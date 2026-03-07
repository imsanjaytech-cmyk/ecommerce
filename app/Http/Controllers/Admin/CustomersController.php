<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        $role   = $request->input('role', 'customer');
        $search = $request->input('search', '');

        $users = User::where('role', $role)
            ->when($search, fn($q) =>
                $q->where(fn($inner) =>
                    $inner->where('name',  'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = User::selectRaw("role, count(*) as total")
            ->whereIn('role', ['customer', 'vendor', 'admin'])
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        $counts = array_merge(['customer' => 0, 'vendor' => 0, 'admin' => 0], $counts);

        $newThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.customers', compact('users', 'counts', 'role', 'search', 'newThisMonth'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Admin accounts cannot be deleted this way.');
        }

        $user->delete();

        return back()->with('success', "{$user->name} has been deleted.");
    }
}