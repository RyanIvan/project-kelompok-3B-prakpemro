<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade'); // Menghubungkan ke KRS & Mata Kuliah
            $table->decimal('assignment_score', 5, 2)->nullable(); // Tugas
            $table->decimal('mid_score', 5, 2)->nullable(); // UTS
            $table->decimal('final_score', 5, 2)->nullable(); // UAS
            $table->decimal('final_grade', 5, 2)->nullable(); // Nilai Akhir
            $table->string('grade_letter')->nullable(); // Huruf Mutu (A, B, C, dll)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};