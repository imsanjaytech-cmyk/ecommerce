@extends('layouts.adminlayout')
@section('page-title', 'Settings')
@section('breadcrumb', 'Home / Settings')

@section('content')

<div class="row g-4">

    {{-- SETTINGS NAV --}}
    <div class="col-md-3">
        <div class="card-w" style="padding:10px;">
            @php
            $tabs=[
                ['icon'=>'bi-person-fill',    'label'=>'Profile',       'active'=>true],
                ['icon'=>'bi-shop',           'label'=>'Store'],
                ['icon'=>'bi-bell-fill',      'label'=>'Notifications'],
                ['icon'=>'bi-lock-fill',      'label'=>'Security'],
                ['icon'=>'bi-credit-card-fill','label'=>'Billing'],
                ['icon'=>'bi-truck',          'label'=>'Shipping'],
                ['icon'=>'bi-palette-fill',   'label'=>'Appearance'],
                ['icon'=>'bi-plug-fill',      'label'=>'Integrations'],
            ];
            @endphp
            @foreach($tabs as $t)
            <a href="#" class="nav-link-item {{ isset($t['active'])?'active':'' }}" style="margin-bottom:2px;">
                <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- SETTINGS BODY --}}
    <div class="col-md-9">

        {{-- Profile --}}
        <div class="card-w mb-4">
            <div class="sec-header">
                <div><div class="sec-title">Profile Settings</div><div class="sec-sub">Manage your personal information</div></div>
            </div>

            <div style="display:flex;align-items:center;gap:20px;margin-bottom:26px;padding-bottom:22px;border-bottom:1px solid var(--border-col);">
                <div style="width:72px;height:72px;border-radius:18px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:white;flex-shrink:0;">A</div>
                <div>
                    <div style="font-weight:700;font-size:16px;color:var(--dark);margin-bottom:3px;">Admin User</div>
                    <div style="color:var(--text-muted);font-size:13px;margin-bottom:12px;">admin@nexadmin.com</div>
                    <div style="display:flex;gap:8px;">
                        <button class="btn-p" style="padding:7px 16px;font-size:12px;"><i class="bi bi-upload"></i> Upload Photo</button>
                        <button class="btn-o" style="padding:7px 16px;font-size:12px;">Remove</button>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="lbl">First Name</label>
                    <input type="text" class="inp" value="Admin">
                </div>
                <div class="col-md-6">
                    <label class="lbl">Last Name</label>
                    <input type="text" class="inp" value="User">
                </div>
                <div class="col-md-6">
                    <label class="lbl">Email Address</label>
                    <input type="email" class="inp" value="admin@nexadmin.com">
                </div>
                <div class="col-md-6">
                    <label class="lbl">Phone Number</label>
                    <input type="tel" class="inp" value="+1 (555) 000-0000">
                </div>
                <div class="col-12">
                    <label class="lbl">Bio</label>
                    <textarea class="inp" rows="3" placeholder="Tell something about yourself..."></textarea>
                </div>
                <div class="col-12" style="display:flex;justify-content:flex-end;gap:8px;">
                    <button class="btn-o">Discard</button>
                    <button class="btn-p"><i class="bi bi-check-lg"></i> Save Changes</button>
                </div>
            </div>
        </div>

        {{-- Store Config --}}
        <div class="card-w mb-4">
            <div class="sec-header">
                <div><div class="sec-title">Store Configuration</div><div class="sec-sub">General store settings</div></div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="lbl">Store Name</label>
                    <input type="text" class="inp" value="NexStore">
                </div>
                <div class="col-md-6">
                    <label class="lbl">Store URL</label>
                    <input type="text" class="inp" value="nexstore.com">
                </div>
                <div class="col-md-6">
                    <label class="lbl">Currency</label>
                    <select class="inp">
                        <option selected>USD — US Dollar</option>
                        <option>EUR — Euro</option>
                        <option>GBP — British Pound</option>
                        <option>INR — Indian Rupee</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="lbl">Timezone</label>
                    <select class="inp">
                        <option selected>UTC-5 Eastern Time</option>
                        <option>UTC-8 Pacific Time</option>
                        <option>UTC+0 GMT</option>
                        <option>UTC+5:30 IST</option>
                    </select>
                </div>
                <div class="col-12" style="display:flex;justify-content:flex-end;">
                    <button class="btn-p"><i class="bi bi-check-lg"></i> Save Store Settings</button>
                </div>
            </div>
        </div>

        {{-- Notifications --}}
        <div class="card-w">
            <div class="sec-header">
                <div><div class="sec-title">Notification Preferences</div><div class="sec-sub">Control what alerts you receive</div></div>
            </div>
            @php
            $toggles=[
                ['label'=>'New Order Alerts',    'sub'=>'Get notified for every new order placed',      'on'=>true],
                ['label'=>'Low Stock Warnings',  'sub'=>'Alert when product stock drops below threshold','on'=>true],
                ['label'=>'Customer Reviews',    'sub'=>'Notify when customers leave reviews',           'on'=>false],
                ['label'=>'Weekly Reports',      'sub'=>'Receive weekly performance summary emails',     'on'=>true],
                ['label'=>'Security Alerts',     'sub'=>'Login attempts and suspicious activity',        'on'=>true],
                ['label'=>'Marketing Emails',    'sub'=>'Promotional and newsletter updates',            'on'=>false],
            ];
            @endphp
            @foreach($toggles as $i => $t)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;{{ $i < count($toggles)-1 ? 'border-bottom:1px solid var(--border-col);':'' }}">
                <div>
                    <div style="font-size:13.5px;font-weight:600;color:var(--dark);">{{ $t['label'] }}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">{{ $t['sub'] }}</div>
                </div>
                <div class="form-check form-switch mb-0" style="padding-left:0;">
                    <input class="form-check-input" type="checkbox" style="width:42px;height:22px;cursor:pointer;margin-left:0;" {{ $t['on']?'checked':'' }}>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

@endsection