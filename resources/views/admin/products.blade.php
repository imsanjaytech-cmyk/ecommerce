@extends('layouts.adminlayout')
@section('page-title', 'Products')
@section('breadcrumb', 'Home / Products')

@section('content')

{{-- ── MINI STATS ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-w" style="text-align:center;padding:16px 12px;">
            <div id="stat-total" style="font-size:22px;font-weight:800;color:var(--dark);">{{ $stats['total'] }}</div>
            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-top:3px;">Total Products</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-w" style="text-align:center;padding:16px 12px;border-top:3px solid #1f9c4a;">
            <div id="stat-in-stock" style="font-size:22px;font-weight:800;color:#1f9c4a;">{{ $stats['in_stock'] }}</div>
            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-top:3px;">In Stock</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-w" style="text-align:center;padding:16px 12px;border-top:3px solid #d97706;">
            <div id="stat-low-stock" style="font-size:22px;font-weight:800;color:#d97706;">{{ $stats['low_stock'] }}</div>
            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-top:3px;">Low Stock</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-w" style="text-align:center;padding:16px 12px;border-top:3px solid var(--danger);">
            <div id="stat-out-of-stock" style="font-size:22px;font-weight:800;color:var(--danger);">{{ $stats['out_of_stock'] }}</div>
            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-top:3px;">Out of Stock</div>
        </div>
    </div>
</div>

{{-- ── TOP ACTION BAR ── --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div style="display:flex;gap:6px;flex-wrap:wrap;" id="filterChips">
        <button class="chip on" data-filter="all">All Products</button>
        <button class="chip" data-filter="published">Published</button>
        <button class="chip" data-filter="draft">Draft</button>
        <button class="chip" data-filter="out_of_stock">Out of Stock</button>
        <button class="chip" data-filter="low_stock">Low Stock</button>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <button class="btn-o" id="bulkDeleteBtn" style="display:none;color:var(--danger);border-color:rgba(220,53,69,0.3);">
            <i class="bi bi-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
        </button>
        <button class="btn-o" id="exportBtn"><i class="bi bi-download"></i> Export</button>
        <button class="btn-p" id="addProductBtn">
            <i class="bi bi-plus-lg"></i> Add Product
        </button>
    </div>
</div>

{{-- ── TABLE CARD ── --}}
<div class="card-w">
    <div class="sec-header" style="flex-wrap:wrap;gap:12px;">
        <div>
            <div class="sec-title">Product Inventory</div>
            <div class="sec-sub" id="tableSubtitle">Loading...</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <div class="search-wrap" style="width:240px;">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Name or SKU...">
            </div>
            <select class="inp" id="categoryFilter" style="width:150px;padding:8px 12px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <select class="inp" id="perPageSelect" style="width:85px;padding:8px 12px;">
                <option value="10">10 / pg</option>
                <option value="25">25 / pg</option>
                <option value="50">50 / pg</option>
            </select>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:38px;"><input type="checkbox" id="selectAll" style="accent-color:var(--primary);cursor:pointer;"></th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Sales</th>
                    <th>Featured</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="productsBody">
                <tr>
                    <td colspan="10" style="text-align:center;padding:50px;color:var(--text-muted);">
                        <div class="spinner-border spinner-border-sm me-2" style="color:var(--primary);"></div>
                        Loading products...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pgn" id="paginationBar" style="display:none;">
        <span class="pgn-info" id="paginationInfo"></span>
        <div class="pgn-btns" id="paginationBtns"></div>
    </div>
</div>


<div class="modal fade" id="productModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="bi bi-plus-circle-fill me-2" style="color:var(--primary);"></i>
                    Add New Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:26px;">
                <div id="formErrors" style="display:none;background:#fee8eb;border:1px solid rgba(220,53,69,0.2);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:var(--danger);"></div>

                <form id="productForm" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" id="productId" value="">

                    <div class="row g-4">
                        {{-- LEFT --}}
                        <div class="col-lg-8">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="lbl">Product Name <span style="color:var(--primary)">*</span></label>
                                    <input type="text" class="inp" name="name" id="f_name" placeholder="e.g. Wireless Bluetooth Headphones">
                                    <div class="field-err" id="err_name"></div>
                                </div>

                                <div class="col-12">
                                    <label class="lbl">Short Description</label>
                                    <textarea class="inp" name="short_description" id="f_short_description" rows="2" placeholder="Brief summary shown in product listings..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="lbl">Full Description</label>
                                    <textarea class="inp" name="description" id="f_description" rows="4" placeholder="Detailed product info, features, specs..."></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="lbl">SKU / Product Code <span style="color:var(--primary)">*</span></label>
                                    <input type="text" class="inp" name="sku" id="f_sku" placeholder="e.g. WBH-001">
                                    <div class="field-err" id="err_sku"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="lbl">Barcode (UPC / EAN)</label>
                                    <input type="text" class="inp" name="barcode" id="f_barcode" placeholder="e.g. 8901234567890">
                                </div>

                                {{-- Pricing block --}}
                                <div class="col-12">
                                    <div style="background:var(--pink-soft);border:1.5px solid var(--pink-border);border-radius:12px;padding:16px 18px;">
                                        <div style="font-size:11.5px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:1px;margin-bottom:14px;">
                                            <i class="bi bi-currency-dollar"></i> Pricing
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="lbl">Regular Price <span style="color:var(--primary)">*</span></label>
                                                <div style="position:relative;">
                                                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;font-weight:600;">$</span>
                                                    <input type="number" step="0.01" min="0" class="inp" name="regular_price" id="f_regular_price" placeholder="0.00" style="padding-left:26px;">
                                                </div>
                                                <div class="field-err" id="err_regular_price"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="lbl">Sale Price</label>
                                                <div style="position:relative;">
                                                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;font-weight:600;">$</span>
                                                    <input type="number" step="0.01" min="0" class="inp" name="sale_price" id="f_sale_price" placeholder="0.00" style="padding-left:26px;">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="lbl">Cost Price</label>
                                                <div style="position:relative;">
                                                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;font-weight:600;">$</span>
                                                    <input type="number" step="0.01" min="0" class="inp" name="cost_price" id="f_cost_price" placeholder="0.00" style="padding-left:26px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Inventory block --}}
                                <div class="col-12">
                                    <div style="background:#f0faf4;border:1.5px solid rgba(31,156,74,0.15);border-radius:12px;padding:16px 18px;">
                                        <div style="font-size:11.5px;font-weight:700;color:#1f9c4a;text-transform:uppercase;letter-spacing:1px;margin-bottom:14px;">
                                            <i class="bi bi-boxes"></i> Inventory
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="lbl">Stock Quantity <span style="color:var(--primary)">*</span></label>
                                                <input type="number" min="0" class="inp" name="stock_quantity" id="f_stock_quantity" placeholder="0">
                                                <div class="field-err" id="err_stock_quantity"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="lbl">Low Stock Alert At</label>
                                                <input type="number" min="0" class="inp" name="low_stock_threshold" id="f_low_stock_threshold" placeholder="10">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="lbl">Tax Class</label>
                                                <select class="inp" name="tax_class" id="f_tax_class">
                                                    <option value="standard">Standard (18%)</option>
                                                    <option value="reduced">Reduced (5%)</option>
                                                    <option value="zero">Zero (0%)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Dimensions --}}
                                <div class="col-12">
                                    <label class="lbl">Shipping Dimensions & Weight</label>
                                    <div class="row g-2">
                                        <div class="col-6 col-md-3">
                                            <input type="number" step="0.001" min="0" class="inp" name="weight" id="f_weight" placeholder="Weight kg">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <input type="number" step="0.01" min="0" class="inp" name="length" id="f_length" placeholder="Length cm">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <input type="number" step="0.01" min="0" class="inp" name="width" id="f_width" placeholder="Width cm">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <input type="number" step="0.01" min="0" class="inp" name="height" id="f_height" placeholder="Height cm">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="lbl">Tags</label>
                                    <input type="text" class="inp" name="tags" id="f_tags" placeholder="wireless, audio, premium (comma separated)">
                                </div>

                            </div>
                        </div>

                        {{-- RIGHT --}}
                        <div class="col-lg-4">
                            <div style="display:flex;flex-direction:column;gap:18px;">

                                {{-- Thumbnail --}}
                                <div>
                                    <label class="lbl">Main Product Image</label>
                                    <div class="upload-zone" id="thumbnailZone" style="cursor:pointer;" onclick="document.getElementById('f_thumbnail').click()">
                                        <div id="thumbPreview" style="display:none;margin-bottom:8px;">
                                            <img id="thumbImg" src="" alt="" style="width:100%;height:140px;object-fit:contain;border-radius:8px;background:#f5f6fb;">
                                        </div>
                                        <div id="thumbPlaceholder">
                                            <div class="uz-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                                            <div class="uz-text">Click to upload thumbnail</div>
                                            <div class="uz-sub">PNG, JPG, WEBP — max 5MB</div>
                                        </div>
                                    </div>
                                    <input type="file" id="f_thumbnail" name="thumbnail" accept="image/*" style="display:none;">
                                </div>

                                {{-- Extra images --}}
                                <div>
                                    <label class="lbl">Additional Images</label>
                                    <div id="existingImages" style="display:flex;flex-wrap:wrap;gap:6px;min-height:0;"></div>
                                    <div class="upload-zone" style="padding:14px;cursor:pointer;" onclick="document.getElementById('f_images').click()">
                                        <div class="uz-icon" style="font-size:22px;"><i class="bi bi-images"></i></div>
                                        <div class="uz-text" style="font-size:12px;">Add gallery images</div>
                                        <div class="uz-sub">Multiple files supported</div>
                                    </div>
                                    <div id="imagesPreview" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>
                                    <input type="file" id="f_images" name="images[]" accept="image/*" multiple style="display:none;">
                                </div>

                                {{-- Category --}}
                                <div>
                                    <label class="lbl">Category <span style="color:var(--primary)">*</span></label>
                                    <select class="inp" name="category_id" id="f_category_id">
                                        <option value="">Select category...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="field-err" id="err_category_id"></div>
                                </div>

                                <div>
                                    <label class="lbl">Brand / Manufacturer</label>
                                    <input type="text" class="inp" name="brand" id="f_brand" placeholder="Brand name">
                                </div>

                                <div>
                                    <label class="lbl">Publish Status</label>
                                    <select class="inp" name="status" id="f_status">
                                        <option value="published">Published</option>
                                        <option value="draft">Draft</option>
                                        <option value="scheduled">Scheduled</option>
                                    </select>
                                </div>

                                <div style="display:flex;align-items:center;justify-content:space-between;background:var(--pink-soft);padding:13px 15px;border-radius:11px;border:1.5px solid var(--pink-border);">
                                    <div>
                                        <div style="font-size:13.5px;font-weight:600;color:var(--dark);">Featured Product</div>
                                        <div style="font-size:11.5px;color:var(--text-muted);">Show on homepage</div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="f_is_featured" style="width:40px;height:22px;cursor:pointer;">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer" style="gap:8px;">
                <button type="button" class="btn-o" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-o" id="saveDraftBtn"><i class="bi bi-floppy"></i> Save Draft</button>
                <button type="button" class="btn-p" id="saveProductBtn">
                    <span id="saveBtnText"><i class="bi bi-check-lg"></i> Publish Product</span>
                    <span id="saveBtnSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-body" style="padding:32px;text-align:center;">
                <div style="width:64px;height:64px;background:#fee8eb;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                    <i class="bi bi-trash-fill" style="font-size:26px;color:var(--danger);"></i>
                </div>
                <h5 style="font-weight:700;color:var(--dark);margin-bottom:8px;">Delete Product?</h5>
                <p style="color:var(--text-muted);font-size:13.5px;margin-bottom:24px;">
                    You are about to delete <strong id="deleteProductName" style="color:var(--dark);"></strong>.
                    This cannot be undone.
                </p>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button class="btn-o" data-bs-dismiss="modal" style="min-width:110px;">Cancel</button>
                    <button class="btn-p" id="confirmDeleteBtn" style="background:linear-gradient(135deg,#dc3545,#c62535);box-shadow:0 4px 14px rgba(220,53,69,0.3);min-width:110px;">
                        <i class="bi bi-trash"></i> Delete
                        <span id="deleteSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toastContainer" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column-reverse;gap:8px;pointer-events:none;"></div>

<style>
.field-err { font-size:11.5px; color:var(--danger); margin-top:4px; min-height:16px; }
.inp.is-invalid { border-color:var(--danger)!important; background:rgba(220,53,69,0.04)!important; }
.inp.is-valid   { border-color:#1f9c4a!important; }

.feat-btn { background:none; border:none; cursor:pointer; padding:3px; font-size:16px; color:#ddd; transition:color 0.2s; }
.feat-btn.on { color:#f7971e; }
.feat-btn:hover { color:#f7971e; transform:scale(1.2); }

.img-chip {
    position:relative;width:52px;height:52px;border-radius:8px;overflow:hidden;
    border:1.5px solid var(--border-col);cursor:pointer;
}
.img-chip img { width:100%;height:100%;object-fit:cover; }
.img-chip .del-img {
    position:absolute;top:2px;right:2px;width:16px;height:16px;
    background:rgba(220,53,69,0.85);border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:9px;color:white;cursor:pointer;
}
</style>

@endsection

@push('scripts')
<script>
window.PROD = {
    routes: {
        list:        '{{ route("admin.products.list") }}',
        store:       '{{ route("admin.products.store") }}',
        stats:       '{{ route("admin.products.stats") }}',
        show:        id => `/admin/products/${id}`,
        update:      id => `/admin/products/${id}`,
        destroy:     id => `/admin/products/${id}`,
        bulkDelete:  '{{ route("admin.products.bulk-delete") }}',
        featured:    id => `/admin/products/${id}/featured`,
        deleteImage: id => `/admin/product-images/${id}`,
    },
    csrf: '{{ csrf_token() }}'
};
</script>
<script src="{{ asset('js/products.js') }}"></script>
@endpush