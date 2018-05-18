<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->char('uuid', 36);
            $table->unsignedInteger('user_id');
            $table->timestamp('device_timestamp');
            $table->enum('payment_method', ['cash', 'card']);
            $table->integer('total_amount');
            $table->integer('paid_amount');
            $table->integer('change_amount');
            $table->timestamps();
            $table->softDeletes();

            $table->index('id');
            $table->index('user_id');
            $table->index('device_timestamp', 'user_id_device_timestamp');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
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
