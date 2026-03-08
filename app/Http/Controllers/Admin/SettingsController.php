<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

/**
 * SettingsController
 * ─────────────────────────────────────────────────────
 * Profile  → reads/writes  users  table only (name, email, password)
 * All other settings → settings table via Setting model
 * No AdminProfile table anywhere.
 * ─────────────────────────────────────────────────────
 */
class SettingsController extends Controller
{
    /* ══════════════════════════════════════════
     |  INDEX
     ══════════════════════════════════════════ */
    public function index()
    {
        return view('admin.settings', [
            'user'          => Auth::user(),
            'store'         => Settings::group('store'),
            'notifications' => Settings::group('notifications'),
            'shipping'      => Settings::group('shipping'),
            'appearance'    => Settings::group('appearance'),
        ]);
    }

    /* ══════════════════════════════════════════
     |  PROFILE  — users table only
     ══════════════════════════════════════════ */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'  => 'required|string|max:160',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',   // stored in settings as admin_phone
            'bio'   => 'nullable|string|max:500',  // stored in settings as admin_bio
        ]);

        /* Update users table */
        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        /* Store phone & bio in settings (avoids touching users schema) */
        if (array_key_exists('phone', $data)) {
            Settings::set('admin_phone', $data['phone'] ?? '', 'string', 'profile');
        }
        if (array_key_exists('bio', $data)) {
            Settings::set('admin_bio', $data['bio'] ?? '', 'string', 'profile');
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Profile updated successfully.',
            'name'      => $user->fresh()->name,
            'initials'  => $this->initials($user->fresh()->name),
        ]);
    }

    /* ══════════════════════════════════════════
     |  SECURITY — password change
     ══════════════════════════════════════════ */
    public function updateSecurity(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors'  => ['current_password' => ['Current password is incorrect.']],
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
    }

    /* ══════════════════════════════════════════
     |  STORE
     ══════════════════════════════════════════ */
    public function updateStore(Request $request): JsonResponse
    {
        $data = $request->validate([
            'store_name'      => 'required|string|max:120',
            'store_url'       => 'nullable|string|max:200',
            'store_email'     => 'nullable|email|max:150',
            'store_phone'     => 'nullable|string|max:30',
            'store_address'   => 'nullable|string|max:400',
            'currency'        => 'required|string|max:10',
            'currency_symbol' => 'nullable|string|max:5',
            'timezone'        => 'required|string|max:60',
            'date_format'     => 'nullable|string|max:30',
        ]);

        Settings::setMany($data, 'store');

        return response()->json(['success' => true, 'message' => 'Store settings saved.']);
    }

    /* ══════════════════════════════════════════
     |  NOTIFICATIONS
     ══════════════════════════════════════════ */
    public function updateNotifications(Request $request): JsonResponse
    {
        $keys = [
            'notif_new_order', 'notif_low_stock', 'notif_reviews',
            'notif_weekly_report', 'notif_security', 'notif_marketing',
        ];

        foreach ($keys as $key) {
            Settings::set($key, $request->boolean($key) ? '1' : '0', 'boolean', 'notifications');
        }

        return response()->json(['success' => true, 'message' => 'Notification preferences saved.']);
    }

    /* ══════════════════════════════════════════
     |  SHIPPING
     ══════════════════════════════════════════ */
    public function updateShipping(Request $request): JsonResponse
    {
        $data = $request->validate([
            'free_shipping_threshold' => 'required|integer|min:0',
            'default_shipping_fee'    => 'required|integer|min:0',
            'shipping_origin'         => 'nullable|string|max:300',
        ]);

        Settings::setMany($data, 'shipping');

        return response()->json(['success' => true, 'message' => 'Shipping settings saved.']);
    }

    /* ══════════════════════════════════════════
     |  APPEARANCE
     ══════════════════════════════════════════ */
    public function updateAppearance(Request $request): JsonResponse
    {
        $data = $request->validate([
            'primary_color'   => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'logo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $old = Settings::get('logo_path');
            if ($old && ! str_starts_with($old, 'http')) {
                Storage::disk('public')->delete($old);
            }
            $data['logo_path'] = $request->file('logo')->store('branding', 'public');
        }

        unset($data['logo']);
        Settings::setMany($data, 'appearance');

        return response()->json(['success' => true, 'message' => 'Appearance settings saved.']);
    }

    /* ══════════════════════════════════════════
     |  PRIVATE HELPER
     ══════════════════════════════════════════ */
    private function initials(string $name): string
    {
        $parts = explode(' ', trim($name));
        return strtoupper(substr($parts[0] ?? 'A', 0, 1) . substr($parts[1] ?? '', 0, 1));
    }
}
