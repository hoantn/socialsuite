<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('price')->default(0);
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('plans'); }
};
