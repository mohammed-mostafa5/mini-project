<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->integer('amount')
                ->comment('The paid amount. This can be part of the total
                transaction amount. For example, the customer can
                pay the transaction on installments and each time a
                payment is recorded');

            $table->dateTime('paid_on')->comment('The date on which this payment was received');
            $table->string('details')->nullable()->comment('Additional comments that can be entered by the admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
