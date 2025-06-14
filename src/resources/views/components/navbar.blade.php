<nav>
    <div class="m-2 mb-5">
        <button class="btn" id="clients-btn" onclick="window.location='{{ route("view.clients") }}'" {{$current == 'clients' ? 'disabled' : ''}}>Clientes</button>
        <button class="btn" id="products-btn" onclick="window.location='{{ route("view.products") }}'" {{$current == 'products' ? 'disabled' : ''}}>Produtos</button>
        <button class="btn" id="orders-btn" onclick="window.location='{{ route("view.orders") }}'" {{$current == 'orders' ? 'disabled' : ''}}>Pedidos</button>
    </div>
</nav>
