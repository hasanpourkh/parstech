<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTagsToPersonsTable extends Migration
{
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('accounting_code');
        });
    }

    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
    }
}
