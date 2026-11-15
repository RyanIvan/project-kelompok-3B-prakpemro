<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mahasiswa terhubung ke program studi (kolom study_program lama tetap dipakai sebagai nama ringkas).
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('study_program_id')->nullable()->constrained()->nullOnDelete();
        });

        // Mata kuliah milik program studi tertentu (NULL = mata kuliah umum).
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('study_program_id')->nullable()->constrained()->nullOnDelete();
        });

        // Kelas: nama kelas (A/B/C) + kelas ini untuk program studi apa.
        Schema::table('classes', function (Blueprint $table) {
            $table->string('name')->default('A');
            $table->foreignId('study_program_id')->nullable()->constrained()->nullOnDelete();
        });

        // Nilai terhubung langsung ke mata kuliah (masukan dosen).
        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_id');
        });
        Schema::table('classes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('study_program_id');
            $table->dropColumn('name');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('study_program_id');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('study_program_id');
        });
    }
};
