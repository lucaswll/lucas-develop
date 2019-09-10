<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer("type_id");
            $table->string("name", 100);
            $table->string("nickname", 100)->nullable();
            $table->string("state_registration", 100)->nullable();
            $table->string("cpf", 14)->nullable();
            $table->string("cnpj", 18)->nullable();
            $table->integer("state_id");
            $table->integer("city_id");
            $table->string("district", 100)->nullable();
            $table->string("complement", 255)->nullable();
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
        Schema::dropIfExists('customers');
    }
}
