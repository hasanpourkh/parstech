<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('کد حساب');
            $table->string('name')->comment('عنوان حساب');
            $table->enum('type', ['asset', 'liability', 'equity', 'income', 'expense'])->comment('نوع حساب');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('حساب والد');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('accounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
