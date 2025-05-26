<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_sale_id'); // فاکتور فروش خدمت
            $table->unsignedBigInteger('service_id');      // خدمت انتخاب‌شده
            $table->bigInteger('price')->default(0);       // مبلغ هر خدمت
            $table->integer('quantity')->default(1);       // تعداد خدمت
            $table->text('note')->nullable();              // توضیحات
            $table->timestamps();

            $table->foreign('service_sale_id')
                  ->references('id')->on('service_sales')
                  ->onDelete('cascade');

            $table->foreign('service_id')
                  ->references('id')->on('services')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_sale_items');
    }
};
