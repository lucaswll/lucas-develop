@extends('layouts.app')

@section('content')
<div class="page products index">

    @include('partials.alert')

    <div class="card">

        <div class="card-header">
            <div class="title">Lista de produtos</div>
            <div class="search-wrapper">
                <form method="get" action="">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ Request::get('search') }}"/>
                        <div class="input-group-append"><button type="submit" class="btn btn-light"><i class="fa fa-search"></i></button></div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body no-padding">

            <div class="table-area">

                @if (count($products) > 0)

                    <div class="table-responsive">

                        <table class="table table-striped">
        
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Preço (R$)</th>
                                    <th>Comissão (%)</th>
                                    <th>Data de atualização</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
        
                            <tbody>
        
                                @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->comission }}</td>
                                    <td>{{ formatDate($product->updated_at) }}</td>
                                    <td>
                                    
                                        <a href="{{ url('products/'.$product->id.'/edit') }}" class="btn btn-sm btn-link" title="Editar"><i class="fas fa-edit"></i></a>
                                        
                                        @if ($product->has_sale)
                                        	<button type="button" class="btn btn-sm btn-link" title="Não é possível remover um produto que já tenha sido vendido." disabled><i class="fas fa-trash"></i></button>
                                        	
                                        @else
                                            <button type="button" class="btn btn-sm btn-link btn-delete" href="{{ url('products') }}" data-id="{{ $product->id }}" title="Remover"><i class="fas fa-trash"></i></button>
                                        @endif
                                        
                                    </td>
                                </tr>
                                @endforeach
        
                            </tbody>
        
                        </table>
        
                    </div>

				@else
					<div class="note">Nenhum registro foi encontrado.</div>
                @endif

            </div>

        </div>

        <div class="card-footer {{ count($products) == 0 ? 'flex-end' : '' }}">

            @if (count($products) > 0)
            <div class="pagination-wrapper">
                    {{ $products->appends(Request::except('page'))->links() }}
            </div>
            @endif

            <div class="controls">
                <a href="{{ url('products/create') }}" class="btn btn-primary">Novo produto</a>
            </div>

        </div>

    </div>

</div>
@endsection