<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->integer('episode_number');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->date('release_date')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['season_id', 'episode_number']);
            $table->index(['season_id', 'is_published', 'episode_number'], 'episodes_season_pub_ep_num_idx');
        });
    }
    public function down(): void { Schema::dropIfExists('episodes'); }
};