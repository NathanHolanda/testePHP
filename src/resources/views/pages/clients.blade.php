@extends('layouts.app')

@section('content')
    <x-navbar :current="'clients'"/>
    <div class="mx-5">
        <form action="">
            <div class="input-group ">
                <input type="text" class="form-control" placeholder="Buscar por nome, sobrenome, email ou CPF">
            </div>
        </form>
    </div>
@endsection
