<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('page-title','Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #ff4d6d;
            --secondary: #ff8fab;
            --dark: #1a1a1a;
            --light: #f8f9fa;
            --gray: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --radius: 12px;
            --transition: all 0.3s ease;
            --pink-soft: #fff0f3;
            --pink-mid: #ffe4ea;
            --pink-border: rgba(255, 77, 109, 0.15);
            --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 25px rgba(0, 0, 0, 0.08);
            --shadow-pink: 0 8px 25px rgba(255, 77, 109, 0.2);
            --sidebar-w: 255px;
            --text-body: #3d3d3d;
            --text-muted: #9199a6;
            --border-col: #eef0f4;
            --bg-page: #f5f6fb;
            --white: #ffffff;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-page);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
            font-size: 14px;
        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--pink-mid); border-radius: 10px; }

        /* ═══ SIDEBAR ═══ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--white);
            border-right: 1px solid var(--border-col);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 300;
            overflow-y: auto;
            box-shadow: 4px 0 24px rgba(255, 77, 109, 0.06);
            transition: transform 0.3s ease;
        }

        /* Mobile: hide sidebar off-screen */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 0 0 40px rgba(0,0,0,0.2);
            }
        }

        /* Overlay behind sidebar on mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 290;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { display: block; }

        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--border-col);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-pink);
            flex-shrink: 0;
        }
        .brand-mark i { color: white; font-size: 18px; }
        .brand-name { font-weight: 800; font-size: 15.5px; color: var(--dark); line-height: 1; }
        .brand-sub { font-size: 10px; font-weight: 500; color: var(--text-muted); letter-spacing: 1.3px; text-transform: uppercase; margin-top: 2px; }

        .sidebar-nav { padding: 14px 12px; flex: 1; }

        .nav-group-label {
            font-size: 9.5px; font-weight: 700; letter-spacing: 1.8px;
            text-transform: uppercase; color: var(--text-muted);
            padding: 14px 10px 6px;
        }

        .nav-link-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 13px; border-radius: 10px;
            color: #7a828e; text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            margin-bottom: 2px; transition: var(--transition);
            position: relative;
        }
        .nav-link-item i { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }
        .nav-link-item:hover { background: var(--pink-soft); color: var(--primary); }
        .nav-link-item.active {
            background: linear-gradient(135deg, var(--pink-soft), var(--pink-mid));
            color: var(--primary); font-weight: 600;
            box-shadow: inset 3px 0 0 var(--primary);
        }

        .nav-badge {
            margin-left: auto;
            background: var(--primary); color: white;
            font-size: 10px; font-weight: 700;
            padding: 1px 7px; border-radius: 20px; line-height: 1.7;
        }

        .sidebar-footer { padding: 14px; border-top: 1px solid var(--border-col); }
        .admin-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: var(--radius);
            background: var(--pink-soft); cursor: pointer; transition: var(--transition);
        }
        .admin-card:hover { background: var(--pink-mid); }
        .admin-av {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 9px; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; color: white; flex-shrink: 0;
        }
        .admin-nm { font-size: 12.5px; font-weight: 600; color: var(--dark); }
        .admin-rl { font-size: 10.5px; color: var(--text-muted); }

        /* ═══ MAIN ═══ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column;
            min-width: 0;
        }

        @media (max-width: 991px) {
            .main-wrap { margin-left: 0; }
        }

        /* ═══ TOPBAR ═══ */
        .topbar {
            position: sticky; top: 0; z-index: 200;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-col);
            height: 62px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 20px;
            box-shadow: var(--shadow-sm);
            gap: 12px;
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; min-width: 0; }

        /* Hamburger button – visible only on mobile/tablet */
        .sidebar-toggle-btn {
            display: none;
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1.5px solid var(--border-col);
            background: white;
            align-items: center; justify-content: center;
            cursor: pointer; color: var(--gray);
            font-size: 17px; flex-shrink: 0;
            transition: var(--transition);
        }
        .sidebar-toggle-btn:hover { border-color: var(--secondary); color: var(--primary); background: var(--pink-soft); }

        @media (max-width: 991px) {
            .sidebar-toggle-btn { display: flex; }
        }

        .topbar-title-wrap { min-width: 0; }
        .topbar-title { font-size: 16px; font-weight: 700; color: var(--dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-bc { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

        .topbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

        .search-wrap {
            display: flex; align-items: center; gap: 8px;
            background: var(--bg-page); border: 1.5px solid var(--border-col);
            border-radius: 10px; padding: 8px 14px;
            width: 210px; transition: var(--transition);
        }
        .search-wrap:focus-within { border-color: var(--secondary); background: var(--pink-soft); }
        .search-wrap i { color: var(--text-muted); font-size: 13px; }
        .search-wrap input {
            background: none; border: none; outline: none;
            font-family: 'Poppins', sans-serif; font-size: 12.5px; color: var(--dark); width: 100%;
        }
        .search-wrap input::placeholder { color: #c4c9d4; }

        /* Hide search bar on small screens, show icon only */
        @media (max-width: 767px) {
            .search-wrap { display: none; }
            .topbar-title { font-size: 14px; }
        }

        .tb-btn {
            width: 36px; height: 36px; border-radius: 10px;
            border: 1.5px solid var(--border-col); background: white;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--gray); font-size: 14px;
            position: relative; transition: var(--transition); flex-shrink: 0;
        }
        .tb-btn:hover { border-color: var(--secondary); color: var(--primary); background: var(--pink-soft); }
        .notif-dot {
            position: absolute; top: 7px; right: 7px;
            width: 7px; height: 7px;
            background: var(--primary); border-radius: 50%; border: 1.5px solid white;
        }

        /* Hide some topbar buttons on very small screens */
        @media (max-width: 480px) {
            .tb-btn.hide-xs { display: none; }
        }

        /* ═══ PAGE BODY ═══ */
        .page-body { padding: 20px; flex: 1; }

        @media (max-width: 767px) {
            .page-body { padding: 14px; }
        }

        /* ═══ CARDS ═══ */
        .card-w {
            background: var(--white); border: 1px solid var(--border-col);
            border-radius: 16px; padding: 22px;
            box-shadow: var(--shadow-sm); transition: var(--transition);
        }
        .card-w:hover { box-shadow: var(--shadow-md); }

        .stat-card {
            background: var(--white); border: 1px solid var(--border-col);
            border-radius: 16px; padding: 22px;
            box-shadow: var(--shadow-sm); transition: var(--transition);
            position: relative; overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

        @media (max-width: 575px) {
            .stat-card, .card-w { padding: 16px; border-radius: 12px; }
        }

        .stat-deco { position: absolute; right: -16px; top: -16px; width: 88px; height: 88px; border-radius: 50%; opacity: 0.07; }
        .stat-icon { width: 46px; height: 46px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 14px; }
        .si-pink  { background: linear-gradient(135deg, #fff0f3, #ffe4ea); color: var(--primary); }
        .si-green { background: linear-gradient(135deg, #e8f8ee, #d1f1dc); color: #1f9c4a; }
        .si-blue  { background: linear-gradient(135deg, #e8f4ff, #d1e9ff); color: #1a7cd4; }
        .si-orange{ background: linear-gradient(135deg, #fff5e8, #ffe8c8); color: #d97706; }
        .stat-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .stat-value { font-size: 26px; font-weight: 800; color: var(--dark); line-height: 1; margin-bottom: 10px; }
        .stat-change { font-size: 11.5px; font-weight: 600; display: flex; align-items: center; gap: 3px; }
        .ch-up  { color: #1f9c4a; }
        .ch-down{ color: var(--danger); }

        .sec-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; flex-wrap: wrap; gap: 8px; }
        .sec-title { font-size: 15px; font-weight: 700; color: var(--dark); }
        .sec-sub { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

        /* ═══ TABLE – scrollable on mobile ═══ */
        .table-responsive-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        .tbl { width: 100%; border-collapse: collapse; min-width: 700px; }
        .tbl thead tr { border-bottom: 1.5px solid var(--border-col); }
        .tbl th { padding: 10px 14px; font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: var(--text-muted); text-align: left; white-space: nowrap; }
        .tbl td { padding: 13px 14px; font-size: 13.5px; border-bottom: 1px solid #f4f5f8; vertical-align: middle; }
        .tbl tbody tr:last-child td { border-bottom: none; }
        .tbl tbody tr { transition: background 0.15s; cursor: default; }
        .tbl tbody tr:hover { background: var(--pink-soft); }

        /* Hide less critical columns on smaller screens */
        @media (max-width: 991px) {
            .tbl { min-width: 600px; }
            .col-sales, .col-sku { display: none; }
        }
        @media (max-width: 767px) {
            .col-cat, .col-feat { display: none; }
        }

        /* ═══ BADGES ═══ */
        .bdg { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 7px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .bdg-success { background: #e8f8ee; color: #1f9c4a; }
        .bdg-warning { background: #fff5e8; color: #d97706; }
        .bdg-danger  { background: #fee8eb; color: var(--danger); }
        .bdg-info    { background: #e8f4ff; color: #1a7cd4; }
        .bdg-pink    { background: var(--pink-mid); color: var(--primary); }
        .bdg-gray    { background: #f1f3f5; color: var(--gray); }

        /* ═══ BUTTONS ═══ */
        .btn-p {
            background: linear-gradient(135deg, var(--primary), #e8304d);
            color: white; border: none; padding: 9px 20px;
            border-radius: 10px; font-family: 'Poppins', sans-serif;
            font-size: 13px; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; gap: 7px;
            transition: var(--transition); box-shadow: var(--shadow-pink);
            text-decoration: none; white-space: nowrap;
        }
        .btn-p:hover { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(255, 77, 109, 0.3); color: white; }

        .btn-o {
            background: white; color: var(--gray);
            border: 1.5px solid var(--border-col); padding: 8px 18px;
            border-radius: 10px; font-family: 'Poppins', sans-serif;
            font-size: 13px; font-weight: 500; cursor: pointer;
            display: inline-flex; align-items: center; gap: 7px;
            transition: var(--transition); text-decoration: none; white-space: nowrap;
        }
        .btn-o:hover { border-color: var(--secondary); color: var(--primary); background: var(--pink-soft); }

        @media (max-width: 480px) {
            .btn-p, .btn-o { padding: 8px 14px; font-size: 12px; }
        }

        /* ═══ FORM INPUTS ═══ */
        .lbl { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 6px; }
        .inp {
            width: 100%; background: var(--bg-page); border: 1.5px solid var(--border-col);
            border-radius: 10px; padding: 10px 14px;
            font-family: 'Poppins', sans-serif; font-size: 13.5px; color: var(--dark);
            transition: var(--transition);
        }
        .inp:focus { outline: none; border-color: var(--secondary); background: var(--pink-soft); box-shadow: 0 0 0 3px rgba(255, 77, 109, 0.08); }
        .inp::placeholder { color: #c4c9d4; }

        /* ═══ ACTION BUTTONS ═══ */
        .act-row { display: flex; gap: 5px; opacity: 0; transition: opacity 0.2s; }
        tr:hover .act-row { opacity: 1; }

        /* Always show on touch devices */
        @media (hover: none) {
            .act-row { opacity: 1; }
        }

        .act-btn {
            width: 28px; height: 28px; border-radius: 7px;
            border: 1.5px solid var(--border-col); background: white;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 12px; color: var(--gray); transition: var(--transition);
        }
        .act-btn:hover { background: var(--pink-soft); border-color: var(--secondary); color: var(--primary); }
        .act-btn.del:hover { background: #fee8eb; border-color: var(--danger); color: var(--danger); }

        /* ═══ PAGINATION ═══ */
        .pgn {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--border-col);
            flex-wrap: wrap; gap: 10px;
        }
        .pgn-info { font-size: 12px; color: var(--text-muted); }
        .pgn-btns { display: flex; gap: 4px; flex-wrap: wrap; }
        .pgn-btn {
            width: 32px; height: 32px; border-radius: 8px;
            border: 1.5px solid var(--border-col); background: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 12.5px; font-family: 'Poppins', sans-serif;
            font-weight: 600; cursor: pointer; color: var(--gray); transition: var(--transition);
        }
        .pgn-btn.active { background: var(--primary); color: white; border-color: var(--primary); box-shadow: var(--shadow-pink); }
        .pgn-btn:hover:not(.active) { border-color: var(--secondary); color: var(--primary); background: var(--pink-soft); }

        /* ═══ MODAL ═══ */
        .modal-content { border: none; border-radius: 18px; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.12); }
        .modal-header { border-bottom: 1px solid var(--border-col); padding: 20px 24px 16px; }
        .modal-title { font-weight: 700; font-size: 15px; color: var(--dark); }
        .modal-footer { border-top: 1px solid var(--border-col); padding: 14px 22px; }

        @media (max-width: 575px) {
            .modal-dialog { margin: 8px; }
            .modal-content { border-radius: 14px; }
            .modal-header { padding: 16px 16px 12px; }
            .modal-body { padding: 16px !important; }
            .modal-footer { padding: 12px 16px; flex-direction: column; gap: 8px; }
            .modal-footer .btn-p, .modal-footer .btn-o { width: 100%; justify-content: center; }
        }

        /* ═══ CHIPS / FILTER TABS ═══ */
        .chip {
            padding: 6px 15px; border-radius: 8px; font-size: 12px; font-weight: 600;
            cursor: pointer; border: 1.5px solid var(--border-col); background: white;
            color: var(--gray); transition: var(--transition); white-space: nowrap;
        }
        .chip:hover, .chip.on { background: var(--pink-soft); border-color: var(--secondary); color: var(--primary); }

        /* Scrollable chip row on mobile */
        .chip-row {
            display: flex; gap: 6px; overflow-x: auto; padding-bottom: 4px;
            -webkit-overflow-scrolling: touch; scrollbar-width: none;
        }
        .chip-row::-webkit-scrollbar { display: none; }

        /* ═══ UPLOAD ZONE ═══ */
        .upload-zone {
            border: 2px dashed var(--pink-border); border-radius: 12px;
            padding: 26px; text-align: center; cursor: pointer;
            background: var(--pink-soft); transition: var(--transition);
        }
        .upload-zone:hover { border-color: var(--secondary); background: var(--pink-mid); }
        .uz-icon  { font-size: 28px; color: var(--secondary); margin-bottom: 8px; }
        .uz-text  { font-size: 13px; font-weight: 600; color: var(--dark); }
        .uz-sub   { font-size: 11px; color: var(--text-muted); margin-top: 3px; }

        /* ═══ FEATURED STAR BTN ═══ */
        .feat-btn {
            width: 30px; height: 30px; border-radius: 8px;
            border: 1.5px solid var(--border-col); background: white;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 13px; color: var(--text-muted);
            transition: var(--transition);
        }
        .feat-btn:hover, .feat-btn.on { background: #fff5e8; border-color: #f59e0b; color: #f59e0b; }

        /* ═══ IMG CHIPS ═══ */
        .img-chip {
            position: relative; width: 72px; height: 72px;
            border-radius: 10px; overflow: hidden;
            border: 1.5px solid var(--border-col); flex-shrink: 0;
        }
        .img-chip img { width: 100%; height: 100%; object-fit: cover; }
        .del-img {
            position: absolute; top: 3px; right: 3px;
            width: 18px; height: 18px; border-radius: 50%;
            background: rgba(220,53,69,.85); color: white;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 10px;
        }

        /* ═══ TOAST ═══ */
        #toastContainer {
            position: fixed; bottom: 20px; right: 20px;
            display: flex; flex-direction: column; gap: 8px;
            z-index: 9999; pointer-events: none;
        }
        @media (max-width: 480px) {
            #toastContainer { bottom: 12px; right: 12px; left: 12px; }
        }

        /* ═══ MISC ═══ */
        .ch-wrap { position: relative; }
        .ch-wrap canvas { width: 100% !important; }

        /* Responsive grid helpers */
        @media (max-width: 575px) {
            .row-cols-sm-1 > * { flex: 0 0 100%; max-width: 100%; }
        }
    </style>
</head>

<body>

    {{-- Overlay for mobile sidebar --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></div>
            <div>
                <div class="brand-name">Shanas Admin</div>
                <div class="brand-sub">Control Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-group-label">Commerce</div>
            <a href="{{ route('admin.orders') }}" class="nav-link-item {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="bi bi-bag-check-fill"></i> Orders
                <!-- <span class="nav-badge"></span> -->
            </a>
            <a href="{{ route('admin.products') }}" class="nav-link-item {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Products
            </a>
            <a href="{{ route('admin.categories') }}" class="nav-link-item {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i> Categories
            </a>
            <a href="{{ route('admin.customers') }}" class="nav-link-item {{ request()->routeIs('admin.customers') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Customers
            </a>

            <div class="nav-group-label">Analytics</div>
            <a href="{{ route('admin.reports') }}" class="nav-link-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line-fill"></i> Reports
            </a>

            <div class="nav-group-label">System</div>
            <a href="{{ route('admin.settings') }}" class="nav-link-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Settings
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-card">
                <div class="admin-av">A</div>
                <div>
                    <div class="admin-nm">{{ auth()->user()->name ?? 'Admin User' }}</div>
                    <div class="admin-rl">Super Administrator</div>
                </div>
                <i class="bi bi-three-dots-vertical ms-auto" style="color:var(--text-muted);font-size:13px;"></i>
            </div>
        </div>
    </aside>

    <div class="main-wrap">
        <header class="topbar">
            <div class="topbar-left">
                {{-- Hamburger: only shows on mobile/tablet --}}
                <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle menu">
                    <i class="bi bi-list"></i>
                </button>
                <div class="topbar-title-wrap">
                    <div class="topbar-title">@yield('page-title','Dashboard')</div>
                    <div class="topbar-bc">@yield('breadcrumb','Home / Dashboard')</div>
                </div>
            </div>
            <div class="topbar-right">
                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search anything...">
                </div>
                <div class="tb-btn" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="notif-dot"></span>
                </div>
                <div class="tb-btn hide-xs" title="Messages"><i class="bi bi-envelope"></i></div>
                <div class="admin-av" style="width:36px;height:36px;border-radius:10px;cursor:pointer;font-size:13px;" title="Account">A</div>
            </div>
        </header>

        <div class="page-body">
            @yield('content')
        </div>
    </div>

    {{-- Toast container --}}
    <div id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        (function () {
            const toggle  = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', () => {
                sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
            });
            overlay.addEventListener('click', closeSidebar);

            // Close on nav link click (mobile UX)
            sidebar.querySelectorAll('.nav-link-item').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) closeSidebar();
                });
            });

            // Close on resize to desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) closeSidebar();
            });
        })();
    </script>

    @stack('page-data')
    <script src="{{ asset('js/chart.js') }}"></script>
    @stack('scripts')
</body>

</html>
