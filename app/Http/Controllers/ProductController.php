<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

/**
 * Product Controller
 */
class ProductController extends Controller {
	
    /**
     * Visualizar lista de produtos
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
	public function index(Request $request) {
		
		$limit = $request->display_qty ?? 10;
		$column = $request->column ?? "updated_at";
		$sort = $request->sort ?? "desc";
		
		return view("products.index", [
			"products" => Product::search($request)->orderBy($column, $sort)->paginate($limit)
		]);
	}
	
	/**
	 * Carregar formulário para criação
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function create() {
		return $this->form(new Product());
	}
	
	/**
	 * Carregar formilário para edição
	 * @param int $id
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function edit(int $id) {
		
		if ($id) {
			
			$product = Product::find($id);
			
			if ($product) {
				return $this->form($product);
			}
		}
		
		Session::flash("error", "Requisição para alteração de produto inválida.");
		return redirect("products");
	}
	
	/**
	 * Carregar formulário
	 * @param Product $product
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function form(Product $product) {
		return view("products.form", [ "product" => $product ]);
	}
	
	/**
	 * Salvar novo produto
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function insert(Request $request) {
		
		$validation = $this->validation($request);
		
		if (!$validation->fails()) {
			
			if ($this->save(new Product(), $request)) {
				return redirect("products");
				
			} else {
				return back()->withInput();
			}
			
		} else {
			return back()->withErrors($validation)->withInput();
		}
	}
	
	/**
	 * Salvar alterações em um produto
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request) {
		
		$validation = $this->validation($request);
		
		if (!$validation->fails()) {
			
			if ($this->save(Product::find($request->id), $request)) {
				return redirect("products");
				
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
	        "name" => "required|max:100",
	        "price" => "required|min:0",
	        "comission" => "required|min:0|max:100",
	        "stock" => "required|min:0"
	    ]);
	    
	    $validator->sometimes("id", "required|numeric|exists:products", function($request) {
	        return $request->_method == "PUT";
	    });
	    
        $validator->after(function($validator) use ($request) {

			$product = Product::where("name", $request->name)->first();

			if ($product && (!$request->id || $product->id != $request->id)) {
				$validator->errors()->add("name", "Este nome já está sendo utilizado por outro produto.");
			}

        });
	    
	    return $validator;
	}
	
	/**
	 * Salvar produto
	 * @param Product $product
	 * @param Request $request
	 * @return boolean
	 */
	private function save(Product $product, Request $request) {
	    
	    $product->name = $request->name;
	    $product->price = formatNumber($request->price);
	    $product->comission = formatNumber($request->comission);
	    $product->stock = $request->stock;
	    
	    try {
	        
	        if ($product->save()) {
	            Session::flash("success", "Produto salvo com sucesso!");
	        }
	        
	        return true;
	        
	    } catch (\Exception $e) {
	        Session::flash("error", "Não foi possível salvar o produto: ".$e->getMessage());
	    }
	    
	    return false;
	}
	
	/**
	 * Remover produto
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function delete(Request $request) {
		
		if ($request->id) {
			
			try {
			    
			    Product::where("id", $request->id)->delete();
			    Session::flash("success", "Produto removido com sucesso!");
				
			} catch (\Exception $e) {
				Session::flash("error", "Não foi possível remover o produto: ".$e->getMessage());
			}
			
		} else {
			Session::flash("error", "Requisição para remoção de produto inválida.");
		}
		
		return redirect("products");
	}
}
