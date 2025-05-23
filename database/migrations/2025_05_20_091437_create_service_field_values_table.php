<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_field_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_sale_item_id'); // به کدام آیتم فروش تعلق دارد
            $table->unsignedBigInteger('service_field_id');     // کدام فیلد
            $table->text('value')->nullable();                  // مقدار (متن، لینک فایل و ...)
            $table->timestamps();

            $table->foreign('service_sale_item_id')->references('id')->on('service_sale_items')->onDelete('cascade');
            $table->foreign('service_field_id')->references('id')->on('service_fields')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_field_values');
    }
};
