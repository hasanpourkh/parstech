<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedInteger('number'); // شماره قسط
            $table->decimal('amount', 20, 2);
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->enum('type', ['monthly', 'weekly'])->default('monthly');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('installments');
    }
}
