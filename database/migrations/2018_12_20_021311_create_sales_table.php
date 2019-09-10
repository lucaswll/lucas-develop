<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger("customer_id");
            $table->float("total");
            $table->float("in");
            $table->float("out");
            $table->timestamp('datetime');
            $table->timestamps();
            
            $table->foreign("customer_id")->references("id")->on("customers");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
