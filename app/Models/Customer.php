<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model {

    public function scopeSearch($query, $request = null) {
        
        $query->select("customers.*",
            DB::raw('coalesce((select true from sales as s where s.customer_id = customers.id limit 1), false) as has_sale')
        );

        if ($request) {
            
            if ($request->search) {
                $query->where("name", "like", "%".$request->search."%");
            }
        }

        return $query;
    }
}
