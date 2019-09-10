<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});

Route::get("/state/{id}/cities", "HomeController@getStateCities");

// Rotas para gerenciar produtos
Route::get		("/products",				["uses" => "ProductController@index",	"role" => "product.index",	"as" => "product"]);
Route::get		("/products/create", 		["uses" => "ProductController@create",	"role" => "product.create",	"as" => "product"]);
Route::get		("/products/{id}/edit", 	["uses" => "ProductController@edit",	"role" => "product.update",	"as" => "product"]);
Route::post		("/products",				["uses" => "ProductController@insert",	"role" => "product.create",	"as" => "product"]);
Route::put		("/products",				["uses" => "ProductController@update",	"role" => "product.update",	"as" => "product"]);
Route::delete	("/products",				["uses" => "ProductController@delete",	"role" => "product.delete",	"as" => "product"]);

// Rotas para gerenciar clientes
Route::get		("/customers",				["uses" => "CustomerController@index",	"role" => "customer.index",     "as" => "customer"]);
Route::get		("/customers/create", 		["uses" => "CustomerController@create",	"role" => "customer.create",    "as" => "customer"]);
Route::get		("/customers/{id}/edit", 	["uses" => "CustomerController@edit",	"role" => "customer.update",	"as" => "customer"]);
Route::post		("/customers",				["uses" => "CustomerController@insert",	"role" => "customer.create",	"as" => "customer"]);
Route::put		("/customers",				["uses" => "CustomerController@update",	"role" => "customer.update",	"as" => "customer"]);
Route::delete	("/customers",				["uses" => "CustomerController@delete",	"role" => "customer.delete",	"as" => "customer"]);

// Rotas para gerenciar vendas
Route::get		("/sales",				["uses" => "SaleController@index",	    "role" => "sale.index",	    "as" => "sale"]);
Route::get		("/sales/create", 		["uses" => "SaleController@create",	    "role" => "sale.create",	"as" => "sale"]);
Route::get		("/sales/{id}/details", ["uses" => "SaleController@details",    "role" => "sale.update",	"as" => "sale"]);
Route::post		("/sales",				["uses" => "SaleController@insert",     "role" => "sale.create",	"as" => "sale"]);
Route::delete	("/sales",				["uses" => "SaleController@delete",	    "role" => "sale.delete",	"as" => "sale"]);