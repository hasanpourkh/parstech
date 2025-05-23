<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->decimal('share_percent', 5, 2)->nullable()->after('type');
        });
    }
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('share_percent');
        });
    }
};
