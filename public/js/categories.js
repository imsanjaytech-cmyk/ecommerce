(function () {
    'use strict';

    const state = {
        categories:       [],
        filtered:         [],
        search:           '',
        statusFilter:     '',
        editingId:        null,
        deleteTargetId:   null,
        deleteTargetName: '',
        debounceTimer:    null,
    };

    const $ = id => document.getElementById(id);

    const grid          = $('categoriesGrid');
    const gridLoader    = $('gridLoader');
    const searchInput   = $('catSearchInput');
    const statusFilter  = $('catStatusFilter');

    const catModal      = new bootstrap.Modal($('catModal'));
    const deleteModal   = new bootstrap.Modal($('deleteCatModal'));

    const catModalTitle = $('catModalTitle');
    const catId         = $('catId');
    const catForm       = $('catForm');
    const catFormErrors = $('catFormErrors');
    const saveCatBtn    = $('saveCatBtn');
    const saveCatText   = $('saveCatText');
    const saveCatSpinner= $('saveCatSpinner');

    const fName         = $('f_cat_name');
    const fSlug         = $('f_cat_slug');
    const fDesc         = $('f_cat_description');
    const fIcon         = $('f_cat_icon');
    const fColor        = $('f_cat_color');
    const fColorPicker  = $('f_cat_color_picker');
    const fActive       = $('f_cat_active');
    const iconPreviewI  = $('iconPreviewI');

    const deleteCatName    = $('deleteCatName');
    const deleteCatWarning = $('deleteCatWarning');
    const confirmDeleteBtn = $('confirmDeleteCatBtn');
    const deleteCatSpinner = $('deleteCatSpinner');


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

    if (!document.getElementById('cat-anim-style')) {
        const s = document.createElement('style');
        s.id = 'cat-anim-style';
        s.textContent = `@keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}`;
        document.head.appendChild(s);
    }

    function hexWithAlpha(hex, alpha) {
        const r = parseInt(hex.slice(1,3),16);
        const g = parseInt(hex.slice(3,5),16);
        const b = parseInt(hex.slice(5,7),16);
        return `rgba(${r},${g},${b},${alpha})`;
    }

    function refreshStats() {
        const cats = state.categories;
        $('stat-total').textContent    = cats.length;
        $('stat-active').textContent   = cats.filter(c => c.is_active).length;
        $('stat-products').textContent = cats.reduce((sum, c) => sum + (c.products_count || 0), 0);
        $('stat-parents').textContent  = cats.filter(c => !c.parent_id).length;
    }

    function loadCategories() {
        if (gridLoader) gridLoader.style.display = 'flex';

        fetch(window.CAT.routes.list, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.CAT.csrf }
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error('Failed to load categories.');
            state.categories = res.data;
            refreshStats();
            applyFilter();
        })
        .catch(err => {
            if (gridLoader) gridLoader.style.display = 'none';
            grid.innerHTML = `<div class="col-12"><div class="empty-state">
                <i class="bi bi-exclamation-triangle"></i>
                <p style="color:var(--danger);">${err.message || 'Failed to load. Please refresh.'}</p>
            </div></div>`;
        });
    }

    function applyFilter() {
        let list = [...state.categories];

        if (state.search) {
            const q = state.search.toLowerCase();
            list = list.filter(c =>
                (c.name  || '').toLowerCase().includes(q) ||
                (c.slug  || '').toLowerCase().includes(q) ||
                (c.description || '').toLowerCase().includes(q)
            );
        }

        if (state.statusFilter === 'active')   list = list.filter(c =>  c.is_active);
        if (state.statusFilter === 'inactive') list = list.filter(c => !c.is_active);

        state.filtered = list;
        renderGrid(list);
    }

    function renderGrid(cats) {
        if (gridLoader) gridLoader.style.display = 'none';

        [...grid.children].forEach(el => {
            if (el.id !== 'gridLoader') el.remove();
        });

        if (!cats.length) {
            const empty = document.createElement('div');
            empty.className = 'col-12';
            empty.innerHTML = `<div class="empty-state">
                <i class="bi bi-tags"></i>
                <p>No categories found. <a href="#" id="clearCatFilter" style="color:var(--primary);">Clear filters</a></p>
            </div>`;
            grid.appendChild(empty);
            const cl = document.getElementById('clearCatFilter');
            if (cl) cl.addEventListener('click', e => { e.preventDefault(); clearFilters(); });
            return;
        }

        cats.forEach((c, idx) => {
            const col = document.createElement('div');
            col.className = 'col-md-4 col-sm-6';
            col.style.animationDelay = `${idx * 40}ms`;

            const color    = c.color || '#9199a6';
            const icon     = c.icon  || 'bi-tag';
            const bgSoft   = hexWithAlpha(color, 0.10);
            const bgBorder = hexWithAlpha(color, 0.15);

            col.innerHTML = `
                <div class="cat-card" style="--cat-color:${color};" data-id="${c.id}">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                            <div class="cat-icon-wrap"
                                style="background:${bgSoft};border:1.5px solid ${bgBorder};">
                                <i class="bi ${esc(icon)}" style="color:${esc(color)};"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="cat-name">${esc(c.name)}</div>
                                <div class="cat-slug">/${esc(c.slug)}</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:5px;flex-shrink:0;margin-left:8px;">
                            <div class="act-btn cat-edit-btn" data-id="${c.id}" title="Edit" style="opacity:1;">
                                <i class="bi bi-pencil"></i>
                            </div>
                            <div class="cat-toggle-btn toggle-btn" data-id="${c.id}" title="${c.is_active ? 'Deactivate' : 'Activate'}" style="opacity:1;">
                                <i class="bi ${c.is_active ? 'bi-eye-slash' : 'bi-eye'}"></i>
                            </div>
                            <div class="act-btn del cat-delete-btn" data-id="${c.id}" data-name="${esc(c.name)}" data-products="${c.products_count || 0}" title="Delete" style="opacity:1;">
                                <i class="bi bi-trash"></i>
                            </div>
                        </div>
                    </div>

                    ${c.description ? `
                    <p style="font-size:12px;color:var(--text-muted);margin-bottom:12px;line-height:1.5;
                        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        ${esc(c.description)}
                    </p>` : ''}

                    <div style="display:flex;align-items:center;justify-content:space-between;
                        padding-top:12px;border-top:1px solid var(--border-col);">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-box-seam" style="color:var(--text-muted);font-size:12px;"></i>
                            <span style="font-size:12.5px;color:var(--text-muted);font-weight:600;">
                                ${c.products_count || 0} product${(c.products_count || 0) !== 1 ? 's' : ''}
                            </span>
                        </div>
                        <span class="${c.is_active ? 'cat-badge-active' : 'cat-badge-inactive'}">
                            ${c.is_active ? '● Active' : '○ Inactive'}
                        </span>
                    </div>
                </div>`;

            grid.appendChild(col);
        });

        grid.querySelectorAll('.cat-edit-btn').forEach(btn => {
            btn.addEventListener('click', () => openEditModal(parseInt(btn.dataset.id)));
        });
        grid.querySelectorAll('.cat-delete-btn').forEach(btn => {
            btn.addEventListener('click', () =>
                openDeleteModal(parseInt(btn.dataset.id), btn.dataset.name, parseInt(btn.dataset.products)));
        });
        grid.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleActive(parseInt(btn.dataset.id)));
        });
    }


    function openAddModal() {
        state.editingId = null;
        catId.value = '';
        catModalTitle.innerHTML = '<i class="bi bi-plus-circle-fill me-2" style="color:var(--primary);"></i>Add Category';
        saveCatText.innerHTML = '<i class="bi bi-check-lg"></i> Create Category';
        resetCatForm();
        catModal.show();
    }

    function openEditModal(id) {
        state.editingId = id;
        catId.value = id;
        catModalTitle.innerHTML = '<i class="bi bi-pencil-fill me-2" style="color:var(--primary);"></i>Edit Category';
        saveCatText.innerHTML = '<i class="bi bi-check-lg"></i> Update Category';
        resetCatForm();

        saveCatBtn.disabled = true;
        saveCatText.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';

        fetch(window.CAT.routes.show(id), {
            headers: { 'Accept':'application/json','X-CSRF-TOKEN':window.CAT.csrf }
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error('Failed to load category.');
            fillCatForm(res.category);
            saveCatBtn.disabled = false;
            saveCatText.innerHTML = '<i class="bi bi-check-lg"></i> Update Category';
        })
        .catch(err => {
            toast(err.message || 'Failed to load category.', 'error');
            catModal.hide();
        });

        catModal.show();
    }

    function openDeleteModal(id, name, productsCount) {
        state.deleteTargetId   = id;
        state.deleteTargetName = name;
        deleteCatName.textContent = name;

        if (productsCount > 0) {
            deleteCatWarning.style.display = 'block';
            deleteCatWarning.textContent = `⚠ This category has ${productsCount} product${productsCount !== 1 ? 's' : ''}. Reassign them before deleting.`;
            confirmDeleteBtn.disabled = true;
        } else {
            deleteCatWarning.style.display = 'none';
            confirmDeleteBtn.disabled = false;
        }

        deleteModal.show();
    }

    function fillCatForm(c) {
        fName.value        = c.name        || '';
        fSlug.value        = c.slug        || '';
        fDesc.value        = c.description || '';
        fIcon.value        = c.icon        || '';
        fColor.value       = c.color       || '';
        fActive.checked    = !!c.is_active;
        fColorPicker.value = c.color       || '#ff4d6d';

        updateIconPreview(c.icon);
        updateColorSwatches(c.color);
    }

    function saveCategory() {
        clearCatErrors();

        const payload = {
            name:        fName.value.trim(),
            slug:        fSlug.value.trim() || null,
            description: fDesc.value.trim() || null,
            icon:        fIcon.value.trim() || null,
            color:       fColor.value.trim() || null,
            is_active:   fActive.checked ? 1 : 0,
        };

        if (!payload.name) {
            showCatError('cat_name', 'Category name is required.');
            return;
        }

        const isEdit = !!state.editingId;
        const url    = isEdit ? window.CAT.routes.update(state.editingId) : window.CAT.routes.store;
        const method = isEdit ? 'PUT' : 'POST';

        saveCatBtn.disabled          = true;
        saveCatText.style.display    = 'none';
        saveCatSpinner.style.display = 'inline-block';

        fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.CAT.csrf,
            },
            body: JSON.stringify(payload),
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw data;
            return data;
        })
        .then(res => {
            catModal.hide();
            toast(res.message, 'success');
            loadCategories();
        })
        .catch(err => {
            if (err.errors) {
                Object.entries(err.errors).forEach(([field, msgs]) => {
                    showCatError(field, msgs[0]);
                });
                catFormErrors.style.display = 'block';
                catFormErrors.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>' + (err.message || 'Please fix the errors below.');
            } else {
                toast(err.message || 'An error occurred.', 'error');
            }
        })
        .finally(() => {
            saveCatBtn.disabled          = false;
            saveCatText.style.display    = 'inline';
            saveCatSpinner.style.display = 'none';
        });
    }

    function confirmDelete() {
        if (!state.deleteTargetId) return;
        confirmDeleteBtn.disabled      = true;
        deleteCatSpinner.style.display = 'inline-block';

        fetch(window.CAT.routes.destroy(state.deleteTargetId), {
            method: 'DELETE',
            headers: { 'Accept':'application/json','X-CSRF-TOKEN':window.CAT.csrf }
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            deleteModal.hide();
            toast(res.message, 'success');
            loadCategories();
        })
        .catch(err => {
            toast(err.message || 'Failed to delete category.', 'error');
        })
        .finally(() => {
            confirmDeleteBtn.disabled      = false;
            deleteCatSpinner.style.display = 'none';
            state.deleteTargetId = null;
        });
    }

    function toggleActive(id) {
        fetch(window.CAT.routes.toggle(id), {
            method: 'PATCH',
            headers: { 'Accept':'application/json','X-CSRF-TOKEN':window.CAT.csrf }
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            toast(res.message, 'info');
            const cat = state.categories.find(c => c.id === id);
            if (cat) cat.is_active = res.is_active;
            applyFilter();
            refreshStats();
        })
        .catch(err => toast(err.message || 'Toggle failed.', 'error'));
    }


    function showCatError(field, message) {
        const key   = field.replace(/^cat_/, '');
        const errEl = $(`err_cat_${key}`);
        const inpEl = $(`f_cat_${key}`);
        if (errEl) errEl.textContent = message;
        if (inpEl) inpEl.classList.add('is-invalid');
    }

    function clearCatErrors() {
        document.querySelectorAll('.field-err').forEach(el => el.textContent = '');
        document.querySelectorAll('.inp.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        catFormErrors.style.display = 'none';
    }

    function resetCatForm() {
        catForm.reset();
        clearCatErrors();
        catId.value        = '';
        fActive.checked    = true;
        fColorPicker.value = '#ff4d6d';
        updateIconPreview('bi-tag');
        updateColorSwatches('');
    }

    function updateIconPreview(iconClass) {
        if (!iconClass) iconClass = 'bi-tag';
        iconClass = iconClass.replace(/^bi\s+/, '').trim();
        if (!iconClass.startsWith('bi-')) iconClass = 'bi-' + iconClass;
        iconPreviewI.className = `bi ${iconClass}`;
    }

    function updateColorSwatches(color) {
        document.querySelectorAll('.color-swatch').forEach(sw => {
            sw.classList.toggle('selected', sw.dataset.color === color);
        });
    }

    function clearFilters() {
        state.search       = '';
        state.statusFilter = '';
        searchInput.value  = '';
        statusFilter.value = '';
        applyFilter();
    }


    function bindEvents() {
        $('addCatBtn').addEventListener('click', openAddModal);
        saveCatBtn.addEventListener('click', saveCategory);
        confirmDeleteBtn.addEventListener('click', confirmDelete);

        $('catModal').addEventListener('hidden.bs.modal', resetCatForm);

        searchInput.addEventListener('input', function () {
            clearTimeout(state.debounceTimer);
            state.debounceTimer = setTimeout(() => {
                state.search = this.value.trim();
                applyFilter();
            }, 300);
        });

        statusFilter.addEventListener('change', function () {
            state.statusFilter = this.value;
            applyFilter();
        });

        fIcon.addEventListener('input', () => updateIconPreview(fIcon.value));

        fColor.addEventListener('input', () => {
            const val = fColor.value.trim();
            if (/^#[0-9a-fA-F]{6}$/.test(val)) {
                fColorPicker.value = val;
            }
            updateColorSwatches(val);
        });

        fColorPicker.addEventListener('input', () => {
            fColor.value = fColorPicker.value;
            updateColorSwatches(fColorPicker.value);
        });

        document.querySelectorAll('.color-swatch').forEach(sw => {
            sw.addEventListener('click', () => {
                const color    = sw.dataset.color;
                fColor.value       = color;
                fColorPicker.value = color;
                updateColorSwatches(color);
            });
        });

        fName.addEventListener('input', () => {
            if (!catId.value) {
                fSlug.value = fName.value.trim()
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
            fName.classList.remove('is-invalid');
            const e = $('err_cat_name');
            if (e) e.textContent = '';
        });

        catForm.querySelectorAll('.inp').forEach(inp => {
            inp.addEventListener('input', () => {
                inp.classList.remove('is-invalid');
                const field = inp.id ? inp.id.replace('f_cat_','') : '';
                const errEl = $(`err_cat_${field}`);
                if (errEl) errEl.textContent = '';
            });
        });
    }

    function init() {
        bindEvents();
        loadCategories();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
