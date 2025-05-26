<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('note');

            // اگر نیاز داری که به users وصل بشه (کلید خارجی)، خط زیر رو هم فعال کن:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            // اگر کلید خارجی گذاشتی باید اول dropForeign بزنی
            // $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
