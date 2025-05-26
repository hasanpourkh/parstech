<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sale_return_items', function (Blueprint $table) {
            $table->integer('qty')->default(0)->after('product_id');
        });
    }

    public function down()
    {
        Schema::table('sale_return_items', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
};
