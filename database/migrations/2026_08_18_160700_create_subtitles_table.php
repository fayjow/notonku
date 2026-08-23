<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('subtitles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sourceable_id');
            $table->string('sourceable_type');
            $table->string('language');
            $table->string('label');
            $table->text('file_path');
            $table->timestamps();
            $table->index(['sourceable_type', 'sourceable_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('subtitles'); }
};