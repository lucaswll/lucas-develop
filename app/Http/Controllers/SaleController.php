<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * Sale Controller
 */
class SaleController extends Controller {
	
    /**
     * Visualizar lista de vendas
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
	public function index(Request $request) {
		
		$limit = $request->display_qty ?? 10;
		$column = $request->column ?? "updated_at";
		$sort = $request->sort ?? "desc";
		
		return view("sales.index", [
			"sales" => Sale::search($request)->orderBy($column, $sort)->paginate($limit)
		]);
	}
	
	/**
	 * Carregar formulário para criação
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function create() {
	    return $this->form(new Sale());
	}
	
	/**
	 * Visualizar os detalhes de uma venda
	 * @param int $id
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function details(int $id) {
	    
	    if ($id) {
	        
	        $sale = Sale::find($id);
	        
	        if ($sale) {
	            return view ("sales.details", [ "sale" => $sale ]);
	        }
	    }
	    
	    return redirect("sales");
	}
	
	/**
	 * Carregar formulário
	 * @param Sale $sale
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function form(Sale $sale) {
	    
	    $products = Product::orderBy("name")->get();
	    $customers = Customer::orderBy("name")->get();
	    
	    return view("sales.form", [
	        "sale" => $sale,
	        "products" => $products,
	        "customers" => $customers
	    ]);
	}
	
	/**
	 * Salvar novo venda
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function insert(Request $request) {
	    
	    $validation = $this->validation($request);
	    
	    if (!$validation->fails()) {
	        
	        if ($this->save(new Sale(), $request)) {
	            return redirect("sales");
	            
	        } else {
	            return back()->withInput();
	        }
	        
	    } else {
	        return back()->withErrors($validation)->withInput();
	    }
	}
	
	/**
	 * Validar requisição
	 * @param Request $request
	 * @return Validator
	 */
	private function validation(Request $request) {
	    
	    $validator = Validator::make($request->all(), [
	        "customer_id" => "required|numeric|exists:customers,id",
	        "datetime" => "required",
	        "in" => "required",
	        "product_id" => "required",
	        "product_qty" => "required"
	    ]);
	    
	    $validator->sometimes("id", "required|numeric|exists:sales", function($request) {
	        return $request->_method == "PUT";
	    });
	        
        $validator->after(function($validator) use ($request) {
            
            $ids = $request->product_id;
            $qty = $request->product_qty;
            
            if (count($ids) != count($qty)) {
                $validator->errors()->add("product_id", "Erro na lista de produtos.");
            
            } elseif (count($ids) > 0) {
             
                $products = Product::whereIn("id", $ids)->get();
                
                foreach ($products as $p) {
                    
                    $index = array_search($p->id, $ids);
                    $productQty = $qty[$index];
                    
                    if ($productQty > $p->stock) {
                        $validator->errors("product_qty", "Um ou mais produtos adicionados a venda ultrapassaram a quantidade disponível.");
                    }
                }
            }
            
        });
	            
        return $validator;
	}
	
	/**
	 * Salvar venda
	 * @param Sale $sale
	 * @param Request $request
	 * @return boolean
	 */
	private function save(Sale $sale, Request $request) {
	    
	    $ids = $request->product_id;
	    $products = Product::whereIn("id", $ids)->get();
	    
	    $total = 0;
	    $arrProducts = [];
	    
	    foreach ($products as $p) {
            
	        $index = array_search($p->id, $ids);
	        $tmpProduct = [
	            "id" => $p->id,
	            "price" => $p->price,
	            "comission" => $p->comission,
	            "qty" => $request->product_qty[$index]
	        ];
	        
	        array_push($arrProducts, $tmpProduct);
	        $total += ($tmpProduct["qty"] * $tmpProduct["price"]);
	        
	    }
	    
	    $sale->customer_id = $request->customer_id;
	    $sale->total = $total;
	    $sale->in = formatNumber($request->in);
	    $sale->out = $sale->in - $sale->total;
	    
	    $date = \DateTime::createFromFormat('d/m/Y H:i', $request->datetime);
	    $sale->datetime = $date->format("Y-m-d H:i:s");
	    
	    try {
	        
	        $sale->save();
	        $sale->products()->detach();
	        
	        foreach ($arrProducts as $p) {
	            
	            $sale->products()->attach($p["id"], [
	                "qty" => $p["qty"], "price" => $p["price"], "comission" => $p["comission"]
	            ]);
	        }
	        
	        return true;
	        
	    } catch (\Exception $e) {
	        Session::flash("error", "Não foi possível salvar o venda: ".$e->getMessage());
	    }
	    
	    return false;
	}
	
	/**
	 * Remover venda
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function delete(Request $request) {
	    
	    if ($request->id) {
	        
	        try {
	            
	            $sale = Sale::find($request->id);
	            
	            if ($sale) {
	                $sale->products()->detach();
	                $sale->delete();
	                
	                Session::flash("success", "Venda removida com sucesso!");
	                
	            } else {
	                Session::flash("error", "Venda não encontrada.");
	            }
	            
	        } catch (\Exception $e) {
	            Session::flash("error", "Não foi possível remover o venda: ".$e->getMessage());
	        }
	        
	    } else {
	        Session::flash("error", "Requisição para remoção de venda inválida.");
	    }
	    
	    return redirect("sales");
	}
	
}
