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
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('cpfCnpj', 14);
            $table->string('rg', 14)->nullable();
            $table->date('birthDate')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('mobilePhone', 11)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('addressNumber', 10)->nullable();
            $table->string('complement', 30)->nullable();
            $table->string('province', 30)->nullable();
            $table->string('postalCode', 8)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('emergencyContact', 100)->nullable();
            $table->string('emergencyContactMobilePhone', 11)->nullable();
            $table->string('externalReference', 100)->nullable();
            $table->boolean('notificationDisabled')->nullable()->default(true);
            $table->string('municipalInscription', 20)->nullable();
            $table->string('stateInscription', 20)->nullable();
            $table->text('observations')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('clientes');
    }
};
