@extends('layouts.adminlayout')
@section('page-title', 'Customers')
@section('breadcrumb', 'Home / Customers')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1a7cd4;"></div>
            <div class="stat-icon si-blue"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-value">{{ number_format($counts['customer']) }}</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Registered users</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-deco" style="background:var(--primary);"></div>
            <div class="stat-icon si-pink"><i class="bi bi-shop"></i></div>
            <div class="stat-label">Vendors</div>
            <div class="stat-value">{{ number_format($counts['vendor']) }}</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Active vendors</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-deco" style="background:#d97706;"></div>
            <div class="stat-icon si-orange"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="stat-label">Admins</div>
            <div class="stat-value">{{ number_format($counts['admin']) }}</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> System admins</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1f9c4a;"></div>
            <div class="stat-icon si-green"><i class="bi bi-person-fill-add"></i></div>
            <div class="stat-label">New This Month</div>
            <div class="stat-value">{{ number_format($newThisMonth) }}</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> All roles</div>
        </div>
    </div>
</div>
<div class="card-w">

    {{-- ── Header ── --}}
    <div class="sec-header flex-wrap gap-2">
        <div>
            <div class="sec-title">User Management</div>
            <div class="sec-sub">{{ number_format($users->total()) }} {{ ucfirst($role) }}s found</div>
        </div>
        <form method="GET" action="{{ route('admin.customers') }}" style="display:flex;gap:8px;">
            <input type="hidden" name="role" value="{{ $role }}">
            <div class="search-wrap" style="width:220px;">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search name or email...">
            </div>
            <button type="submit" class="btn-o"><i class="bi bi-search"></i></button>
        </form>
    </div>

    {{-- ── Role Tabs ── --}}
    <div style="display:flex;gap:6px;margin-bottom:18px;border-bottom:1.5px solid var(--border-col);padding-bottom:0;">
        @foreach(['customer' => 'Customers', 'vendor' => 'Vendors', 'admin' => 'Admins'] as $r => $label)
        <a href="{{ route('admin.customers', ['role' => $r]) }}"
           style="padding:8px 18px;font-size:13px;font-weight:600;border-radius:8px 8px 0 0;
                  text-decoration:none;border:1.5px solid transparent;margin-bottom:-1.5px;
                  {{ $role === $r
                      ? 'background:white;border-color:var(--border-col);border-bottom-color:white;color:var(--primary);'
                      : 'color:var(--text-muted);' }}">
            {{ $label }}
            <span style="font-size:11px;margin-left:4px;opacity:.7;">({{ number_format($counts[$r]) }})</span>
        </a>
        @endforeach
    </div>

    {{-- ── Table ── --}}
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:10px;
                                        background:linear-gradient(135deg,var(--primary),var(--secondary));
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:13px;font-weight:700;color:white;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px;">{{ $user->name }}</div>
                                <div style="font-size:11px;color:var(--text-muted);">ID #{{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="bdg bdg-danger">Admin</span>
                        @elseif($user->role === 'vendor')
                            <span class="bdg bdg-info">Vendor</span>
                        @else
                            <span class="bdg bdg-gray">Customer</span>
                        @endif
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted);">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="bdg bdg-success">Verified</span>
                        @else
                            <span class="bdg bdg-warning">Unverified</span>
                        @endif
                    </td>
                    <td>
                        <div class="act-row">
                            <div class="act-btn" title="View"><i class="bi bi-eye"></i></div>
                            <div class="act-btn" title="Email"><i class="bi bi-envelope"></i></div>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.customers.destroy', $user) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn del" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:50px;color:var(--text-muted);">
                        <i class="bi bi-people" style="font-size:32px;opacity:.25;display:block;margin-bottom:10px;"></i>
                        No {{ $role }}s found
                        @if($search) for "<strong>{{ $search }}</strong>" @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Pagination ── --}}
    @if($users->hasPages())
    <div class="pgn">
        <span class="pgn-info">
            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }}
            of {{ number_format($users->total()) }} {{ $role }}s
        </span>
        <div class="pgn-btns">
            @if($users->onFirstPage())
                <div class="pgn-btn" style="opacity:.4;pointer-events:none;">
                    <i class="bi bi-chevron-left" style="font-size:10px;"></i>
                </div>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="pgn-btn" style="text-decoration:none;">
                    <i class="bi bi-chevron-left" style="font-size:10px;"></i>
                </a>
            @endif

            @foreach($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}" class="pgn-btn {{ $page === $users->currentPage() ? 'active' : '' }}" style="text-decoration:none;">
                    {{ $page }}
                </a>
            @endforeach

            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="pgn-btn" style="text-decoration:none;">
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                </a>
            @else
                <div class="pgn-btn" style="opacity:.4;pointer-events:none;">
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                </div>
            @endif
        </div>
    </div>
    @endif

</div>

@endsection
