(function () {
    'use strict';
    const state = {
        page:          1,
        perPage:       10,
        search:        '',
        filter:        'all',
        categoryId:    '',
        sortBy:        'created_at',
        sortDir:       'desc',
        selectedIds:   new Set(),
        deleteTargetId:null,
        deleteTargetName:'',
        editingId:     null,
        debounceTimer: null,
    };

    const $ = id => document.getElementById(id);
    const $$ = sel => document.querySelectorAll(sel);

    const productsBody   = $('productsBody');
    const tableSubtitle  = $('tableSubtitle');
    const paginationBar  = $('paginationBar');
    const paginationInfo = $('paginationInfo');
    const paginationBtns = $('paginationBtns');
    const selectAllCb    = $('selectAll');
    const bulkDeleteBtn  = $('bulkDeleteBtn');
    const selectedCount  = $('selectedCount');
    const searchInput    = $('searchInput');
    const categoryFilter = $('categoryFilter');
    const perPageSelect  = $('perPageSelect');

    const productModal   = new bootstrap.Modal($('productModal'));
    const deleteModal    = new bootstrap.Modal($('deleteModal'));

    const productForm    = $('productForm');
    const formErrors     = $('formErrors');
    const productId      = $('productId');
    const modalTitle     = $('modalTitle');
    const saveProductBtn = $('saveProductBtn');
    const saveBtnText    = $('saveBtnText');
    const saveBtnSpinner = $('saveBtnSpinner');
    const saveDraftBtn   = $('saveDraftBtn');

    const confirmDeleteBtn  = $('confirmDeleteBtn');
    const deleteSpinner     = $('deleteSpinner');
    const deleteProductName = $('deleteProductName');

    const thumbnailZone    = $('thumbnailZone');
    const thumbPreview     = $('thumbPreview');
    const thumbImg         = $('thumbImg');
    const thumbPlaceholder = $('thumbPlaceholder');
    const fThumbnail       = $('f_thumbnail');
    const fImages          = $('f_images');
    const imagesPreview    = $('imagesPreview');
    const existingImages   = $('existingImages');

    // ─── Helpers ───────────────────────────────────────────────────────────────

    /** Strip commas from formatted price strings like "1,200.00" → 1200 */
    function parsePrice(val) {
        if (val === null || val === undefined || val === '') return '';
        return parseFloat(String(val).replace(/,/g, '')) || '';
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;');
    }

    function toast(message, type = 'success') {
        const container = $('toastContainer');
        const colors = {
            success: { bg: '#e8f8ee', border: '#1f9c4a', text: '#1f9c4a', icon: 'bi-check-circle-fill' },
            error:   { bg: '#fee8eb', border: '#dc3545', text: '#dc3545', icon: 'bi-x-circle-fill' },
            info:    { bg: '#e8f4ff', border: '#1a7cd4', text: '#1a7cd4', icon: 'bi-info-circle-fill' },
        };
        const c = colors[type] || colors.info;
        const t = document.createElement('div');
        t.style.cssText = `
            background:${c.bg};border:1.5px solid ${c.border};color:${c.text};
            padding:11px 16px;border-radius:11px;font-size:13px;font-weight:600;
            display:flex;align-items:center;gap:9px;
            box-shadow:0 4px 20px rgba(0,0,0,0.1);pointer-events:all;
            animation:slideUp .25s ease;min-width:240px;max-width:340px;
        `;
        t.innerHTML = `<i class="bi ${c.icon}" style="font-size:16px;flex-shrink:0;"></i><span>${message}</span>`;
        container.appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transition = 'opacity .3s';
            setTimeout(() => t.remove(), 300);
        }, 3200);
    }

    const style = document.createElement('style');
    style.textContent = `@keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}`;
    document.head.appendChild(style);

    function stockBadge(p) {
        const map = {
            in_stock:     ['bdg-success','In Stock'],
            low_stock:    ['bdg-warning','Low Stock'],
            out_of_stock: ['bdg-danger', 'Out of Stock'],
        };
        const [cls, label] = map[p.stock_status] || ['bdg-gray','Unknown'];
        return `<span class="bdg ${cls}">${label}</span>`;
    }

    function statusBadge(p) {
        const map = {
            published: ['bdg-success','Published'],
            draft:     ['bdg-gray',   'Draft'],
            scheduled: ['bdg-info',   'Scheduled'],
        };
        const [cls, label] = map[p.status] || ['bdg-gray','Unknown'];
        return `<span class="bdg ${cls}">${label}</span>`;
    }

    function stockColor(p) {
        if (p.stock_status === 'out_of_stock') return 'var(--danger)';
        if (p.stock_status === 'low_stock')    return '#d97706';
        return '#1f9c4a';
    }

    // ─── Load categories dynamically ───────────────────────────────────────────

    function loadCategories() {
        fetch(window.PROD.routes.categories, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.PROD.csrf }
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const cats = res.data; // [{id, name}, ...]

            // Populate toolbar filter dropdown
            if (categoryFilter) {
                const existing = [...categoryFilter.options].map(o => o.value);
                cats.forEach(cat => {
                    if (!existing.includes(String(cat.id))) {
                        const opt = document.createElement('option');
                        opt.value = cat.id;
                        opt.textContent = cat.name;
                        categoryFilter.appendChild(opt);
                    }
                });
            }

            // Populate modal category select
            const modalCatSelect = $('f_category_id');
            if (modalCatSelect) {
                // Keep the first placeholder option
                const placeholder = modalCatSelect.options[0];
                modalCatSelect.innerHTML = '';
                modalCatSelect.appendChild(placeholder);
                cats.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.id;
                    opt.textContent = cat.name;
                    modalCatSelect.appendChild(opt);
                });
            }
        })
        .catch(() => console.warn('Could not load categories.'));
    }

    // ─── Products table ────────────────────────────────────────────────────────

    function loadProducts() {
        productsBody.innerHTML = `
            <tr><td colspan="10" style="text-align:center;padding:50px;color:var(--text-muted);">
                <div class="spinner-border spinner-border-sm me-2" style="color:var(--primary);"></div>
                Loading products...
            </td></tr>`;
        paginationBar.style.display = 'none';

        const params = new URLSearchParams({
            page:     state.page,
            per_page: state.perPage,
        });
        if (state.search)     params.set('search', state.search);
        if (state.categoryId) params.set('category_id', state.categoryId);

        if (state.filter !== 'all') {
            if (['in_stock','low_stock','out_of_stock'].includes(state.filter)) {
                params.set('stock_status', state.filter);
            } else {
                params.set('status', state.filter);
            }
        }

        fetch(`${window.PROD.routes.list}?${params}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.PROD.csrf }
        })
        .then(r => r.json())
        .then(res => renderTable(res.data, res.meta))
        .catch(() => {
            productsBody.innerHTML = `
                <tr><td colspan="10" style="text-align:center;padding:40px;color:var(--danger);">
                    <i class="bi bi-exclamation-triangle me-2"></i>Failed to load products. Please refresh.
                </td></tr>`;
        });
    }

    function renderTable(products, meta) {
        state.selectedIds.clear();
        updateBulkBar();

        if (!products.length) {
            productsBody.innerHTML = `
                <tr><td colspan="10" style="text-align:center;padding:60px;color:var(--text-muted);">
                    <i class="bi bi-box-seam" style="font-size:36px;opacity:.3;display:block;margin-bottom:12px;"></i>
                    No products found. <a href="#" id="clearFiltersLink" style="color:var(--primary);">Clear filters</a>
                </td></tr>`;
            tableSubtitle.textContent = '0 products found';
            const cl = document.getElementById('clearFiltersLink');
            if (cl) cl.addEventListener('click', e => { e.preventDefault(); clearFilters(); });
            return;
        }

        tableSubtitle.textContent = `Showing ${meta.from}–${meta.to} of ${meta.total} products`;

        productsBody.innerHTML = products.map(p => `
            <tr data-id="${p.id}">
                <td class="col-check">
                    <input type="checkbox" class="row-cb" data-id="${p.id}"
                           style="accent-color:var(--primary);cursor:pointer;"
                           ${state.selectedIds.has(p.id) ? 'checked' : ''}>
                </td>
                <td class="col-product">
                    <div style="display:flex;align-items:center;gap:11px;">
                        <div style="width:42px;height:42px;border-radius:10px;overflow:hidden;border:1.5px solid var(--border-col);flex-shrink:0;background:var(--pink-soft);">
                            ${p.thumbnail_url
                                ? `<img src="${p.thumbnail_url}" alt="${escHtml(p.name)}"
                                    style="width:100%;height:100%;object-fit:cover;"
                                    onerror='this.parentElement.innerHTML="<div style=&quot;width:100%;height:100%;display:flex;align-items:center;justify-content:center;&quot;><i class=&quot;bi bi-image&quot; style=&quot;color:var(--secondary);font-size:16px;&quot;></i></div>"'>`
                                : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-box-seam" style="color:var(--secondary);font-size:16px;"></i>
                                </div>`
                            }
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:13.5px;color:var(--dark);line-height:1.3;">${escHtml(p.name)}</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">${p.created_at}</div>
                        </div>
                    </div>
                </td>
                <td class="col-sku" style="font-size:12px;color:var(--text-muted);font-family:monospace;letter-spacing:.4px;">${escHtml(p.sku)}</td>
                <td class="col-cat"><span class="bdg bdg-info">${escHtml(p.category)}</span></td>
                <td class="col-price">
                    <div style="font-weight:700;color:var(--primary);font-size:14px;">₹${p.active_price}</div>
                    ${p.sale_price ? `<div style="font-size:11px;color:var(--text-muted);text-decoration:line-through;">₹${p.regular_price}</div>` : ''}
                </td>
                <td class="col-stock" style="font-weight:700;color:${stockColor(p)};">
                    ${p.stock_status === 'out_of_stock' ? '—' : p.stock_quantity}
                </td>
                <td class="col-status">
                    ${stockBadge(p)}
                    <div style="margin-top:4px;">${statusBadge(p)}</div>
                </td>
                <td class="col-sales" style="font-weight:600;">${p.total_sales}</td>
                <td class="col-feat">
                    <button class="feat-btn ${p.is_featured ? 'on' : ''}"
                            data-id="${p.id}" title="Toggle featured">
                        <i class="bi ${p.is_featured ? 'bi-star-fill' : 'bi-star'}"></i>
                    </button>
                </td>
                <td class="col-actions">
                    <div class="act-row">
                        <div class="act-btn edit-btn" data-id="${p.id}" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </div>
                        <div class="act-btn del delete-btn" data-id="${p.id}" data-name="${escHtml(p.name)}" title="Delete">
                            <i class="bi bi-trash"></i>
                        </div>
                    </div>
                </td>
            </tr>
        `).join('');

        renderPagination(meta);
        bindRowEvents();
    }

    function renderPagination(meta) {
        if (meta.last_page <= 1) {
            paginationBar.style.display = 'none';
            return;
        }
        paginationBar.style.display = 'flex';
        paginationInfo.textContent = `Page ${meta.current_page} of ${meta.last_page} (${meta.total} total)`;

        let html = '';
        html += `<div class="pgn-btn" id="pgn-prev" ${meta.current_page===1?'style="opacity:.4;pointer-events:none;"':''}>
                    <i class="bi bi-chevron-left" style="font-size:10px;"></i></div>`;

        const pages = buildPageRange(meta.current_page, meta.last_page);
        pages.forEach(p => {
            if (p === '...') {
                html += `<div class="pgn-btn" style="pointer-events:none;">...</div>`;
            } else {
                html += `<div class="pgn-btn ${p === meta.current_page ? 'active' : ''}" data-page="${p}">${p}</div>`;
            }
        });

        html += `<div class="pgn-btn" id="pgn-next" ${meta.current_page===meta.last_page?'style="opacity:.4;pointer-events:none;"':''}>
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i></div>`;

        paginationBtns.innerHTML = html;

        paginationBtns.querySelectorAll('.pgn-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                state.page = parseInt(btn.dataset.page);
                loadProducts();
            });
        });
        const prev = document.getElementById('pgn-prev');
        const next = document.getElementById('pgn-next');
        if (prev) prev.addEventListener('click', () => { if(state.page>1){state.page--;loadProducts();} });
        if (next) next.addEventListener('click', () => { if(state.page<meta.last_page){state.page++;loadProducts();} });
    }

    function buildPageRange(current, last) {
        if (last <= 7) return Array.from({length:last},(_,i)=>i+1);
        const pages = [];
        pages.push(1);
        if (current > 3) pages.push('...');
        for (let i = Math.max(2,current-1); i <= Math.min(last-1,current+1); i++) pages.push(i);
        if (current < last-2) pages.push('...');
        pages.push(last);
        return pages;
    }

    function bindRowEvents() {
        productsBody.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => openEditModal(parseInt(btn.dataset.id)));
        });
        productsBody.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => openDeleteModal(parseInt(btn.dataset.id), btn.dataset.name));
        });
        productsBody.querySelectorAll('.feat-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleFeatured(btn));
        });
        productsBody.querySelectorAll('.row-cb').forEach(cb => {
            cb.addEventListener('change', () => {
                const id = parseInt(cb.dataset.id);
                cb.checked ? state.selectedIds.add(id) : state.selectedIds.delete(id);
                updateBulkBar();
                const allCbs = productsBody.querySelectorAll('.row-cb');
                selectAllCb.checked = allCbs.length > 0 && [...allCbs].every(c => c.checked);
                selectAllCb.indeterminate = state.selectedIds.size > 0 && state.selectedIds.size < allCbs.length;
            });
        });
    }

    // ─── Featured toggle ───────────────────────────────────────────────────────

    function toggleFeatured(btn) {
        const id = parseInt(btn.dataset.id);
        btn.disabled = true;
        fetch(window.PROD.routes.featured(id), {
            method: 'PATCH',
            headers: { 'Accept':'application/json','X-CSRF-TOKEN':window.PROD.csrf }
        })
        .then(r=>r.json())
        .then(res => {
            if (res.success) {
                btn.classList.toggle('on', res.is_featured);
                btn.innerHTML = `<i class="bi ${res.is_featured ? 'bi-star-fill' : 'bi-star'}"></i>`;
                toast(res.message, 'info');
                refreshStats();
            }
        })
        .catch(() => toast('Failed to update featured status.','error'))
        .finally(() => { btn.disabled = false; });
    }

    // ─── Modal open/close ──────────────────────────────────────────────────────

    function openAddModal() {
        state.editingId = null;
        productId.value = '';
        modalTitle.innerHTML = '<i class="bi bi-plus-circle-fill me-2" style="color:var(--primary);"></i>Add New Product';
        saveBtnText.innerHTML = '<i class="bi bi-check-lg"></i> Publish Product';
        resetForm();
        productModal.show();
    }

    function openEditModal(id) {
        state.editingId = id;
        productId.value = id;
        modalTitle.innerHTML = '<i class="bi bi-pencil-fill me-2" style="color:var(--primary);"></i>Edit Product';
        saveBtnText.innerHTML = '<i class="bi bi-check-lg"></i> Update Product';
        resetForm();

        saveProductBtn.disabled = true;
        saveBtnText.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';

        fetch(window.PROD.routes.show(id), {
            headers: { 'Accept':'application/json','X-CSRF-TOKEN':window.PROD.csrf }
        })
        .then(r=>r.json())
        .then(res => {
            if (!res.success) throw new Error('Failed to load product.');
            fillForm(res.product);
            saveProductBtn.disabled = false;
            saveBtnText.innerHTML = '<i class="bi bi-check-lg"></i> Update Product';
        })
        .catch(err => {
            toast(err.message || 'Failed to load product.','error');
            productModal.hide();
        });

        productModal.show();
    }

    // ─── FIX: fillForm strips commas from formatted price strings ─────────────

    function fillForm(p) {
        const set = (id, val) => {
            const el = $(id);
            if (el) el.value = val ?? '';
        };
        const setPrice = (id, val) => {
            const el = $(id);
            if (el) el.value = parsePrice(val);
        };
        const setChecked = (id, val) => {
            const el = $(id);
            if (el) el.checked = !!val;
        };

        set('f_name',              p.name);
        set('f_short_description', p.short_description);
        set('f_description',       p.description);
        set('f_sku',               p.sku);
        set('f_barcode',           p.barcode);

        // ── PRICE FIX: strip commas before setting value ──
        setPrice('f_regular_price', p.regular_price);
        setPrice('f_sale_price',    p.sale_price);
        setPrice('f_cost_price',    p.cost_price);

        set('f_tax_class',           p.tax_class);
        set('f_stock_quantity',      p.stock_quantity);
        set('f_low_stock_threshold', p.low_stock_threshold);
        set('f_weight',              p.weight);
        set('f_length',              p.length);
        set('f_width',               p.width);
        set('f_height',              p.height);
        set('f_category_id',         p.category_id);
        set('f_brand',               p.brand);
        set('f_tags',                p.tags);
        set('f_status',              p.status);
        setChecked('f_is_featured',  p.is_featured);

        if (p.thumbnail_url && !p.thumbnail_url.includes('no-image')) {
            thumbImg.src = p.thumbnail_url;
            thumbPreview.style.display = 'block';
            thumbPlaceholder.style.display = 'none';
        }

        existingImages.innerHTML = '';
        if (p.product_images && p.product_images.length) {
            p.product_images.forEach(img => {
                const chip = document.createElement('div');
                chip.className = 'img-chip';
                chip.innerHTML = `
                    <img src="${img.url}" alt="">
                    <div class="del-img" data-img-id="${img.id}" title="Remove image">
                        <i class="bi bi-x"></i>
                    </div>`;
                chip.querySelector('.del-img').addEventListener('click', e => {
                    e.stopPropagation();
                    deleteGalleryImage(img.id, chip);
                });
                existingImages.appendChild(chip);
            });
        }
    }

    // ─── Save product ──────────────────────────────────────────────────────────

    function saveProduct(forceDraft = false) {
        clearFieldErrors();
        formErrors.style.display = 'none';

        const fd = new FormData(productForm);
        if (forceDraft) fd.set('status', 'draft');
        if (state.editingId) fd.append('_method', 'PUT');

        fd.set('is_featured',  $('f_is_featured').checked ? '1' : '0');
        fd.set('manage_stock', '1');

        const url    = state.editingId ? window.PROD.routes.update(state.editingId) : window.PROD.routes.store;
        const method = 'POST';

        saveProductBtn.disabled = true;
        saveDraftBtn.disabled   = true;
        saveBtnText.style.display    = 'none';
        saveBtnSpinner.style.display = 'inline-block';

        fetch(url, {
            method,
            headers: { 'X-CSRF-TOKEN': window.PROD.csrf, 'Accept': 'application/json' },
            body: fd,
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw data;
            return data;
        })
        .then(res => {
            productModal.hide();
            toast(res.message, 'success');
            loadProducts();
            refreshStats();
        })
        .catch(err => {
            if (err.errors) {
                Object.entries(err.errors).forEach(([field, messages]) => {
                    showFieldError(field, messages[0]);
                });
                formErrors.style.display = 'block';
                formErrors.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>' + (err.message || 'Please fix the errors below.');
            } else {
                toast(err.message || 'An error occurred. Please try again.', 'error');
            }
        })
        .finally(() => {
            saveProductBtn.disabled = false;
            saveDraftBtn.disabled   = false;
            saveBtnText.style.display    = 'inline';
            saveBtnSpinner.style.display = 'none';
        });
    }

    // ─── Delete ────────────────────────────────────────────────────────────────

    function openDeleteModal(id, name) {
        state.deleteTargetId   = id;
        state.deleteTargetName = name;
        deleteProductName.textContent = name;
        deleteModal.show();
    }

    function confirmDelete() {
        if (!state.deleteTargetId) return;
        confirmDeleteBtn.disabled = true;
        deleteSpinner.style.display = 'inline-block';

        fetch(window.PROD.routes.destroy(state.deleteTargetId), {
            method: 'DELETE',
            headers: { 'Accept':'application/json','X-CSRF-TOKEN':window.PROD.csrf }
        })
        .then(r=>r.json())
        .then(res => {
            deleteModal.hide();
            toast(res.message, 'success');
            if (state.page > 1) state.page--;
            loadProducts();
            refreshStats();
        })
        .catch(() => toast('Failed to delete product.','error'))
        .finally(() => {
            confirmDeleteBtn.disabled = false;
            deleteSpinner.style.display = 'none';
            state.deleteTargetId = null;
        });
    }

    function bulkDelete() {
        if (!state.selectedIds.size) return;
        if (!confirm(`Delete ${state.selectedIds.size} selected products? This cannot be undone.`)) return;

        fetch(window.PROD.routes.bulkDelete, {
            method: 'POST',
            headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':window.PROD.csrf },
            body: JSON.stringify({ ids: [...state.selectedIds] })
        })
        .then(r=>r.json())
        .then(res => {
            toast(res.message, 'success');
            state.selectedIds.clear();
            state.page = 1;
            loadProducts();
            refreshStats();
        })
        .catch(() => toast('Bulk delete failed.','error'));
    }

    function deleteGalleryImage(imgId, chip) {
        chip.style.opacity = '0.4';
        fetch(window.PROD.routes.deleteImage(imgId), {
            method: 'DELETE',
            headers: { 'Accept':'application/json','X-CSRF-TOKEN':window.PROD.csrf }
        })
        .then(r=>r.json())
        .then(res => {
            chip.remove();
            toast(res.message, 'info');
        })
        .catch(() => {
            chip.style.opacity = '1';
            toast('Failed to delete image.','error');
        });
    }

    // ─── Stats refresh ─────────────────────────────────────────────────────────

    function refreshStats() {
        fetch(window.PROD.routes.stats, {
            headers: { 'Accept':'application/json','X-CSRF-TOKEN':window.PROD.csrf }
        })
        .then(r=>r.json())
        .then(res => {
            if (!res.success) return;
            $('stat-total').textContent        = res.stats.total;
            $('stat-in-stock').textContent     = res.stats.in_stock;
            $('stat-low-stock').textContent    = res.stats.low_stock;
            $('stat-out-of-stock').textContent = res.stats.out_of_stock;
        });
    }

    // ─── Bulk bar ──────────────────────────────────────────────────────────────

    function updateBulkBar() {
        const count = state.selectedIds.size;
        bulkDeleteBtn.style.display = count > 0 ? 'inline-flex' : 'none';
        selectedCount.textContent = count;
    }

    // ─── Image previews ────────────────────────────────────────────────────────

    fThumbnail.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            thumbImg.src = e.target.result;
            thumbPreview.style.display = 'block';
            thumbPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    fImages.addEventListener('change', function () {
        imagesPreview.innerHTML = '';
        [...this.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.className = 'img-chip';
                wrap.innerHTML = `<img src="${e.target.result}" alt="">`;
                imagesPreview.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    });

    // ─── Field errors ──────────────────────────────────────────────────────────

    function showFieldError(field, message) {
        const errEl = $(`err_${field}`);
        const inpEl = $(`f_${field}`);
        if (errEl) errEl.textContent = message;
        if (inpEl) inpEl.classList.add('is-invalid');
    }

    function clearFieldErrors() {
        $$('.field-err').forEach(el => el.textContent = '');
        $$('.inp.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    function resetForm() {
        productForm.reset();
        clearFieldErrors();
        formErrors.style.display = 'none';
        productId.value = '';
        thumbImg.src = '';
        thumbPreview.style.display   = 'none';
        thumbPlaceholder.style.display = 'block';
        imagesPreview.innerHTML  = '';
        existingImages.innerHTML = '';
        fThumbnail.value = '';
        fImages.value    = '';
    }

    function clearFilters() {
        state.filter     = 'all';
        state.search     = '';
        state.categoryId = '';
        state.page       = 1;
        searchInput.value    = '';
        categoryFilter.value = '';
        $$('#filterChips .chip').forEach(c => c.classList.remove('on'));
        document.querySelector('#filterChips .chip[data-filter="all"]').classList.add('on');
        loadProducts();
    }

    // ─── Events ────────────────────────────────────────────────────────────────

    function bindEvents() {
        $('addProductBtn').addEventListener('click', openAddModal);
        saveProductBtn.addEventListener('click', () => saveProduct(false));
        saveDraftBtn.addEventListener('click', () => saveProduct(true));
        confirmDeleteBtn.addEventListener('click', confirmDelete);
        bulkDeleteBtn.addEventListener('click', bulkDelete);

        selectAllCb.addEventListener('change', function () {
            const cbs = productsBody.querySelectorAll('.row-cb');
            cbs.forEach(cb => {
                cb.checked = this.checked;
                const id = parseInt(cb.dataset.id);
                this.checked ? state.selectedIds.add(id) : state.selectedIds.delete(id);
            });
            updateBulkBar();
        });

        $$('#filterChips .chip').forEach(chip => {
            chip.addEventListener('click', function () {
                $$('#filterChips .chip').forEach(c => c.classList.remove('on'));
                this.classList.add('on');
                state.filter = this.dataset.filter;
                state.page   = 1;
                loadProducts();
            });
        });

        searchInput.addEventListener('input', function () {
            clearTimeout(state.debounceTimer);
            state.debounceTimer = setTimeout(() => {
                state.search = this.value.trim();
                state.page   = 1;
                loadProducts();
            }, 380);
        });

        categoryFilter.addEventListener('change', function () {
            state.categoryId = this.value;
            state.page       = 1;
            loadProducts();
        });

        perPageSelect.addEventListener('change', function () {
            state.perPage = parseInt(this.value);
            state.page    = 1;
            loadProducts();
        });

        $('exportBtn').addEventListener('click', () => {
            toast('Export started – downloading CSV...', 'info');
        });

        productForm.querySelectorAll('.inp').forEach(inp => {
            inp.addEventListener('input', () => {
                inp.classList.remove('is-invalid');
                const errEl = $(`err_${inp.name}`);
                if (errEl) errEl.textContent = '';
            });
        });

        $('productModal').addEventListener('hidden.bs.modal', resetForm);
    }

    function init() {
        bindEvents();
        loadCategories();
        loadProducts();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
