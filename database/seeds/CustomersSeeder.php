<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersSeeder extends Seeder {
      
    public function run() {
        
        $customers = factory(Customer::class, 100)->make();

        foreach ($customers as $customer) {
            $customer->save();
        }
    }
}
