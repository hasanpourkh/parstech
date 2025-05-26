<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterReturnDateNullableInSaleReturns extends Migration
{
    public function up(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->dateTime('return_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            // اگر لازم بود دوباره به حالت قبلی برگردد:
            // $table->dateTime('return_date')->nullable(false)->change();
        });
    }
}
