<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentColumnsToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // اگر ستون وجود ندارد اضافه کن
            if (!Schema::hasColumn('sales', 'cash_amount')) {
                $table->decimal('cash_amount', 20, 2)->nullable()->after('final_amount');
            }
            if (!Schema::hasColumn('sales', 'cash_reference')) {
                $table->string('cash_reference', 100)->nullable()->after('cash_amount');
            }
            if (!Schema::hasColumn('sales', 'cash_paid_at')) {
                $table->timestamp('cash_paid_at')->nullable()->after('cash_reference');
            }
            if (!Schema::hasColumn('sales', 'card_amount')) {
                $table->decimal('card_amount', 20, 2)->nullable()->after('cash_paid_at');
            }
            if (!Schema::hasColumn('sales', 'card_number')) {
                $table->string('card_number', 50)->nullable()->after('card_amount');
            }
            if (!Schema::hasColumn('sales', 'card_bank')) {
                $table->string('card_bank', 100)->nullable()->after('card_number');
            }
            if (!Schema::hasColumn('sales', 'card_reference')) {
                $table->string('card_reference', 100)->nullable()->after('card_bank');
            }
            if (!Schema::hasColumn('sales', 'card_paid_at')) {
                $table->timestamp('card_paid_at')->nullable()->after('card_reference');
            }
            if (!Schema::hasColumn('sales', 'pos_amount')) {
                $table->decimal('pos_amount', 20, 2)->nullable()->after('card_paid_at');
            }
            if (!Schema::hasColumn('sales', 'pos_terminal')) {
                $table->string('pos_terminal', 100)->nullable()->after('pos_amount');
            }
            if (!Schema::hasColumn('sales', 'pos_reference')) {
                $table->string('pos_reference', 100)->nullable()->after('pos_terminal');
            }
            if (!Schema::hasColumn('sales', 'pos_paid_at')) {
                $table->timestamp('pos_paid_at')->nullable()->after('pos_reference');
            }
            if (!Schema::hasColumn('sales', 'online_amount')) {
                $table->decimal('online_amount', 20, 2)->nullable()->after('pos_paid_at');
            }
            if (!Schema::hasColumn('sales', 'online_transaction_id')) {
                $table->string('online_transaction_id', 100)->nullable()->after('online_amount');
            }
            if (!Schema::hasColumn('sales', 'online_reference')) {
                $table->string('online_reference', 100)->nullable()->after('online_transaction_id');
            }
            if (!Schema::hasColumn('sales', 'online_paid_at')) {
                $table->timestamp('online_paid_at')->nullable()->after('online_reference');
            }
            if (!Schema::hasColumn('sales', 'cheque_amount')) {
                $table->decimal('cheque_amount', 20, 2)->nullable()->after('online_paid_at');
            }
            if (!Schema::hasColumn('sales', 'cheque_number')) {
                $table->string('cheque_number', 100)->nullable()->after('cheque_amount');
            }
            if (!Schema::hasColumn('sales', 'cheque_bank')) {
                $table->string('cheque_bank', 100)->nullable()->after('cheque_number');
            }
            if (!Schema::hasColumn('sales', 'cheque_due_date')) {
                $table->date('cheque_due_date')->nullable()->after('cheque_bank');
            }
            if (!Schema::hasColumn('sales', 'cheque_status')) {
                $table->string('cheque_status', 50)->nullable()->after('cheque_due_date');
            }
            if (!Schema::hasColumn('sales', 'cheque_received_at')) {
                $table->timestamp('cheque_received_at')->nullable()->after('cheque_status');
            }
            if (!Schema::hasColumn('sales', 'status')) {
                $table->string('status', 20)->nullable()->after('cheque_received_at');
            }
            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('status');
            }
            if (!Schema::hasColumn('sales', 'payment_notes')) {
                $table->text('payment_notes')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('sales', 'paid_amount')) {
                $table->decimal('paid_amount', 20, 2)->nullable()->after('payment_notes');
            }
            if (!Schema::hasColumn('sales', 'remaining_amount')) {
                $table->decimal('remaining_amount', 20, 2)->nullable()->after('paid_amount');
            }
            if (!Schema::hasColumn('sales', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('remaining_amount');
            }
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'cash_amount', 'cash_reference', 'cash_paid_at',
                'card_amount', 'card_number', 'card_bank', 'card_reference', 'card_paid_at',
                'pos_amount', 'pos_terminal', 'pos_reference', 'pos_paid_at',
                'online_amount', 'online_transaction_id', 'online_reference', 'online_paid_at',
                'cheque_amount', 'cheque_number', 'cheque_bank', 'cheque_due_date', 'cheque_status', 'cheque_received_at',
                'status', 'payment_method', 'payment_notes', 'paid_amount', 'remaining_amount', 'paid_at'
            ]);
        });
    }
}
