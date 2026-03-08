<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /* ── settings table ── */
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type', 30)->default('string'); 
            $table->string('group', 50)->default('general');
            $table->timestamps();
        });

        $defaults = [
            // Store
            ['key' => 'store_name',     'value' => 'Shanas',              'type' => 'string',  'group' => 'store'],
            ['key' => 'store_url',      'value' => 'shanas.com',          'type' => 'string',  'group' => 'store'],
            ['key' => 'store_email',    'value' => 'support@shanasdigital.com',    'type' => 'string',  'group' => 'store'],
            ['key' => 'store_phone',    'value' => '',                      'type' => 'string',  'group' => 'store'],
            ['key' => 'currency',       'value' => 'INR',                   'type' => 'string',  'group' => 'store'],
            ['key' => 'currency_symbol','value' => '₹',                    'type' => 'string',  'group' => 'store'],
            ['key' => 'timezone',       'value' => 'Asia/Kolkata',          'type' => 'string',  'group' => 'store'],
            ['key' => 'date_format',    'value' => 'd M Y',                 'type' => 'string',  'group' => 'store'],
            ['key' => 'store_address',  'value' => '',                      'type' => 'string',  'group' => 'store'],
            ['key' => 'notif_new_order',    'value' => '1', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'notif_low_stock',    'value' => '1', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'notif_reviews',      'value' => '0', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'notif_weekly_report','value' => '1', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'notif_security',     'value' => '1', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'notif_marketing',    'value' => '0', 'type' => 'boolean', 'group' => 'notifications'],
            // Shipping
            ['key' => 'free_shipping_threshold','value' => '999',  'type' => 'integer', 'group' => 'shipping'],
            ['key' => 'default_shipping_fee',   'value' => '99',   'type' => 'integer', 'group' => 'shipping'],
            ['key' => 'shipping_origin',        'value' => '',     'type' => 'string',  'group' => 'shipping'],
            // Appearance
            ['key' => 'primary_color',   'value' => '#ff4d6d', 'type' => 'string', 'group' => 'appearance'],
            ['key' => 'secondary_color', 'value' => '#ff8fab', 'type' => 'string', 'group' => 'appearance'],
            ['key' => 'logo_path',       'value' => '',        'type' => 'string', 'group' => 'appearance'],
        ];

        foreach ($defaults as $row) {
            DB::table('settings')->insertOrIgnore(array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_profiles');
        Schema::dropIfExists('settings');
    }
};