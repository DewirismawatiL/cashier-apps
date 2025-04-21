    @extends('main')
    @section('title', 'Pembelian')
    @section('breadcrumb', 'Tambah Transaksi')
    @section('page-title', 'Tambah Transaksi')

    @section('content')
    <div class="container mt-4">
        <div class="row g-4">
            @foreach ($products as $product)
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card h-100 text-center p-3">
                    <img src="{{ asset('image/'. $product->image) }}" class="card-img-top mx-auto"
                        style="width: 170px; height: 170px; object-fit: cover;" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted">Stok: <span id="stock-{{ $product->id }}">{{ $product->stock }}</span></p>
                        <h6 class="fw-bold">Rp. {{ number_format($product->price, 0, ',', '.') }}</h6>
                        <div class="d-flex justify-content-center align-items-center mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-decrease" data-id="{{ $product->id }}">-</button>
                            <span class="mx-3 qty" id="qty-{{ $product->id }}">0</span>
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-increase"
                                data-id="{{ $product->id }}"
                                data-stock="{{ $product->stock }}"
                                data-price="{{ $product->price }}">+</button>
                        </div>
                        <p class="mt-3">Sub Total: <span class="fw-bold subtotal" id="subtotal-{{ $product->id }}">Rp. 0</span></p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <form action="{{ route('store.cart') }}" method="POST" id="cartForm">
                @csrf
                <input type="hidden" name="cart_data" id="cartData">
                <button type="submit" class="btn btn-primary">Selanjutnya</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-increase, .btn-decrease').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    let productId = this.getAttribute('data-id');
                    let qtyElement = document.getElementById(`qty-${productId}`);
                    let subtotalElement = document.getElementById(`subtotal-${productId}`);
                    let stock = parseInt(this.getAttribute('data-stock'));
                    let price = parseInt(this.getAttribute('data-price'));
                    let currentQty = parseInt(qtyElement.innerText);

                    let newQty = this.classList.contains('btn-increase')
                        ? Math.min(currentQty + 1, stock)
                        : Math.max(currentQty - 1, 0);

                    qtyElement.innerText = newQty;
                    subtotalElement.innerText = `Rp. ${(newQty * price).toLocaleString('id-ID')}`;
                });
            });

            document.getElementById('cartForm').addEventListener('submit', function (event) {
                let cartData = {};
                document.querySelectorAll('.qty').forEach(qtyElement => {
                    let productId = qtyElement.id.split('-')[1];
                    let quantity = parseInt(qtyElement.innerText);
                    if (quantity > 0) {
                        cartData[productId] = quantity;
                    }
                });
                document.getElementById('cartData').value = JSON.stringify(cartData);
            });
        });
    </script>
    @endsection
