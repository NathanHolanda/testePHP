<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js', 'resources/js/tables.js'])
</head>
<body>
    <div id="app">
        <nav>
            <div class="m-2">
                <button class="btn" id="clients-btn" onclick="window.location='{{ route("view.clients") }}'">Clientes</button>
                <button class="btn" id="products-btn" onclick="window.location='{{ route("view.products") }}'">Produtos</button>
                <button class="btn" id="orders-btn" onclick="window.location='{{ route("view.orders") }}'">Pedidos</button>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
            <div class="mx-5 my-2">
                <table id="table" class="table table-hover m-auto mb-2"><thead></thead><tbody></tbody></table>
                <div class="w-100 d-flex justify-content-center">
                    <nav aria-label="..."><ul class="pagination m-auto" id="pagination"></ul></nav>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
