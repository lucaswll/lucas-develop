@extends('layouts.app')

@section('content')
<div class="page products form">

    @include('partials.alert')

    <div class="card">
        
        <div class="card-header">{{ isEdit() ? 'Alterar produto' : 'Cadastrar produto' }}</div>

        <form method="post" action="{{ url('products') }}" data-parsley-validate>
            
            <div class="card-body">

                @csrf
                @method(isEdit() ? 'put' : 'post')
                
                <input type="hidden" name="id" value="{{ old('id', $product->id) }}" />

                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required />
                </div>

                <div class="row">
                    <div class="form-group col-sm-4 col-xs-12">
                        <label>Preço</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                            <input type="text" name="price" class="form-control money" value="{{ old('price', $product->price) }}" required min-value="0" max-value="9.999,99" data-parsley-errors-container="#price-error" />
                        </div>
                        <div id="price-error"></div>
                    </div>
                    <div class="form-group col-sm-4 col-xs-12">
                        <label>Comissão</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">%</span></div>
                            <input type="text" name="comission" class="form-control percent" value="{{ old('comission', $product->comission) }}" required min-value="0" max-value="100,00" data-parsley-errors-container="#comission-error" />
                        </div>
                        <div id="comission-error"></div>
                    </div>
                    <div class="form-group col-sm-4 col-xs-12">
                        <label>Estoque</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required min="0" max="9999" />
                    </div>
                </div>

            </div>

            <div class="card-footer text-right">
                <div class="row">
                    <div class="col-6 text-left">
                        <a href="{{ url('products') }}" class="btn btn-light">Voltar</a>
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