<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->string('order_number')->after('id');
            $table->date('order_date')->nullable()->after('order_number');

            $table->decimal('sub_total',10,2)->default(0)->after('order_date');
            $table->decimal('tax',10,2)->default(0)->after('sub_total');
            $table->decimal('discount',10,2)->default(0)->after('tax');

            $table->decimal('paid_amount',10,2)->default(0)->after('total_amount');
            $table->decimal('due_amount',10,2)->default(0)->after('paid_amount');

            $table->text('notes')->nullable()->after('due_amount');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'order_number',
                'order_date',
                'sub_total',
                'tax',
                'discount',
                'paid_amount',
                'due_amount',
                'notes'
            ]);

        });
    }
};
