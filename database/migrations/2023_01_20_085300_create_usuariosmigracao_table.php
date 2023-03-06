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
        Schema::create('usuariosmigracao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('username', 14);
            $table->string('email', 14)->nullable();
            $table->date('cpf')->nullable();
            $table->string('phone1', 50)->nullable();
            $table->string('locale', 11)->nullable();
            $table->string('user_type', 11)->nullable();
            $table->boolean('migrado');
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
        Schema::dropIfExists('usuariosmigracao');
    }
};
