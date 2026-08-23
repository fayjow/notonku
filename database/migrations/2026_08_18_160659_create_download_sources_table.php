<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('download_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sourceable_id');
            $table->string('sourceable_type');
            $table->string('provider');
            $table->text('url');
            $table->string('quality');
            $table->string('server_name');
            $table->unsignedBigInteger('file_size_bytes')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['sourceable_type', 'sourceable_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('download_sources'); }
};