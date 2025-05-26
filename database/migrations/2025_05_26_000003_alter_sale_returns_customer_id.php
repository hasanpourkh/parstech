<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            // اگر مطمئن نیستی کلید خارجی هست یا نه، این خط را کامنت کن یا حذف کن
            // $table->dropForeign('sale_returns_customer_id_foreign');

            // اگر نیاز به تغییرات دیگر داری، اینجا بنویس
            // مثلاً اضافه کردن ستون یا تغییر نوع ستون
        });
    }

    public function down(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            // اگر لازم شد، دوباره کلید خارجی را اضافه کن
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }
};
