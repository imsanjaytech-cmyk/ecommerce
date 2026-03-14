@extends('layouts.adminlayout')
@section('page-title', 'Categories')
@section('breadcrumb', 'Categories')

@section('content')

{{-- ── STATS BAR ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-w" style="text-align:center;padding:16px 12px;">
            <div id="stat-total" style="font-size:22px;font-weight:800;color:var(--dark);">{{ $categories->count() }}</div>
            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-top:3px;">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-w" style="text-align:center;padding:16px 12px;border-top:3px solid #1f9c4a;">
            <div id="stat-active" style="font-size:22px;font-weight:800;color:#1f9c4a;">{{ $categories->where('is_active', true)->count() }}</div>
            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-top:3px;">Active</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-w" style="text-align:center;padding:16px 12px;border-top:3px solid var(--primary);">
            <div id="stat-products" style="font-size:22px;font-weight:800;color:var(--primary);">{{ $categories->sum('products_count') }}</div>
            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-top:3px;">Products</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-w" style="text-align:center;padding:16px 12px;border-top:3px solid #1a7cd4;">
            <div id="stat-parents" style="font-size:22px;font-weight:800;color:#1a7cd4;">{{ $categories->whereNull('parent_id')->count() }}</div>
            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-top:3px;">Top-Level</div>
        </div>
    </div>
</div>

{{-- ── ACTION BAR ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <div class="search-wrap" style="width:220px;">
            <i class="bi bi-search"></i>
            <input type="text" id="catSearchInput" placeholder="Search categories...">
        </div>
        <select class="inp" id="catStatusFilter" style="width:130px;padding:8px 12px;">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <button class="btn-p" id="addCatBtn">
        <i class="bi bi-plus-lg"></i> Add Category
    </button>
</div>

{{-- ── CATEGORY GRID ── --}}
<div class="row g-3" id="categoriesGrid">
    {{-- Populated by JS --}}
    <div class="col-12 text-center py-5" id="gridLoader">
        <div class="spinner-border spinner-border-sm me-2" style="color:var(--primary);"></div>
        <span style="color:var(--text-muted);font-size:13px;">Loading categories...</span>
    </div>
</div>

{{-- ── ADD / EDIT MODAL ── --}}
<div class="modal fade" id="catModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="catModalTitle">
                    <i class="bi bi-tags-fill me-2" style="color:var(--primary);"></i>Add Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div id="catFormErrors" style="display:none;background:#fee8eb;border:1px solid rgba(220,53,69,0.2);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:var(--danger);"></div>

                <form id="catForm" novalidate>
                    <input type="hidden" id="catId">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="lbl">Category Name <span style="color:var(--primary)">*</span></label>
                            <input type="text" class="inp" id="f_cat_name" name="name" placeholder="e.g. Electronics">
                            <div class="field-err" id="err_cat_name"></div>
                        </div>

                        <div class="col-12">
                            <label class="lbl">Slug</label>
                            <input type="text" class="inp" id="f_cat_slug" name="slug" placeholder="auto-generated from name">
                            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Leave blank to auto-generate from name.</div>
                        </div>

                        <div class="col-12">
                            <label class="lbl">Description</label>
                            <textarea class="inp" id="f_cat_description" name="description" rows="3" placeholder="Category description..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="lbl">Icon <span style="font-size:10px;font-weight:400;text-transform:none;letter-spacing:0;">(Bootstrap Icons class)</span></label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="text" class="inp" id="f_cat_icon" name="icon" placeholder="bi-tag" style="flex:1;">
                                <div id="iconPreview" style="width:38px;height:38px;border-radius:9px;background:var(--pink-soft);border:1.5px solid var(--pink-border);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-tag" id="iconPreviewI" style="font-size:17px;color:var(--primary);"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="lbl">Color</label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="text" class="inp" id="f_cat_color" name="color" placeholder="#ff4d6d" style="flex:1;">
                                <input type="color" id="f_cat_color_picker" value="#ff4d6d"
                                    style="width:38px;height:38px;border-radius:9px;border:1.5px solid var(--border-col);cursor:pointer;padding:3px;background:white;flex-shrink:0;">
                            </div>
                            {{-- Quick color swatches --}}
                            <div style="display:flex;gap:5px;margin-top:8px;flex-wrap:wrap;" id="colorSwatches">
                                <div class="color-swatch" data-color="#ff4d6d" style="background:#ff4d6d;"></div>
                                <div class="color-swatch" data-color="#1f9c4a" style="background:#1f9c4a;"></div>
                                <div class="color-swatch" data-color="#1a7cd4" style="background:#1a7cd4;"></div>
                                <div class="color-swatch" data-color="#d97706" style="background:#d97706;"></div>
                                <div class="color-swatch" data-color="#7c3aed" style="background:#7c3aed;"></div>
                                <div class="color-swatch" data-color="#0891b2" style="background:#0891b2;"></div>
                                <div class="color-swatch" data-color="#be185d" style="background:#be185d;"></div>
                                <div class="color-swatch" data-color="#374151" style="background:#374151;"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div style="display:flex;align-items:center;justify-content:space-between;background:var(--pink-soft);padding:13px 15px;border-radius:11px;border:1.5px solid var(--pink-border);">
                                <div>
                                    <div style="font-size:13.5px;font-weight:600;color:var(--dark);">Active</div>
                                    <div style="font-size:11.5px;color:var(--text-muted);">Show in storefront</div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="f_cat_active" name="is_active" checked style="width:40px;height:22px;cursor:pointer;">
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer" style="gap:8px;">
                <button class="btn-o" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-p" id="saveCatBtn">
                    <span id="saveCatText"><i class="bi bi-check-lg"></i> Create Category</span>
                    <span id="saveCatSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── DELETE CONFIRM MODAL ── --}}
<div class="modal fade" id="deleteCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-body" style="padding:32px;text-align:center;">
                <div style="width:64px;height:64px;background:#fee8eb;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                    <i class="bi bi-trash-fill" style="font-size:26px;color:var(--danger);"></i>
                </div>
                <h5 style="font-weight:700;color:var(--dark);margin-bottom:8px;">Delete Category?</h5>
                <p style="color:var(--text-muted);font-size:13.5px;margin-bottom:6px;">
                    You are about to delete <strong id="deleteCatName" style="color:var(--dark);"></strong>.
                </p>
                <p id="deleteCatWarning" style="display:none;color:var(--danger);font-size:12.5px;font-weight:600;background:#fee8eb;padding:8px 12px;border-radius:8px;margin-bottom:16px;"></p>
                <p style="color:var(--text-muted);font-size:12.5px;margin-bottom:24px;">This action cannot be undone.</p>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button class="btn-o" data-bs-dismiss="modal" style="min-width:110px;">Cancel</button>
                    <button class="btn-p" id="confirmDeleteCatBtn"
                        style="background:linear-gradient(135deg,#dc3545,#c62535);box-shadow:0 4px 14px rgba(220,53,69,0.3);min-width:110px;">
                        <i class="bi bi-trash"></i> Delete
                        <span id="deleteCatSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.field-err { font-size:11.5px; color:var(--danger); margin-top:4px; min-height:16px; }
.inp.is-invalid { border-color:var(--danger)!important; background:rgba(220,53,69,0.04)!important; }

.color-swatch {
    width: 22px; height: 22px; border-radius: 6px;
    cursor: pointer; border: 2px solid transparent;
    transition: transform 0.15s, border-color 0.15s;
}
.color-swatch:hover { transform: scale(1.2); }
.color-swatch.selected { border-color: var(--dark); }

/* Category card */
.cat-card {
    background: var(--white);
    border: 1px solid var(--border-col);
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}
.cat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.cat-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    border-radius: 4px 0 0 4px;
    background: var(--cat-color, #ccc);
}

.cat-icon-wrap {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 20px;
}

.cat-name { font-weight:700; font-size:14.5px; color:var(--dark); }
.cat-slug { font-size:11px; color:var(--text-muted); margin-top:2px; }

.cat-badge-inactive {
    font-size: 10.5px; font-weight: 700;
    background: #f1f3f5; color: var(--gray);
    padding: 2px 9px; border-radius: 6px;
}
.cat-badge-active {
    font-size: 10.5px; font-weight: 700;
    background: #e8f8ee; color: #1f9c4a;
    padding: 2px 9px; border-radius: 6px;
}

.cat-toggle-btn {
    width: 28px; height: 28px; border-radius: 7px;
    border: 1.5px solid var(--border-col); background: white;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 12px; color: var(--gray);
    transition: var(--transition);
}
.cat-toggle-btn:hover { background: #e8f8ee; border-color: #1f9c4a; color: #1f9c4a; }

.empty-state {
    text-align: center; padding: 60px 20px; color: var(--text-muted);
}
.empty-state i { font-size: 48px; opacity: 0.2; display: block; margin-bottom: 12px; }
.empty-state p { font-size: 13px; margin: 0; }
</style>

@endsection

@push('scripts')
<script>
window.CAT = {
    routes: {
        list:    '{{ route("admin.categories.list") }}',
        store:   '{{ route("admin.categories.store") }}',
        show:    id => `/admin/categories/${id}`,
        update:  id => `/admin/categories/${id}`,
        destroy: id => `/admin/categories/${id}`,
        toggle:  id => `/admin/categories/${id}/toggle`,
    },
    csrf: '{{ csrf_token() }}'
};
</script>
<script src="{{ asset('js/categories.js') }}"></script>
@endpush
