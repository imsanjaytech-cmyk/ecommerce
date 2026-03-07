<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('color', 20)->default('#ff4d6d');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->decimal('regular_price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->enum('tax_class', ['standard', 'reduced', 'zero'])->default('standard');
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(10);
            $table->boolean('manage_stock')->default(true);
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');
            $table->decimal('weight', 8, 3)->nullable(); 
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width',  8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('brand')->nullable();
            $table->string('thumbnail')->nullable();        
            $table->json('images')->nullable();         
            $table->string('tags')->nullable();           
            $table->enum('status', ['published', 'draft', 'scheduled'])->default('published');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('total_sales')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('category_id');
            $table->index('stock_status');
            $table->index('is_featured');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};