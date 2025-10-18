<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('page_id');
            $table->string('name')->nullable();
            $table->text('page_token')->nullable();
            $table->timestamps();
            $table->index(['user_id','page_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('pages'); }
};
