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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->year('tahun');
            $table->unsignedTinyInteger('bulan');
            $table->decimal('gaji_pokok', 15, 2);
            $table->integer('jumlah_kehadiran')->default(0);
            $table->integer('jumlah_cuti')->default(0);
            $table->decimal('insentif_kehadiran', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('total_gaji', 15, 2)->default(0);
            $table->string('status')->default('pending'); // atau 'dibayar'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
