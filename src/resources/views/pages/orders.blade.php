@extends('layouts.app')

@section('content')
    <x-navbar :current="'orders'"/>
    <div class="mx-5">
        <form id="filter-form">
            <div class="row">
                <div class="input-group col">
                    <input type="text" class="form-control" placeholder="Buscar por número do pedido, cliente, produto, valor unitário, quantidade ou desconto" id="filter-by-value">
                </div>
                <div class="input-group col">
                    <select type="text" class="form-control" id="filter-by-status">
                        <option value="" hidden selected>Filtrar por status</option>
                        <option value="">Em aberto</option>
                        <option value="">Cancelado</option>
                        <option value="">Pago</option>
                    </select>
                </div>
                <div class="input-group col">
                    <input type="date" class="form-control" placeholder="Buscar por data" id="filter-by-date">
                </div>

            </div>
            <div class="d-flex justify-content-center mt-2">
                <button class="btn btn-outline-primary" style="width: 100px">Filtrar</button>
            </div>
        </form>
    </div>
@endsection
