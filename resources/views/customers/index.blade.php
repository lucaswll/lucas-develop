@extends('layouts.app')

@section('content')
<div class="page customers index">

    @include('partials.alert')

    <div class="card">

        <div class="card-header">
            <div class="title">Lista de clientes</div>
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

                @if (count($customers) > 0)

                    <div class="table-responsive">

                        <table class="table table-striped">
        
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>CPF / CNPJ</th>
                                    <th>Data de atualização</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
        
                            <tbody>
        
                                @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->cpf ?? $customer->cnpj }}</td>
                                    <td>{{ formatDate($customer->updated_at) }}</td>
                                    <td>
                                        
                                        <a href="{{ url('customers/'.$customer->id.'/edit') }}" class="btn btn-sm btn-link"><i class="fas fa-edit"></i></a>
                                        
                                        @if ($customer->has_sale)
                                        	<button type="button" class="btn btn-sm btn-link" title="Não é possível remover um cliente que já tenha realizado alguma compra." disabled><i class="fas fa-trash"></i></button>
                                        	
                                        @else
                                            <button type="button" class="btn btn-sm btn-link btn-delete" href="{{ url('customers') }}" data-id="{{ $customer->id }}"><i class="fas fa-trash"></i></button>
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

        <div class="card-footer {{ count($customers) == 0 ? 'flex-end' : '' }}">

            @if (count($customers) > 0)
            <div class="pagination-wrapper">
                    {{ $customers->appends(Request::except('page'))->links() }}
            </div>
            @endif

            <div class="controls">
                <a href="{{ url('customers/create') }}" class="btn btn-primary">Novo cliente</a>
            </div>

        </div>

    </div>

</div>
@endsection