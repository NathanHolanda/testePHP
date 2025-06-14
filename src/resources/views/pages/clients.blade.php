@extends('layouts.app')

@section('content')
    <x-navbar :current="'clients'"/>
    <div class="mx-5">
        <form id="filter-form">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar por nome, sobrenome, email ou CPF" id="filter-by-value">
            </div>
            <div class="d-flex justify-content-center mt-2">
                <button class="btn btn-outline-primary" style="width: 100px">Filtrar</button>
            </div>
        </form>
    </div>
@endsection
