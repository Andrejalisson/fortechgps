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
        Schema::create('enterprise', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('fantasy_name', 100)->nullable();
            $table->string('cnpj', 14);
            $table->string('email', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobilePhone', 20)->nullable();
            $table->string('theft_emergency_tel', 20)->nullable();
            $table->string('assistance_emergency_tel', 20)->nullable();
            $table->string('postalCode', 8)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('addressNumber', 6)->nullable();
            $table->string('complement', 50)->nullable();
            $table->string('province', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('assas_id', 20)->nullable();
            $table->string('softruck_id', 40)->nullable();
            $table->text('observations')->nullable()->default('Sem observações');
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
        Schema::dropIfExists('enterprise');
    }
};
