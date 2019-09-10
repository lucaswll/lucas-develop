<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model {

    public function scopeSearch($query, $request = null) {
        
        $query->select("products.*",
            DB::raw('coalesce((select true from sales_products as sp where sp.product_id = products.id limit 1), false) as has_sale')
        );

        if ($request) {
            
            if ($request->search) {
                $query->where("name", "like", "%".$request->search."%");
            }
        }

        return $query;
    }
}
