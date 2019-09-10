@extends('layouts.app')

@section('content')
<div class="page customers form">

    @include('partials.alert')

    <div class="card">
        
        <div class="card-header">{{ isEdit() ? 'Alterar cliente' : 'Cadastrar cliente' }}</div>

        <form method="post" action="{{ url('customers') }}" data-parsley-validate>
            
            <div class="card-body">

                @csrf
                @method(isEdit() ? 'put' : 'post')

                @php
                    $selectedType = old('type_id', $customer->type_id) ?? 1;
                @endphp

                <div class="row">
                    <div class="col-sm-6 col-xs-12 form-group">
                        <label>Tipo do cliente</label>
                        <select name="type_id" class="form-control" required>
                            
                            @foreach ($types as $value => $type)
                                <option value="{{ $value }}" {{ $value == $selectedType ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-sm-6 col-xs-12">

                        <div class="form-group cpf-group {{ $selectedType == 2 ? 'hide' : '' }}">
                            <label>CPF</label>
                            <input type="text" name="cpf" class="form-control cpf" value="{{ old('cpf', $customer->cpf) }}" {{ $selectedType == 1 ? 'required' : '' }} /> 
                        </div>

                        <div class="form-group cnpj-group {{ $selectedType == 1 ? 'hide' : '' }}">
                            <label>CNPJ</label>
                            <input type="text" name="cnpj" class="form-control cnpj" value="{{ old('cpf', $customer->cpf) }}" {{ $selectedType == 2 ? 'required' : '' }} /> 
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <label>{{ $selectedType == 1 ? 'Nome' : 'Razão social' }}</label>
                    <input type="text" name="name" class="form-control" maxlength="100" value="{{ old('name', $customer->name) }}" required />
                </div>

                <div class="row cnpj-group {{ $selectedType == 1 ? 'hide' : '' }}">
                    <div class="col-sm-6 col-xs-12 form-group">
                        <label>Nome fantasia</label>
                        <input type="text" name="nickname" class="form-control" maxlength="100" value="{{ old('nickname', $customer->nickname) }}" {{ $selectedType == 2 ? 'required' : '' }} />
                    </div>
                    <div class="col-sm-6 col-xs-12 form-group">
                        <label>Inscrição estadual</label>
                        <input type="text" name="state_registration" class="form-control" maxlength="100" value="{{ old('state_registration', $customer->state_registration) }}" {{ $selectedType == 2 ? 'required' : '' }} />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4 col-xs-12 form-group">
                        <label>Estado</label>
                        <select name="state_id" class="form-control selectpicker" data-live-search="true" data-size="5" required>

                            <option value="">Selecione um valor</option>

                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ $state->id == old('state_id', $customer->state_id) ? 'selected' : '' }}>{{ $state->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-sm-4 col-xs-12 form-group">
                        <label>Cidade</label>
                        <select name="city_id" class="form-control selectpicker" data-live-search="true" data-size="5" data-id="{{ old('city_id', $customer->city_id) }}" required>
                            <option value="">Selecione um estado</option>
                        </select>
                    </div>

                    <div class="col-sm-4 col-xs-12 form-group">
                        <label>Bairro</label>
                        <input type="text" name="district" class="form-control" maxlength="100" value="{{ old('district', $customer->district) }}" />
                    </div>
                </div>
                
                <div class="form-group">
                	<label>Endereço</label>
                	<input type="text" name="complement" class="form-control" maxlength="255" value="{{ old('complement', $customer->complement) }}" />
                </div>

            </div>

            <div class="card-footer text-right">
                <div class="row">
                    <div class="col-6 text-left">
                        <a href="{{ url('customers') }}" class="btn btn-light">Voltar</a>
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