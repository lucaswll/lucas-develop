@extends('layouts.app')

@section('content')
<div class="page sales index">

    @include('partials.alert')

    <div class="card">

        <div class="card-header">
            <div class="title">Lista de vendas</div>
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

                @if (count($sales) > 0)

                    <div class="table-responsive">

                        <table class="table table-striped">
        
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Total (R$)</th>
                                    <th>Realizada em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
        
                            <tbody>
        
                                @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ $sale->total }}</td>
                                    <td>{{ formatDate($sale->datetime) }}</td>
                                    <td>
                                        <a href="{{ url('sales/'.$sale->id.'/details') }}" class="btn btn-sm btn-link" title="Detalhes"><i class="fas fa-info"></i></a>
                                        <button type="button" class="btn btn-sm btn-link btn-delete" href="{{ url('sales') }}" data-id="{{ $sale->id }}" title="Remover"><i class="fas fa-trash"></i></button>
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

        <div class="card-footer {{ count($sales) == 0 ? 'flex-end' : '' }}">

            @if (count($sales) > 0)
            <div class="pagination-wrapper">
                    {{ $sales->appends(Request::except('page'))->links() }}
            </div>
            @endif

            <div class="controls">
                <a href="{{ url('sales/create') }}" class="btn btn-primary">Nova venda</a>
            </div>

        </div>

    </div>

</div>
@endsection