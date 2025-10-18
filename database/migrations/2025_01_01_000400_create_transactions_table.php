<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('amount');
            $table->string('type'); // deposit|purchase|refund
            $table->string('status')->default('pending'); // pending|completed|canceled
            $table->string('ref')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->index(['user_id','type','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('transactions'); }
};
