<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinancialFieldsToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'total_amount')) {
                $table->bigInteger('total_amount')->default(0)->after('tax_percent');
            }
            if (!Schema::hasColumn('invoices', 'final_amount')) {
                $table->bigInteger('final_amount')->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('invoices', 'profit')) {
                $table->bigInteger('profit')->default(0)->after('final_amount');
            }
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'profit')) {
                $table->dropColumn('profit');
            }
            if (Schema::hasColumn('invoices', 'final_amount')) {
                $table->dropColumn('final_amount');
            }
            if (Schema::hasColumn('invoices', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
        });
    }
}
