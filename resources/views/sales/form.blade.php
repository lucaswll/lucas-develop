@extends('layouts.app')

@section('content')
<div class="page sales form">

    @include('partials.alert')

    <div class="card">
        
        <div class="card-header">{{ isEdit() ? 'Alterar venda' : 'Cadastrar venda' }}</div>

        <form method="post" action="{{ url('sales') }}" data-parsley-validate>
            
            <div class="card-body">

                @csrf
                @method(isEdit() ? 'put' : 'post')
                
                <div class="row">
                	<div class="col-sm-8 col-xs-12">
                    	<div class="form-group">
                            <label>Cliente</label>
                            <select name="customer_id" class="form-control selectpicker" data-live-search="true" data-size="5" title="Selecione um cliente" value="{{ old('customer_id', $sale->customer_id) }}">
                            
                            	@foreach ($customers as $c)
                            		<option value="{{ $c->id }}">{{ $c->name }}</option>
                            	@endforeach
                            
                            </select>
                        </div>
                	</div>
                	<div class="form-group col-sm-4 col-xs-12">
                		<label>Data</label>
                		<input type="text" class="form-control datetime" name="datetime" value="{{ date('d/m/Y H:i') }}" required />
                	</div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-4 col-xs-12">
                        <label>Total</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                            <input type="text" name="total" class="form-control money" value="{{ old('total', $sale->total) }}" required min-value="0" max-value="9.999,99" data-parsley-errors-container="#total-error" readonly />
                        </div>
                        <div id="total-error"></div>
                    </div>
                    <div class="form-group col-sm-4 col-xs-6">
                        <label>Entrada</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                            <input type="text" name="in" class="form-control money" value="{{ old('in', $sale->in) }}" required min-value="0" max-value="9.999,99" data-parsley-errors-container="#in-error" />
                        </div>
                        <div id="in-error"></div>
                    </div>
                    <div class="form-group col-sm-4 col-xs-6">
                    	<label>Saída</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                            <input type="text" name="out" class="form-control money" value="{{ old('out', $sale->out) }}" required min-value="0" max-value="9.999,99" data-parsley-errors-container="#out-error" readonly />
                        </div>
                        <div id="out-error"></div>
                    </div>
                </div>
                
                <div class="products-wrapper">
                
                	<div class="form-group mb0">
                        <label>Selecione um produto</label>
                        
                        <div class="input-group">
                        	 <select name="product" class="form-control selectpicker" data-live-search="true" data-size="5" title="Selecione um produto...">
                            	
                            	@foreach ($products as $p)
                            		<option value="{{ $p->id }}" data-price="{{ $p->price }}" data-name="{{ $p->name }}" data-stock="{{ $p->stock }}" data-subtext="R$ {{ $p->price }}">{{ $p->name }} ({{ $p->stock }})</option>
                            	@endforeach
                            	
                            </select>
                            <div class="input-group-append">
                            	<button class="btn btn-light btn-add-product" type="button"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="table-responsive">

                        <table class="table table-striped products-table mt20 mb0 hide">
        
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Subtotal (R$)</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
        
                            <tbody></tbody>
        
                        </table>
        
                    </div>
                
                </div>

            </div>

            <div class="card-footer text-right">
                <div class="row">
                    <div class="col-6 text-left">
                        <a href="{{ url('sales') }}" class="btn btn-light">Voltar</a>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </div>

        </form>

    </div>

</div>
@endsection