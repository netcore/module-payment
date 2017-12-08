<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id');

            $table->unsignedInteger('plan_id')
                ->nullable();

            $table->string('name')
                ->index();

            $table->string('braintree_id')
                ->index();

            $table->string('braintree_plan')
                ->index();

            $table->integer('quantity');

            $table->timestamp('trial_ends_at')
                ->nullable();

            $table->timestamp('ends_at')
                ->nullable();

            $table->timestamps();


            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('plan_id')
                ->references('id')
                ->on('netcore_subscription__plans')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
