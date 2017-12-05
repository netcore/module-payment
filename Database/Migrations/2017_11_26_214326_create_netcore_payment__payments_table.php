<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcorePaymentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_payment__payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->decimal('amount');
            $table->enum('state', [
                'successful', 'failed', 'in_process'
            ])->default('in_process');
            $table->enum('status', [
                'active', 'closed'
            ]);
            $table->enum('method', [
                'paypal', 'creditcard', 'sms', 'braintree'
            ]);
            $table->boolean('is_active');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('netcore_payment__payments');
    }
}
