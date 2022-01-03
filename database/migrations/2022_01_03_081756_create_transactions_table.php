<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained('categories')->comment('select subcategory for transaction');
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories');
            $table->foreignId('payer_id')->constrained('users')->comment('The customer who will pay the given amount');
            $table->integer('amount')->comment('The total amount of the transaction');
            $table->dateTime('due_on')->comment('The date on which the customer should pay');
            $table->integer('vat')->comment('The VAT percentage');
            $table->boolean('is_vat_inclusive')->comment('Is the VAT amount included in the entered amount');
            $table->unsignedTinyInteger('status')
                        ->default(0)
                        ->comment(' 0 => Pending, 1 => Paid, 2 => Outstanding, 3 => Overdue');

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
        Schema::dropIfExists('transactions');
    }
}
