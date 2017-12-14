<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceIdColumnToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('netcore_payment__payments', function (Blueprint $table) {

            $table->unsignedInteger('invoice_id')
                  ->after('id')
                  ->nullable();


            $table->foreign('invoice_id')
                  ->references('id')
                  ->on('netcore_invoice__invoices')
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
        Schema::table('netcore_payment__payments', function (Blueprint $table) {

            $table->dropForeign(['invoice_id']);

            $table->dropColumn('invoice_id');

        });
    }
}
