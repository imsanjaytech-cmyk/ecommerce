@extends('layouts.app')
@section('title', 'My Orders — Shanas')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

:root {
    --pink:      #ff4d6d;
    --pink-d:    #e8304d;
    --pink-soft: #fff0f3;
    --pink-bd:   #ffe4ea;
    --rose:      #ff8fab;
    --page-bg:   #f5f6f8;
    --border:    #e8eaed;
    --text:      #0f1111;
    --muted:     #7a828f;
    --light:     #f8f9fa;
    --card-bg:   #ffffff;
    --font:      'Plus Jakarta Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; }
body { background: var(--page-bg); font-family: var(--font); margin: 0; }

/* ══════════════════════════════════════
   PAGE SHELL
══════════════════════════════════════ */
.op-shell {
    max-width: 1280px;
    margin: 0 auto;
    padding: 28px 20px 80px;
    display: grid;
    grid-template-columns: 220px 1fr 220px;
    gap: 20px;
    align-items: start;
}

/* ══════════════════════════════════════
   SIDEBARS
══════════════════════════════════════ */
.aside-panel {
    position: sticky;
    top: 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.aside-widget {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
}

.aside-head {
    padding: 13px 15px 11px;
    border-bottom: 1px solid #f2f4f7;
    display: flex;
    align-items: center;
    gap: 9px;
}
.aside-head .ah-ico {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .82rem; flex-shrink: 0;
}
.aside-head .ah-title {
    font-size: .76rem; font-weight: 800; color: var(--text); letter-spacing: -.01em;
}
.aside-head .ah-badge {
    margin-left: auto; font-size: .56rem; font-weight: 800;
    letter-spacing: .08em; text-transform: uppercase;
    padding: 2px 7px; border-radius: 20px;
}

.aside-list { list-style: none; margin: 0; padding: 5px 0; }
.aside-list li + li { border-top: 1px solid #f5f7f9; }
.aside-list li a {
    display: flex; align-items: center; gap: 9px;
    padding: 9px 15px; text-decoration: none; transition: background .15s;
}
.aside-list li a:hover { background: var(--pink-soft); }
.al-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.al-info { flex: 1; min-width: 0; }
.al-label { font-size: .76rem; font-weight: 700; color: var(--text); }
.al-sub   { font-size: .64rem; color: var(--muted); margin-top: 1px; }
.al-arr   { font-size: .62rem; color: #ced4da; transition: color .15s, transform .15s; }
.aside-list li a:hover .al-arr { color: var(--pink); transform: translateX(2px); }

.aside-foot {
    border-top: 1px solid #f2f4f7; padding: 9px 15px;
}
.aside-foot a {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 7px; border-radius: 8px; font-size: .71rem; font-weight: 800;
    text-decoration: none; transition: all .18s;
}

/* Promo card */
.aside-promo {
    background: linear-gradient(135deg, #ff4d6d 0%, #ff8fab 100%);
    border-radius: 14px; padding: 18px 15px;
    display: flex; flex-direction: column; gap: 5px;
    position: relative; overflow: hidden; text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    box-shadow: 0 4px 18px rgba(255,77,109,.2);
}
.aside-promo:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(255,77,109,.28); }
.aside-promo::before {
    content: ''; position: absolute; right: -18px; top: -18px;
    width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,.1);
}
.ap-tag   { font-size: .56rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.8); }
.ap-title { font-size: .92rem; font-weight: 900; color: white; line-height: 1.2; }
.ap-sub   { font-size: .68rem; color: rgba(255,255,255,.75); }
.ap-cta   {
    display: inline-flex; align-items: center; gap: 5px; margin-top: 7px;
    padding: 7px 13px; border-radius: 8px; background: rgba(255,255,255,.94);
    color: var(--pink); font-size: .68rem; font-weight: 800; width: fit-content;
    position: relative; z-index: 1; transition: background .18s;
}
.aside-promo:hover .ap-cta { background: white; }

/* Color utils */
.t-amber  { background: #fffbeb; color: #d97706; }
.t-green  { background: #f0fdf4; color: #15803d; }
.t-purple { background: #f5f3ff; color: #6d28d9; }
.t-blue   { background: #eff6ff; color: #1d4ed8; }
.t-slate  { background: #f1f5f9; color: #475569; }
.t-pink   { background: #fff0f3; color: #ff4d6d; }
.badge-amber { background: #fffbeb; color: #d97706; }

.dot-pink   { background: #ff4d6d; }
.dot-amber  { background: #f59e0b; }
.dot-green  { background: #10b981; }
.dot-blue   { background: #3b82f6; }
.dot-purple { background: #8b5cf6; }
.dot-slate  { background: #64748b; }

.foot-pink  { color: var(--pink) !important; }
.foot-pink:hover  { background: var(--pink-soft) !important; }
.foot-green { color: #15803d !important; }
.foot-green:hover { background: #f0fdf4 !important; }

/* ══════════════════════════════════════
   MAIN AREA
══════════════════════════════════════ */
.op-main { min-width: 0; }

.op-title-row {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 6px; margin-bottom: 16px;
}
.op-title-row h1 { font-size: 1.45rem; font-weight: 800; color: var(--text); margin: 0; letter-spacing: -.02em; }
.op-title-row span { font-size: .78rem; color: var(--muted); font-weight: 500; }

/* Filter bar */
.op-filters {
    background: white; border: 1px solid var(--border);
    border-radius: 10px; padding: 4px;
    display: flex; gap: 2px;
    overflow-x: auto; scrollbar-width: none; -webkit-overflow-scrolling: touch;
    margin-bottom: 16px;
}
.op-filters::-webkit-scrollbar { display: none; }
.op-filter-btn {
    padding: 7px 13px; border-radius: 7px; border: none; background: none;
    font-family: var(--font); font-size: .76rem; font-weight: 600;
    color: var(--muted); cursor: pointer; white-space: nowrap; transition: all .18s; flex-shrink: 0;
}
.op-filter-btn:hover { background: var(--pink-soft); color: var(--pink); }
.op-filter-btn.active { background: var(--pink-soft); color: var(--pink); font-weight: 700; }
.fp {
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--pink); color: white;
    font-size: .56rem; font-weight: 800; min-width: 15px; height: 15px;
    border-radius: 8px; padding: 0 4px; margin-left: 4px; vertical-align: middle;
}

/* ══════════════════════════════════════
   ORDER CARD
══════════════════════════════════════ */
.o-card {
    background: white; border: 1px solid var(--border);
    border-radius: 12px; overflow: hidden; margin-bottom: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
    transition: box-shadow .2s, transform .2s;
}
.o-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.09); transform: translateY(-1px); }

/* Card header */
.o-card-head {
    background: #f8f9fb; border-bottom: 1px solid var(--border);
    padding: 10px 16px;
    display: flex; align-items: center; flex-wrap: wrap; gap: 10px;
}
.o-head-meta { display: flex; align-items: center; gap: 0; flex-wrap: wrap; flex: 1; }
.o-meta-col {
    padding: 0 16px 0 0; margin-right: 0;
    border-right: 1px solid var(--border);
    margin-right: 16px; flex-shrink: 0;
}
.o-meta-col:last-of-type { border-right: none; margin-right: 0; flex: 1; min-width: 0; }
.o-meta-lbl { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: 2px; }
.o-meta-val { font-size: .8rem; font-weight: 700; color: var(--text); }
.o-meta-val.pink { color: var(--pink); font-family: monospace; font-size: .76rem; }

.o-head-actions { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; flex-shrink: 0; }

/* Status pills */
.st-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px; font-size: .66rem; font-weight: 700; white-space: nowrap;
}
.st-pending    { background: #fff5e8; color: #b45309; }
.st-processing { background: #eff6ff; color: #1d4ed8; }
.st-shipped    { background: #f5f3ff; color: #6d28d9; }
.st-delivered  { background: #f0fdf4; color: #15803d; }
.st-cancelled  { background: #fef2f2; color: #b91c1c; }
.st-paid       { background: #f0fdf4; color: #15803d; }
.st-unpaid     { background: #fff5e8; color: #b45309; }
.st-failed     { background: #fef2f2; color: #b91c1c; }

.o-track-bar {
    padding: 12px 16px 10px; background: var(--pink-soft); border-bottom: 1px solid var(--pink-bd);
    display: flex; align-items: center; gap: 10px;
}
.trk-label-text {
    font-size: .68rem; font-weight: 700; color: var(--pink);
    white-space: nowrap; flex-shrink: 0;
}
.trk-steps { flex: 1; display: flex; }

.trk-node { display: flex; flex-direction: column; align-items: center; flex: 1; position: relative; }
.trk-node:not(:last-child)::after {
    content: ''; position: absolute; top: 11px; left: 50%; width: 100%; height: 2px;
    background: var(--pink-bd); z-index: 0;
}
.trk-node.done:not(:last-child)::after    { background: var(--pink); }
.trk-node.current:not(:last-child)::after { background: linear-gradient(90deg, var(--pink) 40%, var(--pink-bd) 40%); }

.trk-dot {
    width: 22px; height: 22px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; border: 2px solid #ddd; background: white;
    position: relative; z-index: 1; flex-shrink: 0; transition: all .3s;
}
.trk-node.done .trk-dot    { background: var(--pink); border-color: var(--pink); color: white; box-shadow: 0 2px 6px rgba(255,77,109,.3); }
.trk-node.current .trk-dot { border-color: var(--pink); color: var(--pink); animation: trkPulse 1.8s infinite; }
.trk-node.waiting .trk-dot { color: #ced4da; }

@keyframes trkPulse {
    0%   { box-shadow: 0 0 0 0 rgba(255,77,109,.35); }
    70%  { box-shadow: 0 0 0 5px rgba(255,77,109,0); }
    100% { box-shadow: 0 0 0 0 rgba(255,77,109,0); }
}
.trk-lbl { font-size: .56rem; font-weight: 700; text-align: center; color: #adb5bd; text-transform: uppercase; letter-spacing: .02em; margin-top: 4px; }
.trk-node.done .trk-lbl, .trk-node.current .trk-lbl { color: var(--pink); }

.cancelled-notice {
    padding: 10px 16px; background: #fef2f2; border-bottom: 1px solid #fecaca;
    display: flex; align-items: center; gap: 7px;
    font-size: .76rem; font-weight: 600; color: #b91c1c;
}

/* Items */
.o-items { padding: 0 16px; }
.o-item {
    display: flex; align-items: flex-start; gap: 13px;
    padding: 13px 0; border-bottom: 1px solid #f3f4f6;
}
.o-item:last-child { border-bottom: none; }

.o-item-img {
    width: 66px; height: 66px; border-radius: 8px;
    object-fit: cover; border: 1px solid var(--border); flex-shrink: 0; background: var(--light);
}
.o-item-img-ph {
    width: 66px; height: 66px; border-radius: 8px;
    background: var(--pink-soft); border: 1px solid var(--pink-bd);
    display: flex; align-items: center; justify-content: center;
    color: var(--rose); font-size: 20px; flex-shrink: 0;
}
.o-item-info { flex: 1; min-width: 0; }
.o-item-name {
    font-size: .85rem; font-weight: 600; color: var(--text); line-height: 1.35; margin-bottom: 3px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.o-item-sku  { font-size: .68rem; color: var(--muted); font-family: monospace; margin-bottom: 3px; }
.o-item-meta { font-size: .73rem; color: var(--muted); }
.o-item-price { font-size: .88rem; font-weight: 800; color: var(--text); flex-shrink: 0; white-space: nowrap; text-align: right; }
.o-item-sub   { font-size: .66rem; color: var(--muted); text-align: right; margin-top: 2px; }

/* Address bar */
.o-addr-bar {
    padding: 8px 16px; background: #fafbfc; border-top: 1px solid #f0f2f4;
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; color: var(--muted); overflow: hidden;
}
.o-addr-bar i { color: var(--rose); flex-shrink: 0; }
.o-addr-bar span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* Card footer */
.o-card-foot {
    background: #fafbfc; border-top: 1px solid var(--border);
    padding: 10px 16px;
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap; gap: 10px;
}
.o-foot-total-lbl { font-size: .68rem; color: var(--muted); }
.o-foot-total-val { font-size: .98rem; font-weight: 800; color: var(--text); }
.o-foot-btns { display: flex; gap: 7px; flex-wrap: wrap; align-items: center; }

/* Buttons */
.btn-primary-sm {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 15px; border-radius: 7px;
    background: linear-gradient(135deg, var(--pink), var(--pink-d));
    color: white; font-size: .73rem; font-weight: 700; font-family: var(--font);
    text-decoration: none; border: none; cursor: pointer; transition: all .18s;
    box-shadow: 0 2px 8px rgba(255,77,109,.22); white-space: nowrap;
}
.btn-primary-sm:hover { transform: translateY(-1px); box-shadow: 0 5px 14px rgba(255,77,109,.3); color: white; }

.btn-ghost-sm {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 13px; border-radius: 7px;
    background: white; color: var(--muted); font-size: .73rem; font-weight: 600; font-family: var(--font);
    border: 1px solid var(--border); cursor: pointer; transition: all .18s; text-decoration: none; white-space: nowrap;
}
.btn-ghost-sm:hover { border-color: var(--rose); color: var(--pink); background: var(--pink-soft); }

.btn-cancel-sm {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 13px; border-radius: 7px;
    background: white; color: #b91c1c; font-size: .73rem; font-weight: 600; font-family: var(--font);
    border: 1px solid #fecaca; cursor: pointer; transition: all .18s; text-decoration: none; white-space: nowrap;
}
.btn-cancel-sm:hover { background: #fef2f2; border-color: #b91c1c; color: #991b1b; }

/* Empty */
.op-empty {
    background: white; border: 1px solid var(--border);
    border-radius: 12px; text-align: center; padding: 60px 20px;
}
.op-empty .ei { font-size: 3.2rem; opacity: .12; display: block; margin-bottom: 16px; }
.op-empty h4  { font-weight: 800; font-size: .98rem; color: var(--text); margin-bottom: 6px; }
.op-empty p   { font-size: .8rem; color: var(--muted); margin-bottom: 20px; }

/* ══════════════════════════════════════
   CANCEL MODAL
══════════════════════════════════════ */
.cancel-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.45); z-index: 9999;
    align-items: center; justify-content: center;
    backdrop-filter: blur(3px); padding: 16px;
}
.cancel-overlay.show { display: flex; }
.cancel-modal {
    background: white; border-radius: 18px;
    width: 100%; max-width: 420px; overflow: hidden;
    box-shadow: 0 24px 60px rgba(0,0,0,.22);
    animation: slideUp .25s ease;
}
@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to   { transform: translateY(0); opacity: 1; }
}
.cm-head {
    background: #fef2f2; padding: 18px 20px 14px;
    border-bottom: 1px solid #fecaca;
    display: flex; align-items: center; gap: 12px;
}
.cm-head .ci { font-size: 1.5rem; color: #b91c1c; }
.cm-head h3  { font-size: .95rem; font-weight: 800; color: #1a1a1a; margin: 0 0 2px; }
.cm-head p   { font-size: .73rem; color: #9199a6; margin: 0; }

.cm-body { padding: 18px 20px; }
.cm-body label {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: #9199a6; display: block; margin-bottom: 8px;
}
.reason-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 7px; margin-bottom: 14px; }
.reason-chip {
    padding: 8px 10px; border-radius: 9px; border: 1.5px solid #eef0f4;
    background: white; font-size: .71rem; font-weight: 600; color: #3d3d3d;
    cursor: pointer; text-align: left; font-family: var(--font); transition: all .16s;
    display: flex; align-items: center; gap: 6px;
}
.reason-chip:hover  { border-color: var(--pink); color: var(--pink); background: var(--pink-soft); }
.reason-chip.selected { border-color: var(--pink); background: var(--pink-soft); color: var(--pink); }

.cancel-note {
    width: 100%; padding: 9px 11px; border: 1.5px solid #eef0f4;
    border-radius: 9px; font-family: var(--font); font-size: .78rem; resize: none;
    outline: none; transition: border-color .18s; color: #3d3d3d;
}
.cancel-note:focus { border-color: var(--pink); }

.cancel-warning {
    background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;
    padding: 9px 11px; font-size: .72rem; color: #92400e;
    display: flex; gap: 7px; align-items: flex-start; margin-bottom: 13px;
}
.cancel-warning i { flex-shrink: 0; margin-top: 1px; }

.cm-foot {
    padding: 13px 20px; border-top: 1px solid #f4f5f8;
    display: flex; gap: 9px; justify-content: flex-end;
}
.btn-keep {
    padding: 9px 17px; border-radius: 9px; border: 1.5px solid #eef0f4;
    background: white; font-size: .78rem; font-weight: 700; color: #6c757d;
    cursor: pointer; font-family: var(--font); transition: all .18s;
}
.btn-keep:hover { border-color: var(--border); color: var(--text); }
.btn-confirm-cancel {
    padding: 9px 18px; border-radius: 9px; border: none;
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: white; font-size: .78rem; font-weight: 700; cursor: pointer;
    font-family: var(--font); transition: all .18s;
    box-shadow: 0 3px 12px rgba(239,68,68,.3);
}
.btn-confirm-cancel:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(239,68,68,.35); }
.btn-confirm-cancel:disabled { opacity: .5; cursor: not-allowed; }

/* Toast */
.toast-msg {
    display: none; position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
    background: #15803d; color: white; padding: 11px 22px; border-radius: 10px;
    font-size: .8rem; font-weight: 700; box-shadow: 0 6px 20px rgba(0,0,0,.2);
    z-index: 99999; white-space: nowrap;
}

/* ══════════════════════════════════════
   MOBILE BOTTOM NAV (orders quick jump)
══════════════════════════════════════ */
.mobile-filter-hint {
    display: none;
    position: fixed; bottom: 0; left: 0; right: 0;
    background: white; border-top: 1px solid var(--border);
    padding: 8px 16px 12px; z-index: 100;
    box-shadow: 0 -4px 20px rgba(0,0,0,.08);
}
.mobile-filter-hint .mfh-inner {
    display: flex; gap: 6px; overflow-x: auto; scrollbar-width: none;
}
.mobile-filter-hint .mfh-inner::-webkit-scrollbar { display: none; }

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */

/* Large tablets */
@media (max-width: 1080px) {
    .op-shell { grid-template-columns: 190px 1fr 190px; gap: 16px; }
}

/* Tablet — hide sidebars, full width */
@media (max-width: 860px) {
    .op-shell {
        grid-template-columns: 1fr;
        padding: 16px 14px 90px;
        gap: 0;
    }
    .aside-panel { display: none; } /* Hidden on mobile — show promo inline instead */
    .op-main { width: 100%; }

    /* Sticky top filter */
    .op-filters {
        position: sticky; top: 0; z-index: 50;
        border-radius: 0 0 10px 10px;
        margin: -16px -14px 16px;
        padding: 6px 10px;
        border-left: none; border-right: none; border-top: none;
        border-radius: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
    }

    .op-title-row { padding: 16px 0 4px; }
    .op-title-row h1 { font-size: 1.25rem; }
}

/* Mobile phones */
@media (max-width: 600px) {
    .op-shell { padding: 14px 12px 80px; }

    /* Card header stacks */
    .o-card-head { flex-direction: column; align-items: flex-start; gap: 10px; }
    .o-head-meta { width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 8px 0; }
    .o-meta-col  { border-right: none; padding-right: 0; margin-right: 0; }
    .o-meta-col:last-of-type { grid-column: 1 / -1; }
    .o-head-actions { width: 100%; display: flex; flex-wrap: wrap; gap: 7px; }
    .o-head-actions .btn-primary-sm,
    .o-head-actions .btn-ghost-sm,
    .o-head-actions .btn-cancel-sm { flex: 1; justify-content: center; min-width: 100px; }

    /* Tracking bar on mobile — hide label text, show compact */
    .trk-label-text { display: none; }
    .o-track-bar { padding: 10px 12px 8px; }

    /* Items */
    .o-items { padding: 0 12px; }
    .o-item  { gap: 10px; padding: 11px 0; }
    .o-item-img, .o-item-img-ph { width: 56px; height: 56px; }
    .o-item-name { font-size: .82rem; }

    /* Card footer stacks */
    .o-card-foot { flex-direction: column; align-items: flex-start; gap: 10px; padding: 10px 12px; }
    .o-foot-btns { width: 100%; }
    .o-foot-btns .st-pill { display: none; } /* status shown in head already */
    .o-foot-btns .btn-ghost-sm,
    .o-foot-btns .btn-cancel-sm { flex: 1; justify-content: center; }

    .o-card-head { padding: 10px 12px; }
    .o-addr-bar  { padding: 8px 12px; }

    /* Cancel modal */
    .cancel-overlay { padding: 12px; align-items: flex-end; }
    .cancel-modal { border-radius: 18px 18px 12px 12px; max-width: 100%; }
    .reason-grid { grid-template-columns: 1fr; }

    .op-title-row h1 { font-size: 1.15rem; }

    /* Mobile inline promo strip */
    .mobile-promo-strip {
        display: flex !important;
        background: linear-gradient(135deg, #ff4d6d, #ff8fab);
        border-radius: 12px; padding: 14px 16px;
        align-items: center; gap: 12px; margin-bottom: 14px;
        text-decoration: none;
    }
    .mps-text { flex: 1; }
    .mps-tag   { font-size: .56rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.8); }
    .mps-title { font-size: .88rem; font-weight: 900; color: white; margin: 2px 0; }
    .mps-sub   { font-size: .66rem; color: rgba(255,255,255,.75); }
    .mps-btn   {
        padding: 8px 14px; background: rgba(255,255,255,.92); border-radius: 8px;
        font-size: .7rem; font-weight: 800; color: var(--pink); flex-shrink: 0; white-space: nowrap;
    }
}

@media (min-width: 601px) {
    .mobile-promo-strip { display: none; }
}
</style>

{{-- Cancel Modal --}}
<div class="cancel-overlay" id="cancelOverlay">
    <div class="cancel-modal">
        <div class="cm-head">
            <div class="ci"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <h3>Cancel this order?</h3>
                <p>Order <span id="cancelOrderNum" style="font-weight:700;color:#1a1a1a;"></span></p>
            </div>
        </div>
        <div class="cm-body">
            <div class="cancel-warning">
                <i class="bi bi-info-circle-fill"></i>
                <span>If paid, a refund will be issued to your original payment method within <strong>5–7 business days</strong>.</span>
            </div>
            <label>Why are you cancelling?</label>
            <div class="reason-grid">
                <button class="reason-chip" data-reason="Changed my mind" onclick="selectReason(this)"><i class="bi bi-emoji-frown"></i> Changed my mind</button>
                <button class="reason-chip" data-reason="Wrong item ordered" onclick="selectReason(this)"><i class="bi bi-bag-x"></i> Wrong item</button>
                <button class="reason-chip" data-reason="Found a better price" onclick="selectReason(this)"><i class="bi bi-tag"></i> Better price</button>
                <button class="reason-chip" data-reason="Ordered by mistake" onclick="selectReason(this)"><i class="bi bi-arrow-counterclockwise"></i> Ordered by mistake</button>
                <button class="reason-chip" data-reason="Delivery too long" onclick="selectReason(this)"><i class="bi bi-clock"></i> Delivery too long</button>
                <button class="reason-chip" data-reason="Other" onclick="selectReason(this)"><i class="bi bi-three-dots"></i> Other reason</button>
            </div>
            <label style="margin-bottom:6px;">Additional note (optional)</label>
            <textarea class="cancel-note" id="cancelNote" rows="2" placeholder="Tell us more (optional)..."></textarea>
        </div>
        <div class="cm-foot">
            <button class="btn-keep" onclick="closeCancelModal()"><i class="bi bi-arrow-left"></i> Keep Order</button>
            <button class="btn-confirm-cancel" id="confirmCancelBtn" onclick="submitCancel()" disabled>
                <i class="bi bi-x-circle"></i> Yes, Cancel
            </button>
        </div>
    </div>
</div>

<div class="toast-msg" id="toastMsg"></div>

<div class="op-shell">

    {{-- LEFT SIDEBAR --}}
    <div class="aside-panel">
        <a href="{{ route('products.index') }}" class="aside-promo">
            <div class="ap-tag">🔥 This Week</div>
            <div class="ap-title">New Arrivals<br>are here</div>
            <div class="ap-sub">Fresh styles dropped. Be first.</div>
            <div class="ap-cta">Shop Now <i class="bi bi-arrow-right"></i></div>
        </a>
        <div class="aside-widget">
            <div class="aside-head">
                <div class="ah-ico t-amber">🏷️</div>
                <div class="ah-title">Active Offers</div>
                <span class="ah-badge badge-amber">Live</span>
            </div>
            <ul class="aside-list">
                <li><a href="{{ route('products.index') }}"><span class="al-dot dot-amber"></span><div class="al-info"><div class="al-label">Up to 50% Off</div><div class="al-sub">Selected collections</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
                <li><a href="{{ route('products.index') }}"><span class="al-dot dot-green"></span><div class="al-info"><div class="al-label">Free Delivery</div><div class="al-sub">Orders above ₹1,500</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
                <li><a href="{{ route('products.index') }}"><span class="al-dot dot-pink"></span><div class="al-info"><div class="al-label">Buy 2, Get 1</div><div class="al-sub">Mix &amp; match accessories</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
            </ul>
            <div class="aside-foot"><a href="{{ route('products.index') }}" class="foot-pink">View all offers <i class="bi bi-arrow-right"></i></a></div>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="op-main">

        {{-- Mobile promo strip (only on phones) --}}
        <a href="{{ route('products.index') }}" class="mobile-promo-strip">
            <div class="mps-text">
                <div class="mps-tag">🔥 This Week</div>
                <div class="mps-title">New Arrivals are here</div>
                <div class="mps-sub">Fresh styles dropped</div>
            </div>
            <div class="mps-btn">Shop Now →</div>
        </a>

        <div class="op-title-row">
            <h1>Your Orders</h1>
            <span>{{ $orders->total() }} order{{ $orders->total() !== 1 ? 's' : '' }}</span>
        </div>

        <div class="op-filters">
            @php
                $tabs = ['all' => 'All', 'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];
                $currentFilter = request('filter', 'all');
            @endphp
            @foreach($tabs as $key => $label)
            <button class="op-filter-btn {{ $currentFilter === $key ? 'active' : '' }}"
                    data-filter="{{ $key }}"
                    onclick="filterOrders('{{ $key }}', this)">
                {{ $label }}
                @if($key !== 'all' && isset($counts[$key]) && $counts[$key] > 0)
                    <span class="fp">{{ $counts[$key] }}</span>
                @endif
            </button>
            @endforeach
        </div>

        <div id="ordersContainer">
            @forelse($orders as $order)
            @php
                $stepMap = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3];
                $cur     = $stepMap[$order->status] ?? 0;
                $steps   = [
                    ['icon' => 'bi-bag-check-fill', 'label' => 'Placed'],
                    ['icon' => 'bi-gear-fill',       'label' => 'Processing'],
                    ['icon' => 'bi-truck',           'label' => 'Shipped'],
                    ['icon' => 'bi-house-check-fill','label' => 'Delivered'],
                ];
                $canCancel = in_array($order->status, ['pending', 'processing']);
            @endphp

            <div class="o-card" data-status="{{ $order->status }}">

                {{-- Head --}}
                <div class="o-card-head">
                    <div class="o-head-meta">
                        <div class="o-meta-col">
                            <div class="o-meta-lbl">Order Placed</div>
                            <div class="o-meta-val">{{ $order->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="o-meta-col">
                            <div class="o-meta-lbl">Total</div>
                            <div class="o-meta-val">₹{{ number_format($order->total_amount) }}</div>
                        </div>
                        <div class="o-meta-col">
                            <div class="o-meta-lbl">Payment</div>
                            <div class="o-meta-val">
                                <span class="st-pill st-{{ $order->payment_status === 'paid' ? 'paid' : ($order->payment_status === 'failed' ? 'failed' : 'unpaid') }}">
                                    <i class="bi {{ $order->payment_status === 'paid' ? 'bi-shield-check' : 'bi-clock' }}"></i>
                                    {{ ucfirst($order->payment_status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                        <div class="o-meta-col">
                            <div class="o-meta-lbl">Order #</div>
                            <div class="o-meta-val pink">{{ $order->order_number }}</div>
                        </div>
                    </div>
                    <div class="o-head-actions">
                        <a href="{{ route('account.order.detail', $order->id) }}" class="btn-primary-sm">
                            <i class="bi bi-eye"></i> View Order
                        </a>
                        @if($order->status === 'delivered')
                        <button class="btn-ghost-sm" onclick="reorder({{ $order->id }})">
                            <i class="bi bi-arrow-clockwise"></i> Reorder
                        </button>
                        @endif
                        @if($canCancel)
                        <button class="btn-cancel-sm"
                            onclick="openCancelModal({{ $order->id }}, '{{ $order->order_number }}', '{{ $order->status }}', this)">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Tracking --}}
                @if($order->status !== 'cancelled')
                <div class="o-track-bar">
                    <div class="trk-label-text">
                        @if($order->status === 'pending')        <i class="bi bi-hourglass-split me-1"></i>Awaiting
                        @elseif($order->status === 'processing') <i class="bi bi-gear-fill me-1"></i>Packing
                        @elseif($order->status === 'shipped')    <i class="bi bi-truck me-1"></i>On the way
                        @elseif($order->status === 'delivered')  <i class="bi bi-check-circle-fill me-1"></i>Delivered
                        @endif
                    </div>
                    <div class="trk-steps">
                        @foreach($steps as $i => $step)
                        @php $cls = $i < $cur ? 'done' : ($i === $cur ? 'current' : 'waiting'); @endphp
                        <div class="trk-node {{ $cls }}">
                            <div class="trk-dot">
                                @if($i < $cur) <i class="bi bi-check-lg"></i>
                                @else          <i class="bi {{ $step['icon'] }}"></i>
                                @endif
                            </div>
                            <div class="trk-lbl">{{ $step['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="cancelled-notice">
                    <i class="bi bi-x-circle-fill"></i>
                    Order cancelled &nbsp;·&nbsp; Refund will be processed within 5–7 business days if charged.
                </div>
                @endif

                {{-- Items --}}
                <div class="o-items">
                    @forelse($order->items as $item)
                    <div class="o-item">
                        @if($item->product_image)
                            <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="o-item-img"
                                 onerror="this.outerHTML='<div class=\'o-item-img-ph\'><i class=\'bi bi-box-seam\'></i></div>'">
                        @else
                            <div class="o-item-img-ph"><i class="bi bi-box-seam"></i></div>
                        @endif
                        <div class="o-item-info">
                            <div class="o-item-name">{{ $item->product_name }}</div>
                            @if($item->product_sku)
                            <div class="o-item-sku">SKU: {{ $item->product_sku }}</div>
                            @endif
                            <div class="o-item-meta">Qty: {{ $item->quantity }} &nbsp;·&nbsp; ₹{{ number_format($item->unit_price) }} each</div>
                        </div>
                        <div>
                            <div class="o-item-price">₹{{ number_format($item->subtotal) }}</div>
                            <div class="o-item-sub">subtotal</div>
                        </div>
                    </div>
                    @empty
                    <div style="padding:14px 0;font-size:.8rem;color:var(--muted);text-align:center;">No item details available.</div>
                    @endforelse
                </div>

                @if($order->shipping_address)
                <div class="o-addr-bar">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Ship to: @if(is_array($order->shipping_address)){{ implode(', ', array_filter($order->shipping_address)) }}@else{{ $order->shipping_address }}@endif</span>
                </div>
                @endif

                {{-- Footer --}}
                <div class="o-card-foot">
                    <div>
                        <div class="o-foot-total-lbl">Order Total ({{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }})</div>
                        <div class="o-foot-total-val">₹{{ number_format($order->total_amount) }}</div>
                    </div>
                    <div class="o-foot-btns">
                        <span class="st-pill st-{{ $order->status }}">
                            @if($order->status==='pending')        <i class="bi bi-hourglass-split"></i>
                            @elseif($order->status==='processing') <i class="bi bi-gear-fill"></i>
                            @elseif($order->status==='shipped')    <i class="bi bi-truck"></i>
                            @elseif($order->status==='delivered')  <i class="bi bi-check-circle-fill"></i>
                            @elseif($order->status==='cancelled')  <i class="bi bi-x-circle-fill"></i>
                            @endif
                            {{ ucfirst($order->status) }}
                        </span>
                        <a href="{{ route('account.order.detail', $order->id) }}" class="btn-ghost-sm">
                            <i class="bi bi-file-text"></i> Details
                        </a>
                        @if($canCancel)
                        <button class="btn-cancel-sm"
                            onclick="openCancelModal({{ $order->id }}, '{{ $order->order_number }}', '{{ $order->status }}', this)">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        @endif
                    </div>
                </div>

            </div>
            @empty
            <div class="op-empty" id="emptyState">
                <span class="ei">🛍️</span>
                <h4>No orders yet</h4>
                <p>You haven't placed any orders.<br>Explore our collection and find something you love!</p>
                <a href="{{ route('products.index') }}"
                   style="display:inline-flex;align-items:center;gap:6px;padding:9px 22px;border-radius:8px;background:linear-gradient(135deg,var(--pink),var(--pink-d));color:white;font-weight:700;text-decoration:none;font-size:.82rem;box-shadow:0 4px 14px rgba(255,77,109,.26);">
                    Shop Now <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @endforelse
        </div>

        @if($orders->hasPages())
        <div style="display:flex;justify-content:center;margin-top:12px;">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    {{-- RIGHT SIDEBAR --}}
    <div class="aside-panel">
        <div class="aside-widget">
            <div class="aside-head">
                <div class="ah-ico t-purple">⭐</div>
                <div class="ah-title">Top Categories</div>
            </div>
            <ul class="aside-list">
                <li><a href="{{ route('products.index') }}"><span class="al-dot dot-pink"></span><div class="al-info"><div class="al-label">Best Sellers</div><div class="al-sub">What's trending now</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
                <li><a href="{{ route('products.index') }}"><span class="al-dot dot-purple"></span><div class="al-info"><div class="al-label">Members Picks</div><div class="al-sub">Exclusive to loyal shoppers</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
                <li><a href="{{ route('products.index') }}"><span class="al-dot dot-blue"></span><div class="al-info"><div class="al-label">Gift Sets</div><div class="al-sub">Free wrapping on every order</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
                <li><a href="{{ route('products.index') }}"><span class="al-dot dot-slate"></span><div class="al-info"><div class="al-label">New Drops</div><div class="al-sub">Added this week</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
            </ul>
            <div class="aside-foot"><a href="{{ route('products.index') }}" class="foot-pink">Browse all <i class="bi bi-arrow-right"></i></a></div>
        </div>
        <div class="aside-widget">
            <div class="aside-head">
                <div class="ah-ico t-green">💬</div>
                <div class="ah-title">Need Help?</div>
            </div>
            <ul class="aside-list">
                <li><a href="#"><span class="al-dot dot-green"></span><div class="al-info"><div class="al-label">Track My Order</div><div class="al-sub">Real-time updates</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
                <li><a href="#"><span class="al-dot dot-amber"></span><div class="al-info"><div class="al-label">Returns &amp; Refunds</div><div class="al-sub">Easy 7-day returns</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
                <li><a href="#"><span class="al-dot dot-blue"></span><div class="al-info"><div class="al-label">Contact Support</div><div class="al-sub">Mon–Sat, 10am–6pm</div></div><i class="bi bi-chevron-right al-arr"></i></a></li>
            </ul>
        </div>
    </div>

</div>

<script>
function filterOrders(filter, btn) {
    document.querySelectorAll('.op-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const cards = document.querySelectorAll('.o-card');
    let visible = 0;
    cards.forEach(card => {
        const show = filter === 'all' || card.dataset.status === filter;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    let emptyEl = document.getElementById('emptyState');
    if (!emptyEl && visible === 0) {
        emptyEl = document.createElement('div');
        emptyEl.id = 'emptyState';
        emptyEl.className = 'op-empty';
        emptyEl.innerHTML = `<span class="ei">📦</span><h4>No ${filter} orders</h4><p>No orders found for this status.</p>`;
        document.getElementById('ordersContainer').appendChild(emptyEl);
    } else if (emptyEl) {
        emptyEl.style.display = visible === 0 ? '' : 'none';
    }
}

function reorder(id) { alert('Reorder coming soon!'); }

let cancelOrderId = null, cancelOrderNum = '', cancelCardEl = null, selectedReason = '';

function openCancelModal(orderId, orderNum, status, btn) {
    cancelOrderId = orderId; cancelOrderNum = orderNum;
    cancelCardEl = btn.closest('.o-card'); selectedReason = '';
    document.getElementById('cancelOrderNum').textContent = '#' + orderNum;
    document.getElementById('cancelNote').value = '';
    document.querySelectorAll('.reason-chip').forEach(c => c.classList.remove('selected'));
    document.getElementById('confirmCancelBtn').disabled = true;
    document.getElementById('cancelOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeCancelModal() {
    document.getElementById('cancelOverlay').classList.remove('show');
    document.body.style.overflow = '';
}
function selectReason(chip) {
    document.querySelectorAll('.reason-chip').forEach(c => c.classList.remove('selected'));
    chip.classList.add('selected');
    selectedReason = chip.dataset.reason;
    document.getElementById('confirmCancelBtn').disabled = false;
}
document.getElementById('cancelOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});

async function submitCancel() {
    const btn  = document.getElementById('confirmCancelBtn');
    const note = document.getElementById('cancelNote').value.trim();
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Cancelling…';
    try {
        const resp = await fetch(`/account/orders/${cancelOrderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reason: selectedReason, note })
        });
        const data = await resp.json();
        if (resp.ok && data.success) {
            closeCancelModal();
            if (cancelCardEl) {
                cancelCardEl.dataset.status = 'cancelled';
                const trackBar = cancelCardEl.querySelector('.o-track-bar');
                if (trackBar) {
                    const notice = document.createElement('div');
                    notice.className = 'cancelled-notice';
                    notice.innerHTML = `<i class="bi bi-x-circle-fill"></i> Order cancelled &nbsp;·&nbsp; Refund will be processed within 5–7 business days.`;
                    trackBar.replaceWith(notice);
                }
                const pill = cancelCardEl.querySelector('.o-foot-btns .st-pill');
                if (pill) { pill.className = 'st-pill st-cancelled'; pill.innerHTML = `<i class="bi bi-x-circle-fill"></i> Cancelled`; }
                cancelCardEl.querySelectorAll('.btn-cancel-sm').forEach(b => b.remove());
            }
            showToast('✓ Order #' + cancelOrderNum + ' cancelled.');
        } else {
            alert(data.message || 'Could not cancel. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-x-circle"></i> Yes, Cancel';
        }
    } catch(err) {
        alert('Something went wrong. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-x-circle"></i> Yes, Cancel';
    }
}

function showToast(msg) {
    const t = document.getElementById('toastMsg');
    t.textContent = msg; t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 3500);
}
</script>
@endsection
