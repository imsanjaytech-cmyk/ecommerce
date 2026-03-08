(function () {
    'use strict';

    const state = {
        page:           1,
        perPage:        15,
        search:         window.ORDERS.initialSearch || '',
        status:         window.ORDERS.initialStatus || 'all',
        debounceTimer:  null,
        statusTargetId: null,
        statusOrderNum: '',
        deleteTargetId: null,
        deleteOrderNum: '',
    };

    const $ = id => document.getElementById(id);

    const ordersBody      = $('ordersBody');
    const ordersSubtitle  = $('ordersSubtitle');
    const paginationBar   = $('paginationBar');
    const paginationInfo  = $('paginationInfo');
    const paginationBtns  = $('paginationBtns');
    const orderSearch     = $('orderSearch');
    const perPageSelect   = $('perPageSelect');

    const statusModal     = new bootstrap.Modal($('statusModal'));
    const deleteModal     = new bootstrap.Modal($('deleteOrderModal'));

    const statusOrderNum  = $('statusOrderNum');
    const statusSelect    = $('statusSelect');
    const saveStatusBtn   = $('saveStatusBtn');
    const saveStatusText  = $('saveStatusText');
    const saveStatusSpinner = $('saveStatusSpinner');

    const deleteOrderNum      = $('deleteOrderNum');
    const confirmDeleteOrderBtn = $('confirmDeleteOrderBtn');
    const deleteOrderSpinner  = $('deleteOrderSpinner');

    function esc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function toast(message, type = 'success') {
        let container = $('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.cssText = 'position:fixed;bottom:20px;right:20px;display:flex;flex-direction:column;gap:8px;z-index:9999;pointer-events:none;';
            document.body.appendChild(container);
        }
        const colors = {
            success: { bg:'#e8f8ee', border:'#1f9c4a', text:'#1f9c4a', icon:'bi-check-circle-fill' },
            error:   { bg:'#fee8eb', border:'#dc3545', text:'#dc3545', icon:'bi-x-circle-fill' },
            info:    { bg:'#e8f4ff', border:'#1a7cd4', text:'#1a7cd4', icon:'bi-info-circle-fill' },
        };
        const c = colors[type] || colors.info;
        const t = document.createElement('div');
        t.style.cssText = `background:${c.bg};border:1.5px solid ${c.border};color:${c.text};
            padding:11px 16px;border-radius:11px;font-size:13px;font-weight:600;
            display:flex;align-items:center;gap:9px;
            box-shadow:0 4px 20px rgba(0,0,0,0.1);pointer-events:all;
            animation:slideUp .25s ease;min-width:240px;max-width:340px;`;
        t.innerHTML = `<i class="bi ${c.icon}" style="font-size:16px;flex-shrink:0;"></i><span>${message}</span>`;
        container.appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0'; t.style.transition = 'opacity .3s';
            setTimeout(() => t.remove(), 300);
        }, 3200);
    }

    if (!document.getElementById('orders-anim-style')) {
        const s = document.createElement('style');
        s.id = 'orders-anim-style';
        s.textContent = `@keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}`;
        document.head.appendChild(s);
    }

    function statusBadge(status) {
        const map = {
            delivered:  ['bdg-success', 'Delivered'],
            processing: ['bdg-info',    'Processing'],
            shipped:    ['bdg-warning', 'Shipped'],
            cancelled:  ['bdg-danger',  'Cancelled'],
            pending:    ['bdg-gray',    'Pending'],
        };
        const [cls, label] = map[status] || ['bdg-gray', status];
        return `<span class="bdg ${cls}">${label}</span>`;
    }

    function loadOrders() {
        ordersBody.innerHTML = `
            <tr><td colspan="7" style="text-align:center;padding:50px;color:var(--text-muted);">
                <div class="spinner-border spinner-border-sm me-2" style="color:var(--primary);"></div>
                Loading orders...
            </td></tr>`;
        paginationBar.style.display = 'none';

        const params = new URLSearchParams({
            page:     state.page,
            per_page: state.perPage,
        });
        if (state.search) params.set('search', state.search);
        if (state.status !== 'all') params.set('status', state.status);

        fetch(`${window.ORDERS.routes.list}?${params}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.ORDERS.csrf }
        })
        .then(r => {
            console.log('Response status:', r.status, 'URL:', r.url);
            if (!r.ok) throw new Error(`HTTP ${r.status} - ${r.statusText}`);
            return r.json();
        })        
        .then(res => {
            if (res.orders) {
                renderTable(res.orders.data, res.orders);
                refreshCounts(res.counts, res.total_revenue);
            } else {
                throw new Error('Unexpected response format.');
            }
        })
        .catch((err) => {
            console.error('Orders fetch error:', err);
            ordersBody.innerHTML = `
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--danger);">
                    <i class="bi bi-exclamation-triangle me-2"></i>Failed to load orders. Please refresh.
                </td></tr>`;
        });
    }

    function renderTable(orders, meta) {
        if (!orders.length) {
            ordersBody.innerHTML = `
                <tr><td colspan="7" style="text-align:center;padding:60px;color:var(--text-muted);">
                    <i class="bi bi-bag" style="font-size:36px;opacity:.25;display:block;margin-bottom:10px;"></i>
                    No orders found${state.search ? ` for "<strong>${esc(state.search)}</strong>"` : ''}
                </td></tr>`;
            ordersSubtitle.textContent = '0 orders found';
            return;
        }

        ordersSubtitle.textContent = `Showing ${meta.from}–${meta.to} of ${meta.total} orders`;

        ordersBody.innerHTML = orders.map(o => `
            <tr data-id="${o.id}">
                <td style="color:var(--primary);font-weight:700;font-size:13px;">
                    <a href="${window.ORDERS.routes.show(o.id)}" style="color:inherit;text-decoration:none;">
                        #${esc(o.order_number)}
                    </a>
                </td>
                <td>
                    <div style="font-weight:600;font-size:13px;">${esc(o.customer_name || 'Guest')}</div>
                    <div style="font-size:11px;color:var(--text-muted);">${esc(o.customer_email || '')}</div>
                </td>
                <td class="col-items" style="font-weight:600;">${o.items_count ?? 0}</td>
                <td style="font-weight:700;">₹${Number(o.total_amount).toLocaleString('en-IN')}</td>
                <td class="col-date" style="font-size:12.5px;color:var(--text-muted);">${o.created_at_formatted ?? ''}</td>
                <td>${statusBadge(o.status)}</td>
                <td>
                    <div class="act-row" style="opacity:1;">
                        <a href="${window.ORDERS.routes.show(o.id)}" class="act-btn" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <div class="act-btn status-btn"
                             data-id="${o.id}"
                             data-num="${esc(o.order_number)}"
                             data-status="${o.status}"
                             title="Update Status">
                            <i class="bi bi-pencil"></i>
                        </div>
                        <div class="act-btn del delete-btn"
                             data-id="${o.id}"
                             data-num="${esc(o.order_number)}"
                             title="Delete">
                            <i class="bi bi-trash"></i>
                        </div>
                    </div>
                </td>
            </tr>
        `).join('');

        renderPagination(meta);
        bindRowEvents();
    }

    // ─── Pagination ────────────────────────────────────────────────────────────

    function renderPagination(meta) {
        if (meta.last_page <= 1) {
            paginationBar.style.display = 'none';
            return;
        }
        paginationBar.style.display = 'flex';
        paginationInfo.textContent = `Page ${meta.current_page} of ${meta.last_page} (${meta.total} total)`;

        const pages = buildPageRange(meta.current_page, meta.last_page);
        let html = '';

        html += `<div class="pgn-btn" id="pgn-prev" ${meta.current_page===1 ? 'style="opacity:.4;pointer-events:none;"' : ''}>
                    <i class="bi bi-chevron-left" style="font-size:10px;"></i></div>`;

        pages.forEach(p => {
            if (p === '...') {
                html += `<div class="pgn-btn" style="pointer-events:none;">…</div>`;
            } else {
                html += `<div class="pgn-btn ${p === meta.current_page ? 'active' : ''}" data-page="${p}">${p}</div>`;
            }
        });

        html += `<div class="pgn-btn" id="pgn-next" ${meta.current_page===meta.last_page ? 'style="opacity:.4;pointer-events:none;"' : ''}>
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i></div>`;

        paginationBtns.innerHTML = html;

        paginationBtns.querySelectorAll('.pgn-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', () => { state.page = parseInt(btn.dataset.page); loadOrders(); });
        });
        const prev = document.getElementById('pgn-prev');
        const next = document.getElementById('pgn-next');
        if (prev) prev.addEventListener('click', () => { if (state.page > 1) { state.page--; loadOrders(); } });
        if (next) next.addEventListener('click', () => { if (state.page < meta.last_page) { state.page++; loadOrders(); } });
    }

    function buildPageRange(current, last) {
        if (last <= 7) return Array.from({length: last}, (_, i) => i + 1);
        const pages = [1];
        if (current > 3) pages.push('...');
        for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) pages.push(i);
        if (current < last - 2) pages.push('...');
        pages.push(last);
        return pages;
    }

    // ─── Counts refresh ────────────────────────────────────────────────────────

    function refreshCounts(counts, totalRevenue) {
        if (!counts) return;

        const total = Object.values(counts).reduce((a, b) => a + b, 0);
        const statTotal = $('stat-total');
        if (statTotal) statTotal.textContent = total.toLocaleString('en-IN');

        const statPending = $('stat-pending');
        if (statPending) statPending.textContent = (counts.pending || 0).toLocaleString('en-IN');

        const statDelivered = $('stat-delivered');
        if (statDelivered) statDelivered.textContent = (counts.delivered || 0).toLocaleString('en-IN');

        const statRevenue = $('stat-revenue');
        if (statRevenue && totalRevenue !== undefined) {
            statRevenue.textContent = '₹' + (totalRevenue / 1000).toFixed(1) + 'K';
        }

        // Update tab counts
        document.querySelectorAll('.tab-count').forEach(el => {
            const s = el.dataset.status;
            if (counts[s] !== undefined) el.textContent = `(${counts[s].toLocaleString('en-IN')})`;
        });
    }

    // ─── Row events ────────────────────────────────────────────────────────────

    function bindRowEvents() {
        ordersBody.querySelectorAll('.status-btn').forEach(btn => {
            btn.addEventListener('click', () => openStatusModal(
                parseInt(btn.dataset.id), btn.dataset.num, btn.dataset.status
            ));
        });
        ordersBody.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => openDeleteModal(
                parseInt(btn.dataset.id), btn.dataset.num
            ));
        });
    }

    // ─── Status modal ──────────────────────────────────────────────────────────

    function openStatusModal(id, num, currentStatus) {
        state.statusTargetId = id;
        state.statusOrderNum = num;
        statusOrderNum.textContent = `#${num}`;
        statusSelect.value = currentStatus;
        statusModal.show();
    }

    function saveStatus() {
        if (!state.statusTargetId) return;
        const newStatus = statusSelect.value;

        saveStatusBtn.disabled          = true;
        saveStatusText.style.display    = 'none';
        saveStatusSpinner.style.display = 'inline-block';

        fetch(window.ORDERS.routes.status(state.statusTargetId), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.ORDERS.csrf,
            },
            body: JSON.stringify({ status: newStatus }),
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            statusModal.hide();
            toast(res.message, 'success');
            loadOrders();
        })
        .catch(err => toast(err.message || 'Failed to update status.', 'error'))
        .finally(() => {
            saveStatusBtn.disabled          = false;
            saveStatusText.style.display    = 'inline';
            saveStatusSpinner.style.display = 'none';
            state.statusTargetId = null;
        });
    }

    function openDeleteModal(id, num) {
        state.deleteTargetId = id;
        state.deleteOrderNum = num;
        deleteOrderNum.textContent = `#${num}`;
        deleteModal.show();
    }

    function confirmDelete() {
        if (!state.deleteTargetId) return;
        confirmDeleteOrderBtn.disabled  = true;
        deleteOrderSpinner.style.display = 'inline-block';

        fetch(window.ORDERS.routes.destroy(state.deleteTargetId), {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.ORDERS.csrf }
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            deleteModal.hide();
            toast(res.message, 'success');
            if (state.page > 1) state.page--;
            loadOrders();
        })
        .catch(err => toast(err.message || 'Failed to delete order.', 'error'))
        .finally(() => {
            confirmDeleteOrderBtn.disabled  = false;
            deleteOrderSpinner.style.display = 'none';
            state.deleteTargetId = null;
        });
    }

    // ─── Tab switching ─────────────────────────────────────────────────────────

    function bindTabs() {
        document.querySelectorAll('.order-tab').forEach(tab => {
            tab.addEventListener('click', e => {
                e.preventDefault();

                document.querySelectorAll('.order-tab').forEach(t => {
                    t.style.background        = '';
                    t.style.borderColor       = 'transparent';
                    t.style.borderBottomColor = 'transparent';
                    t.style.color             = 'var(--text-muted)';
                    t.classList.remove('active-tab');
                });

                tab.style.background        = 'white';
                tab.style.borderColor       = 'var(--border-col)';
                tab.style.borderBottomColor = 'white';
                tab.style.color             = 'var(--primary)';
                tab.classList.add('active-tab');

                state.status = tab.dataset.status;
                state.page   = 1;
                loadOrders();
            });
        });
    }

    // ─── Events ────────────────────────────────────────────────────────────────

    function bindEvents() {
        saveStatusBtn.addEventListener('click', saveStatus);
        confirmDeleteOrderBtn.addEventListener('click', confirmDelete);

        $('statusModal').addEventListener('hidden.bs.modal', () => {
            state.statusTargetId = null;
        });

        orderSearch.addEventListener('input', function () {
            clearTimeout(state.debounceTimer);
            state.debounceTimer = setTimeout(() => {
                state.search = this.value.trim();
                state.page   = 1;
                loadOrders();
            }, 380);
        });

        perPageSelect.addEventListener('change', function () {
            state.perPage = parseInt(this.value);
            state.page    = 1;
            loadOrders();
        });

        bindTabs();
    }

    function init() {
        bindEvents();
        loadOrders();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
