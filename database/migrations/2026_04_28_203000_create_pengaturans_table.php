<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default configurations
        DB::table('pengaturans')->insert([
            ['key' => 'app_name', 'value' => 'Sihati', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_description', 'value' => 'Informasi harga pasar harian yang akurat, transparan, dan mudah diakses.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_footer', 'value' => '&copy; 2026 Dinas Pertanian Kabupaten Muna Barat. All rights reserved.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_phone', 'value' => '08123456789', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
