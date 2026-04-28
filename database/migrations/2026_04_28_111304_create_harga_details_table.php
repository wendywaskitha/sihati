<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('harga_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komoditas_id')->constrained('komoditas')->onDelete('cascade');
            $table->foreignId('pedagang_id')->constrained('pedagangs')->onDelete('cascade');
            $table->foreignId('pasar_id')->constrained('pasars')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('harga', 12, 2);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_details');
    }
};
