<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id'); // به چه خدمت تعلق دارد
            $table->string('label'); // عنوان (مثلاً: کد ملی)
            $table->string('name'); // نام انگلیسی فیلد (مثلاً: national_id)
            $table->string('type'); // نوع فیلد (text, number, date, file, image, select, ...)
            $table->boolean('required')->default(false); // الزامی بودن
            $table->text('options')->nullable(); // گزینه‌ها برای select (فرمت json)
            $table->integer('order')->default(0); // ترتیب نمایش
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_fields');
    }
};
