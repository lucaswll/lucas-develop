<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $p = Product::orderBy("id", "desc")->first();
        $id = $p->id ?? 1;

        for ($i = 0; $i < 100; $i++) {
            $product = new Product();
            $product->name = "Produto ".($id + $i + 1);
            $product->price = rand(10, 100);
            $product->comission = rand(0, 100);
            $product->stock = random_int(10, 200);
            $product->save();
        }
    }
}
