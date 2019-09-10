<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_products', function (Blueprint $table) {
           
            $table->increments('id');
            $table->unsignedInteger("sale_id");
            $table->unsignedInteger("product_id");
            $table->integer("qty");
            $table->float("price");
            $table->float("comission");
            
            $table->foreign("sale_id")->references("id")->on("sales");
            $table->foreign("product_id")->references("id")->on("products");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_products');
    }
}
