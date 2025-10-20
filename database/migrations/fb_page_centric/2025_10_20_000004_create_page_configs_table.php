<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('page_configs', function (Blueprint $t) {
            $t->id();
            $t->string('page_id');
            $t->foreign('page_id')->references('page_id')->on('fb_pages')->cascadeOnDelete();
            $t->json('settings')->nullable();
            $t->foreignId('updated_by')->nullable()->constrained('fb_accounts')->nullOnDelete();
            $t->timestamps();
            $t->unique('page_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('page_configs');
    }
};
