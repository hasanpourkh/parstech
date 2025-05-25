<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnNumberToSaleReturnsTable extends Migration
{
    public function up()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->string('return_number')->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->dropColumn('return_number');
        });
    }
}
