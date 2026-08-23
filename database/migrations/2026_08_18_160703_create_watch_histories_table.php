<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('watch_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignId('episode_id')->nullable()->constrained('episodes')->cascadeOnDelete();
            $table->integer('progress_seconds')->default(0);
            $table->integer('duration_seconds')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_watched_at')->useCurrent();
            $table->timestamps();
            $table->index(['user_id', 'content_id']);
            $table->index(['user_id', 'episode_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('watch_histories'); }
};