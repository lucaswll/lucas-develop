@extends('layouts.app')

@section('content')
<div class="page sales details">

    @include('partials.alert')
    
    @php
    	$customer = $sale->customer;
    	$products = $sale->products;
    @endphp

    <div class="card">
        
    	<div class="card-header">Detalhes da venda</div>

        <div class="card-body">
        
        	<div class="row">
            	<div class="form-group col-sm-8 col-xs-12">
            		<label>Cliente</label>
            		<input type="text" class="form-control" value="{{ $customer->name }}" readonly />
            	</div>
            	<div class="form-group col-sm-4 col-xs-12">
            		<label>Data</label>
            		<input type="text" class="form-control" value="{{ formatDate($sale->datetime) }}" readonly />
            	</div>
            </div>

            <div class="row">
                <div class="form-group col-sm-4 col-xs-12">
                    <label>Total</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                        <input type="text" class="form-control" value="{{ formatNumber($sale->total) }}" readonly />
                    </div>
                </div>
                <div class="form-group col-sm-4 col-xs-6">
                    <label>Entrada</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                        <input type="text" class="form-control" value="{{ formatNumber($sale->in) }}" readonly />
                    </div>
                </div>
                <div class="form-group col-sm-4 col-xs-6">
                	<label>Saída</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                        <input type="text" class="form-control money" value="{{ formatNumber($sale->out) }}" readonly />
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">

                <table class="table table-striped products-table mb0">

                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço (R$)</th>
                            <th>Quantidade</th>
                            <th>Subtotal (R$)</th>
                        </tr>
                    </thead>

                    <tbody>
                    
                    	@php
                    		$totalQty = 0;
                    	@endphp
                    
                    	@foreach ($products as $product)
                    	
                    		@php
                    			$totalQty += $product->pivot->qty;
                    		@endphp
                    	
                        	<tr>
                        		<td>{{ $product->name }}</td>
                        		<td>{{ $product->pivot->price }}</td>
                        		<td>{{ $product->pivot->qty }}</td>
                        		<td>{{ $product->pivot->price * $product->pivot->qty }}</td>
                        	</tr>
                        	
                    	@endforeach
                    
                    </tbody>
                    
                    <tfooter>
                    	<tr>
                    		<td></td>
                    		<td></td>
                    		<td><b>{{ $totalQty }}</b></td>
                    		<td><b>{{ $sale->total }}</b></td>
                    	</tr>
                    </tfooter>

                </table>

            </div>

        </div>

        <div class="card-footer text-left">
            <a href="{{ url('sales') }}" class="btn btn-light">Voltar</a>
        </div>

    </div>

</div>
@endsection