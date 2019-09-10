<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\State;

/**
 * Customer Controller
 */
class CustomerController extends Controller {
	
    /**
     * Visualizar lista de clientes
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
	public function index(Request $request) {
		
		$limit = $request->display_qty ?? 10;
		$column = $request->column ?? "updated_at";
		$sort = $request->sort ?? "desc";
		
		return view("customers.index", [
			"customers" => Customer::search($request)->orderBy($column, $sort)->paginate($limit)
		]);
	}
	
	/**
	 * Carregar formulário para criação
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function create() {
		return $this->form(new Customer());
	}
	
	/**
	 * Carregar formilário para edição
	 * @param int $id
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function edit(int $id) {
		
		if ($id) {
			
			$customer = Customer::find($id);
			
			if ($customer) {
				return $this->form($customer);
			}
		}
		
		Session::flash("error", "Requisição para alteração de cliente inválida.");
		return redirect("customers");
	}
	
	/**
	 * Carregar formulário
	 * @param Customer $customer
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function form(Customer $customer) {

		$types = [
			1 => "Pessoa física",
			2 => "Pessoa jurídica"
		];

		$states = State::orderBy("name")->get();

		return view("customers.form", [ "customer" => $customer, "types" => $types, "states" => $states ]);
	}
	
	/**
	 * Salvar novo cliente
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function insert(Request $request) {
		
		$validation = $this->validation($request);
		
		if (!$validation->fails()) {
			
			if ($this->save(new Customer(), $request)) {
				return redirect("customers");
				
			} else {
				return back()->withInput();
			}
			
		} else {
			return back()->withErrors($validation)->withInput();
		}
	}
	
	/**
	 * Salvar alterações em um cliente
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request) {
		
		$validation = $this->validation($request);
		
		if (!$validation->fails()) {
			
			if ($this->save(Customer::find($request->id), $request)) {
				return redirect("customers");
				
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
			"type_id" => "required|numeric|",
			"state_id" => "required|numeric|exists:states,id",
			"city_id" => "required|numeric|exists:cities,id",
	        "district" => "nullable|max:100",
	        "complement" => "nullable|max:255"
	    ]);
	    
	    $validator->sometimes("id", "required|numeric|exists:customers", function($request) {
	        return $request->_method == "PUT";
		});
		
		$validator->sometimes("cpf", "required|max:14", function($request) {
	        return $request->type_id == 1;
		});
		
		$validator->sometimes("cnpj", "required|max:18", function($request) {
	        return $request->type_id == 2;
		});
		
		$validator->sometimes("state_registration", "required|max:100", function($request) {
	        return $request->type_id == 2;
		});
		
		$validator->sometimes("nickname", "required|max:100", function($request) {
	        return $request->type_id == 2;
	    });
	    
        $validator->after(function($validator) use ($request) {
            
            $p1 = Customer::where("name", $request->name)->first();
            
            if ($p1 && (!$request->id || $p1->id != $request->id)) {
                $validator->errors()->add("name", "Esse nome já está sendo utilizado por outro cliente.");
            }
            
            $key = $request->type_id == 1 ? "cpf" : "cnpj";
            $p2 = Customer::where($key, $key == "cpf" ? $request->cpf : $request->cnpj)->first();
            
            if ($p2 && (!$request->id || $p2->id != $request->id)) {
                $validator->errors()->add($key, "Esse ".$key." já está utilizando por outro cliente.");
            }

        });
	    
	    return $validator;
	}
	
	/**
	 * Salvar cliente
	 * @param Customer $customer
	 * @param Request $request
	 * @return boolean
	 */
	private function save(Customer $customer, Request $request) {
	    
		$customer->name = $request->name;
		$customer->nickname = $request->nickname;
		$customer->state_id = $request->state_id;
		$customer->city_id = $request->city_id;
		
		$customer->type_id = $request->type_id;
		$customer->cpf = $request->type_id == 1 ? $request->cpf : null;
		$customer->cnpj = $request->type_id == 2 ? $request->cnpj : null;
		$customer->state_registration = $request->type_id == 2 ? $request->state_registration : null;

		$customer->district = $request->district ?? null;
		$customer->complement = $request->complement ?? null;
	    
	    try {
	        
	        if ($customer->save()) {
	            Session::flash("success", "Cliente salvo com sucesso!");
	        }
	        
	        return true;
	        
	    } catch (\Exception $e) {
	        Session::flash("error", "Não foi possível salvar o cliente: ".$e->getMessage());
	    }
	    
	    return false;
	}
	
	/**
	 * Remover cliente
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function delete(Request $request) {
		
		if ($request->id) {
			
			try {
			    
			    Customer::where("id", $request->id)->delete();
			    Session::flash("success", "Cliente removido com sucesso!");
				
			} catch (\Exception $e) {
				Session::flash("error", "Não foi possível remover o cliente: ".$e->getMessage());
			}
			
		} else {
			Session::flash("error", "Requisição para remoção de cliente inválida.");
		}
		
		return redirect("customers");
	}
}
