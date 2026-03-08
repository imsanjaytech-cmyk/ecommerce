@extends('layouts.adminlayout')
@section('page-title', 'Settings')
@section('breadcrumb', 'Home / Settings')

@section('content')

<div class="row g-4">

    {{-- ══════════════════════════════
         LEFT NAV
    ══════════════════════════════ --}}
    <div class="col-md-3">
        <div class="card-w" style="padding:10px;position:sticky;top:80px;">
            @php
            $tabs = [
                ['id' => 'profile',       'icon' => 'bi-person-fill',   'label' => 'Profile'],
                ['id' => 'store',         'icon' => 'bi-shop',          'label' => 'Store'],
                ['id' => 'notifications', 'icon' => 'bi-bell-fill',     'label' => 'Notifications'],
                ['id' => 'security',      'icon' => 'bi-lock-fill',     'label' => 'Security'],
                ['id' => 'shipping',      'icon' => 'bi-truck',         'label' => 'Shipping'],
                ['id' => 'appearance',    'icon' => 'bi-palette-fill',  'label' => 'Appearance'],
            ];
            @endphp
            @foreach($tabs as $t)
            <a href="#"
               class="nav-link-item settings-tab-link {{ $loop->first ? 'active' : '' }}"
               data-tab="{{ $t['id'] }}"
               style="margin-bottom:2px;">
                <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════
         PANELS
    ══════════════════════════════ --}}
    <div class="col-md-9">

        <div id="toastContainer"
             style="position:fixed;bottom:24px;right:24px;z-index:9999;
                    display:flex;flex-direction:column-reverse;gap:8px;pointer-events:none;"></div>

        {{-- ════════════ PROFILE ════════════ --}}
        <div class="settings-panel" id="panel-profile">
            <div class="card-w">
                <div class="sec-header">
                    <div>
                        <div class="sec-title">Profile Settings</div>
                        <div class="sec-sub">Updates your name and email in the users table</div>
                    </div>
                </div>

                {{-- Avatar block --}}
                <div style="display:flex;align-items:center;gap:20px;margin-bottom:26px;
                            padding-bottom:22px;border-bottom:1px solid var(--border-col);">
                    <div id="avatarWrap"
                         style="width:76px;height:76px;border-radius:18px;overflow:hidden;flex-shrink:0;
                                background:linear-gradient(135deg,var(--primary),var(--secondary));
                                display:flex;align-items:center;justify-content:center;
                                font-size:28px;font-weight:800;color:white;">
                        @php
                            $parts    = explode(' ', trim($user->name));
                            $initials = strtoupper(substr($parts[0] ?? 'A', 0, 1) . substr($parts[1] ?? '', 0, 1));
                        @endphp
                        <span id="avatarInitials">{{ $initials }}</span>
                    </div>
                    <div>
                        <div id="displayName"
                             style="font-weight:700;font-size:16px;color:var(--dark);margin-bottom:2px;">
                            {{ $user->name }}
                        </div>
                        <div style="color:var(--text-muted);font-size:13px;margin-bottom:2px;">
                            {{ $user->email }}
                        </div>
                        <div style="font-size:11.5px;color:var(--primary);font-weight:600;">
                            {{ ucfirst($user->role ?? 'admin') }}
                        </div>
                    </div>
                </div>

                {{-- Profile form — only name & email (users table fields) --}}
                <form id="profileForm" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="lbl">Full Name <span style="color:var(--primary)">*</span></label>
                            <input type="text" class="inp" name="name" value="{{ $user->name }}"
                                   placeholder="Your full name">
                            <div class="field-err" id="err_name"></div>
                        </div>
                        <div class="col-12">
                            <label class="lbl">Email Address <span style="color:var(--primary)">*</span></label>
                            <input type="email" class="inp" name="email" value="{{ $user->email }}"
                                   placeholder="admin@example.com">
                            <div class="field-err" id="err_email"></div>
                        </div>
                        <div class="col-12">
                            <label class="lbl">Phone</label>
                            <input type="tel" class="inp" name="phone"
                                   value=""
                                   placeholder="+91 98765 43210">
                        </div>
                        <div class="col-12">
                            <label class="lbl">Bio</label>
                            <textarea class="inp" name="bio" rows="3"
                                      placeholder="Brief description about yourself..."></textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn-o" onclick="window.location.reload()">Discard</button>
                            <button type="button" class="btn-p" id="saveProfileBtn">
                                <span id="saveProfileText"><i class="bi bi-check-lg"></i> Save Changes</span>
                                <span id="saveProfileSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ════════════ STORE ════════════ --}}
        <div class="settings-panel" id="panel-store" style="display:none;">
            <div class="card-w">
                <div class="sec-header">
                    <div>
                        <div class="sec-title">Store Configuration</div>
                        <div class="sec-sub">General store settings and regional preferences</div>
                    </div>
                </div>
                <form id="storeForm" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="lbl">Store Name <span style="color:var(--primary)">*</span></label>
                            <input type="text" class="inp" name="store_name"
                                   value="{{ $store['store_name'] ?? '' }}" placeholder="My Store">
                            <div class="field-err" id="err_store_name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="lbl">Store URL</label>
                            <input type="text" class="inp" name="store_url"
                                   value="{{ $store['store_url'] ?? '' }}" placeholder="mystore.com">
                        </div>
                        <div class="col-md-6">
                            <label class="lbl">Store Email</label>
                            <input type="email" class="inp" name="store_email"
                                   value="{{ $store['store_email'] ?? '' }}" placeholder="hello@mystore.com">
                        </div>
                        <div class="col-md-6">
                            <label class="lbl">Store Phone</label>
                            <input type="tel" class="inp" name="store_phone"
                                   value="{{ $store['store_phone'] ?? '' }}" placeholder="+91 98765 43210">
                        </div>
                        <div class="col-md-5">
                            <label class="lbl">Currency <span style="color:var(--primary)">*</span></label>
                            <select class="inp" name="currency">
                                @foreach(['INR'=>'INR — Indian Rupee','USD'=>'USD — US Dollar','EUR'=>'EUR — Euro','GBP'=>'GBP — British Pound','AED'=>'AED — UAE Dirham'] as $code => $label)
                                <option value="{{ $code }}" {{ ($store['currency'] ?? 'INR') === $code ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="lbl">Symbol</label>
                            <input type="text" class="inp" name="currency_symbol"
                                   value="{{ $store['currency_symbol'] ?? '₹' }}" maxlength="5" placeholder="₹">
                        </div>
                        <div class="col-md-4">
                            <label class="lbl">Timezone <span style="color:var(--primary)">*</span></label>
                            <select class="inp" name="timezone">
                                @foreach(['Asia/Kolkata'=>'IST (UTC+5:30)','UTC'=>'UTC','America/New_York'=>'EST (UTC-5)','America/Los_Angeles'=>'PST (UTC-8)','Europe/London'=>'GMT','Asia/Dubai'=>'GST (UTC+4)'] as $tz => $label)
                                <option value="{{ $tz }}" {{ ($store['timezone'] ?? 'Asia/Kolkata') === $tz ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="lbl">Store Address</label>
                            <textarea class="inp" name="store_address" rows="2"
                                      placeholder="Full store / warehouse address">{{ $store['store_address'] ?? '' }}</textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn-p" id="saveStoreBtn">
                                <span id="saveStoreText"><i class="bi bi-check-lg"></i> Save Store Settings</span>
                                <span id="saveStoreSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ════════════ NOTIFICATIONS ════════════ --}}
        <div class="settings-panel" id="panel-notifications" style="display:none;">
            <div class="card-w">
                <div class="sec-header">
                    <div>
                        <div class="sec-title">Notification Preferences</div>
                        <div class="sec-sub">Control what alerts you receive</div>
                    </div>
                    <button type="button" class="btn-p" id="saveNotifBtn">
                        <span id="saveNotifText"><i class="bi bi-check-lg"></i> Save</span>
                        <span id="saveNotifSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                    </button>
                </div>
                <form id="notifForm" novalidate>
                    @csrf
                    @php
                    $toggles = [
                        ['key' => 'notif_new_order',     'label' => 'New Order Alerts',    'sub' => 'Get notified for every new order placed'],
                        ['key' => 'notif_low_stock',     'label' => 'Low Stock Warnings',  'sub' => 'Alert when stock drops below threshold'],
                        ['key' => 'notif_reviews',       'label' => 'Customer Reviews',    'sub' => 'Notify when customers leave reviews'],
                        ['key' => 'notif_weekly_report', 'label' => 'Weekly Reports',      'sub' => 'Receive weekly performance summary emails'],
                        ['key' => 'notif_security',      'label' => 'Security Alerts',     'sub' => 'Login attempts and suspicious activity'],
                        ['key' => 'notif_marketing',     'label' => 'Marketing Emails',    'sub' => 'Promotional and newsletter updates'],
                    ];
                    @endphp
                    @foreach($toggles as $t)
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                padding:15px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border-col);' : '' }}">
                        <div>
                            <div style="font-size:13.5px;font-weight:600;color:var(--dark);">{{ $t['label'] }}</div>
                            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">{{ $t['sub'] }}</div>
                        </div>
                        <div class="form-check form-switch mb-0" style="padding-left:0;">
                            <input class="form-check-input" type="checkbox"
                                   name="{{ $t['key'] }}"
                                   style="width:42px;height:22px;cursor:pointer;margin-left:0;"
                                   {{ ($notifications[$t['key']] ?? false) ? 'checked' : '' }}>
                        </div>
                    </div>
                    @endforeach
                </form>
            </div>
        </div>

        {{-- ════════════ SECURITY ════════════ --}}
        <div class="settings-panel" id="panel-security" style="display:none;">
            <div class="card-w mb-4">
                <div class="sec-header">
                    <div>
                        <div class="sec-title">Change Password</div>
                        <div class="sec-sub">Use a strong password with at least 8 characters</div>
                    </div>
                </div>
                <form id="securityForm" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="lbl">Current Password <span style="color:var(--primary)">*</span></label>
                            <div style="position:relative;">
                                <input type="password" class="inp" name="current_password"
                                       id="currentPwdInput" placeholder="Enter current password"
                                       style="padding-right:42px;">
                                <button type="button" class="pwd-toggle" onclick="togglePwd('currentPwdInput',this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="field-err" id="err_current_password"></div>
                        </div>
                        <div class="col-md-8">
                            <label class="lbl">New Password <span style="color:var(--primary)">*</span></label>
                            <div style="position:relative;">
                                <input type="password" class="inp" name="password"
                                       id="newPwdInput" placeholder="Min 8 chars, letters + numbers"
                                       style="padding-right:42px;" oninput="checkPwdStrength(this.value)">
                                <button type="button" class="pwd-toggle" onclick="togglePwd('newPwdInput',this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div style="margin-top:8px;">
                                <div style="height:4px;background:#f0f2f7;border-radius:4px;overflow:hidden;">
                                    <div id="pwdStrengthBar"
                                         style="height:100%;width:0;border-radius:4px;transition:width .3s,background .3s;"></div>
                                </div>
                                <div id="pwdStrengthLabel"
                                     style="font-size:11px;margin-top:4px;color:var(--text-muted);font-weight:600;"></div>
                            </div>
                            <div class="field-err" id="err_password"></div>
                        </div>
                        <div class="col-md-8">
                            <label class="lbl">Confirm New Password <span style="color:var(--primary)">*</span></label>
                            <div style="position:relative;">
                                <input type="password" class="inp" name="password_confirmation"
                                       id="confirmPwdInput" placeholder="Re-enter new password"
                                       style="padding-right:42px;">
                                <button type="button" class="pwd-toggle" onclick="togglePwd('confirmPwdInput',this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn-p" id="saveSecurityBtn">
                                <span id="saveSecurityText"><i class="bi bi-lock"></i> Update Password</span>
                                <span id="saveSecuritySpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-w">
                <div class="sec-header">
                    <div>
                        <div class="sec-title">Active Sessions</div>
                        <div class="sec-sub">Devices currently logged in</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:42px;height:42px;background:var(--pink-soft);border-radius:10px;
                                    display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-laptop" style="color:var(--primary);font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:13.5px;">This Device</div>
                            <div style="font-size:12px;color:var(--text-muted);">
                                {{ request()->ip() }} — {{ now()->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                    <span class="bdg bdg-success">Current</span>
                </div>
            </div>
        </div>

        {{-- ════════════ SHIPPING ════════════ --}}
        <div class="settings-panel" id="panel-shipping" style="display:none;">
            <div class="card-w">
                <div class="sec-header">
                    <div>
                        <div class="sec-title">Shipping Settings</div>
                        <div class="sec-sub">Configure delivery fees and dispatch address</div>
                    </div>
                </div>
                <form id="shippingForm" novalidate>
                    @csrf
                    @php $sym = $store['currency_symbol'] ?? '₹'; @endphp
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="lbl">Free Shipping Above ({{ $sym }})</label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:13px;top:50%;transform:translateY(-50%);
                                             color:var(--text-muted);font-weight:600;">{{ $sym }}</span>
                                <input type="number" min="0" class="inp" name="free_shipping_threshold"
                                       value="{{ $shipping['free_shipping_threshold'] ?? 999 }}"
                                       style="padding-left:28px;" placeholder="999">
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                                Orders above this get free shipping
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="lbl">Default Shipping Fee ({{ $sym }})</label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:13px;top:50%;transform:translateY(-50%);
                                             color:var(--text-muted);font-weight:600;">{{ $sym }}</span>
                                <input type="number" min="0" class="inp" name="default_shipping_fee"
                                       value="{{ $shipping['default_shipping_fee'] ?? 99 }}"
                                       style="padding-left:28px;" placeholder="99">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="lbl">Shipping Origin / Warehouse Address</label>
                            <textarea class="inp" name="shipping_origin" rows="2"
                                      placeholder="Full dispatch address">{{ $shipping['shipping_origin'] ?? '' }}</textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn-p" id="saveShippingBtn">
                                <span id="saveShippingText"><i class="bi bi-check-lg"></i> Save Shipping</span>
                                <span id="saveShippingSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ════════════ APPEARANCE ════════════ --}}
        <div class="settings-panel" id="panel-appearance" style="display:none;">
            <div class="card-w">
                <div class="sec-header">
                    <div>
                        <div class="sec-title">Appearance</div>
                        <div class="sec-sub">Brand colours and store logo</div>
                    </div>
                </div>
                <form id="appearanceForm" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="lbl">Primary Color</label>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <input type="color" id="primaryPicker"
                                       value="{{ $appearance['primary_color'] ?? '#ff4d6d' }}"
                                       style="width:44px;height:44px;border:none;padding:3px;
                                              border-radius:10px;cursor:pointer;flex-shrink:0;">
                                <input type="text" class="inp" name="primary_color" id="primaryText"
                                       value="{{ $appearance['primary_color'] ?? '#ff4d6d' }}"
                                       placeholder="#ff4d6d" style="font-family:monospace;">
                            </div>
                            <div style="height:6px;border-radius:6px;margin-top:8px;
                                        background:{{ $appearance['primary_color'] ?? '#ff4d6d' }};"
                                 id="primaryBar"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="lbl">Secondary Color</label>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <input type="color" id="secondaryPicker"
                                       value="{{ $appearance['secondary_color'] ?? '#ff8fab' }}"
                                       style="width:44px;height:44px;border:none;padding:3px;
                                              border-radius:10px;cursor:pointer;flex-shrink:0;">
                                <input type="text" class="inp" name="secondary_color" id="secondaryText"
                                       value="{{ $appearance['secondary_color'] ?? '#ff8fab' }}"
                                       placeholder="#ff8fab" style="font-family:monospace;">
                            </div>
                            <div style="height:6px;border-radius:6px;margin-top:8px;
                                        background:{{ $appearance['secondary_color'] ?? '#ff8fab' }};"
                                 id="secondaryBar"></div>
                        </div>
                        <div class="col-12">
                            <label class="lbl">Store Logo</label>
                            <div class="upload-zone" style="padding:20px;cursor:pointer;"
                                 onclick="document.getElementById('logoInput').click()">
                                @if(!empty($appearance['logo_path']))
                                    <img id="logoPreview"
                                         src="{{ asset('storage/'.$appearance['logo_path']) }}"
                                         alt="Logo"
                                         style="max-height:80px;object-fit:contain;">
                                @else
                                    <div id="logoPlaceholder">
                                        <div class="uz-icon"><i class="bi bi-image"></i></div>
                                        <div class="uz-text">Click to upload logo</div>
                                        <div class="uz-sub">PNG, SVG, WEBP — max 2MB</div>
                                    </div>
                                @endif
                            </div>
                            <input type="file" id="logoInput" name="logo" accept="image/*" style="display:none;">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn-p" id="saveAppearanceBtn">
                                <span id="saveAppearanceText"><i class="bi bi-check-lg"></i> Save Appearance</span>
                                <span id="saveAppearanceSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
.settings-panel { animation: fadeIn .2s ease; }
@keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }
.pwd-toggle {
    position:absolute;right:12px;top:50%;transform:translateY(-50%);
    background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:14px;padding:4px;
}
.pwd-toggle:hover { color:var(--primary); }
.field-err { font-size:11.5px;color:var(--danger);margin-top:4px;min-height:16px; }
.inp.is-invalid { border-color:var(--danger)!important; }
</style>

@endsection

@push('scripts')
<script>
window.SETTINGS = {
    csrf: '{{ csrf_token() }}',
    routes: {
        profile:       '{{ route("admin.settings.profile") }}',
        store:         '{{ route("admin.settings.store") }}',
        notifications: '{{ route("admin.settings.notifications") }}',
        security:      '{{ route("admin.settings.security") }}',
        shipping:      '{{ route("admin.settings.shipping") }}',
        appearance:    '{{ route("admin.settings.appearance") }}',
    },
};
</script>
<script src="{{ asset('js/settings.js') }}"></script>
@endpush
