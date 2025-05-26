<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->string('return_number')->unique();
            $table->dateTime('return_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('note')->nullable();
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            // اگر می‌خواهی user_id کلید خارجی به users باشد این را هم فعال کن:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
