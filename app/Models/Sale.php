<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
    
    public function products() {
        return $this->belongsToMany("App\Models\Product", "sales_products")
        ->withPivot("qty", "price", "comission")
        ->orderBy("name");
    }
    
    public function customer() {
        return $this->belongsTo("App\Models\Customer");
    }

    public function scopeSearch($query, $request = null) {
        
        $query->join("customers as c", "c.id", "sales.customer_id");
        $query->select("sales.*", "c.name as customer_name");
        
        if ($request) {
            
            if ($request->search) {
                $query->where("c.name", "like", "%".$request->search."%");
            }
        }

        return $query;
    }
}
