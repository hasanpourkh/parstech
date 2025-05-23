<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'payment_method'))
                $table->string('payment_method')->nullable()->after('id');
            if (!Schema::hasColumn('sales', 'payment_notes'))
                $table->text('payment_notes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'payment_method'))
                $table->dropColumn('payment_method');
            if (Schema::hasColumn('sales', 'payment_notes'))
                $table->dropColumn('payment_notes');
        });
    }
};
