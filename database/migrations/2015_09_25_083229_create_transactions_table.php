<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// таблица транзакций
class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function ($table) {
                    $table->increments('id');
                    $table->integer('user_id'); // какому пользователю принадлежит транзакция
                    $table->integer('from_id')->nullable(); // от какого пользователя был сделан перевод
                    $table->integer('to_id')->nullable(); // от кому был сделан перевод
                    $table->integer('card_id'); // кошелек на который переводятся деньги
                    $table->double('money', 15, 2); // сумма
                    $table->string('comment', 255)->nullable();
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
        Schema::drop('transactions');
    }
}
