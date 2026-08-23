<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->string('original_title')->nullable();
            $table->text('description')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->date('release_date')->nullable();
            $table->string('status');
            $table->integer('duration_minutes')->nullable();
            $table->string('age_rating')->nullable();
            $table->decimal('average_rating', 4, 2)->default(0);
            $table->unsignedBigInteger('ratings_count')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['type', 'is_published']);
        });
    }
    public function down(): void { Schema::dropIfExists('contents'); }
};