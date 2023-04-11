<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobrancas', function (Blueprint $table) {
            $table->id();
            $table->integer('cliente_id')->unsigned()->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes')->nullable();
            $table->string('externalReference', 25);
            $table->string('paymentLink',100)->nullable();
            $table->string('billingType', 12)->default('UNDEFINED');
            $table->float('value')->default(0.00);
            $table->float('netValue')->nullable();
            $table->float('grossValue')->nullable();
            $table->float('originalValue')->nullable();
            $table->float('interestValue')->nullable();
            $table->string('pixTransaction',100)->nullable();
            $table->string('status',30);
            $table->date('dueDate');
            $table->date('originalDueDate');
            $table->date('paymentDate')->nullable();
            $table->date('clientPaymentDate')->nullable();
            $table->string('installmentNumber',2)->nullable();
            $table->string('invoiceUrl',50)->nullable();
            $table->string('invoiceNumber',15)->nullable();
            $table->boolean('deleted')->nullable();
            $table->boolean('anticipated')->nullable();
            $table->boolean('anticipable')->nullable();
            $table->date('creditDate')->nullable();
            $table->date('estimatedCreditDate')->nullable();
            $table->string('transactionReceiptUrl',100)->nullable();
            $table->string('nossoNumero',30)->nullable();
            $table->string('bankSlipUrl',100)->nullable();
            $table->text('description')->default('Rastreamento veicular')->nullable();
            $table->boolean('postalService')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cobrancas');
    }
};
