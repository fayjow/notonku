<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->integer('season_number');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('poster_path')->nullable();
            $table->timestamps();
            $table->unique(['content_id', 'season_number']);
        });
    }
    public function down(): void { Schema::dropIfExists('seasons'); }
};