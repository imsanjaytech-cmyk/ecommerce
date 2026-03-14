<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'short_description', 'description',
        'sku', 'barcode',
        'regular_price', 'sale_price', 'cost_price', 'tax_class',
        'stock_quantity', 'low_stock_threshold', 'manage_stock', 'stock_status',
        'weight', 'length', 'width', 'height',
        'category_id', 'brand', 'tags',
        'thumbnail', 'status', 'is_featured', 'total_sales',
    ];

    protected $casts = [
        'regular_price'       => 'decimal:2',
        'sale_price'          => 'decimal:2',
        'cost_price'          => 'decimal:2',
        'stock_quantity'      => 'integer',
        'low_stock_threshold' => 'integer',
        'manage_stock'        => 'boolean',
        'is_featured'         => 'boolean',
        'total_sales'         => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getThumbnailUrlAttribute(): string
    {
        $thumb = $this->thumbnail;

        if (empty($thumb)) {
            return $this->placeholder();
        }

        if (str_starts_with($thumb, 'http://') || str_starts_with($thumb, 'https://')) {
            return $thumb;
        }

        $path = str_replace('\\', '/', $thumb);

        return asset('storage/' . $path);
    }

    public function getGalleryUrlsAttribute(): array
    {
        $urls = [];

        $urls[] = $this->thumbnail_url;

        foreach ($this->productImages as $img) {
            $path = $img->path ?? '';

            if (empty($path)) {
                continue;
            }

            $parts = preg_split('/(?=products\/)/', $path, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($parts as $part) {
                $clean = $this->cleanPath($part);

                if (empty($clean)) {
                    continue;
                }

                if (Storage::disk('public')->exists($clean)) {
                    $url = asset('storage/' . $clean);
                    if (! in_array($url, $urls)) {
                        $urls[] = $url;
                    }
                }
            }
        }

        return $urls;
    }

    public function getActivePriceAttribute(): float
    {
        return (float) ($this->sale_price ?: $this->regular_price);
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->sale_price || $this->sale_price >= $this->regular_price) {
            return 0;
        }

        return (int) round(
            (($this->regular_price - $this->sale_price) / $this->regular_price) * 100
        );
    }

    public function getBadgeAttribute(): ?string
    {
        if ($this->sale_price) return 'sale';
        if ($this->created_at?->diffInDays(now()) <= 14) return 'new';
        if ($this->total_sales >= 100) return 'bestseller';
        return null;
    }

    public function getRatingAttribute(): int
    {
        return 0;
    }

    public function getReviewCountAttribute(): int
    {
        return 0;
    }

    public function getTagsArrayAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }

        return array_filter(
            array_map('trim', explode(',', $this->tags))
        );
    }

    // CSS class only → use as: class="bdg {{ $product->stock_badge }}"
    public function getStockBadgeAttribute(): string
    {
        return match ($this->stock_status) {
            'in_stock'     => 'bdg-success',
            'low_stock'    => 'bdg-warning',
            'out_of_stock' => 'bdg-danger',
            default        => 'bdg-gray',
        };
    }

    // Display text → use as: {{ $product->stock_label }}
    public function getStockLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'in_stock'     => 'In Stock',
            'low_stock'    => 'Low Stock',
            'out_of_stock' => 'Out of Stock',
            default        => 'Unknown',
        };
    }

    // CSS class only → use as: class="bdg {{ $product->status_badge }}"
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'published' => 'bdg-success',
            'draft'     => 'bdg-gray',
            'scheduled' => 'bdg-info',
            default     => 'bdg-gray',
        };
    }

    // Display text → use as: {{ $product->status_label }}
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'published' => 'Published',
            'draft'     => 'Draft',
            'scheduled' => 'Scheduled',
            default     => 'Unknown',
        };
    }

    // ─── Mutators ─────────────────────────────────────────────────────────────

    public function setStockQuantityAttribute(int $value): void
    {
        $this->attributes['stock_quantity'] = $value;
        $threshold = $this->low_stock_threshold ?? 10;

        $this->attributes['stock_status'] = match (true) {
            $value <= 0          => 'out_of_stock',
            $value <= $threshold => 'low_stock',
            default              => 'in_stock',
        };
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function cleanPath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#^storage/app/public/#', '', $path);
        $path = preg_replace('#^storage/#', '', $path);
        $path = preg_replace('#^public/#', '', $path);
        $path = ltrim($path, '/');

        if (! str_contains($path, '/')) {
            $path = 'products/thumbnails/' . $path;
        }

        return $path;
    }

    private function placeholder(): string
    {
        return 'https://placehold.co/600x600/2558a0/fff?text=No+Image';
    }
}
