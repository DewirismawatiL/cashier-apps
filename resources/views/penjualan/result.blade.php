@extends('main')
@section('title', 'Result Member Page')
@section('breadcrumb', 'Member')
@section('page-title', 'Member')

@section('content')
<div class="container">
    <div class="mb-4">
        {{-- <button class="btn btn-primary" type="button">
            <a href="{{ route('formatpdf', $item->id) }}" class="text-white">Unduh Bukti</a>
        </button> --}}
        {{-- <button class="btn btn-primary">Unduh</button> --}}
        <a href="{{ route('formatpdf', ['id' => $transactionId]) }}" class="btn btn-primary">Unduh</a>
        <button class="btn btn-secondary"><a href="{{ route('pembelians.index') }}" class="text-white text-decoration none" >Kembali</a></button>
    </div>
    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold">Invoice - #{{ $invoiceNumber }}</h5>
            <span class="text-muted">{{ now()->format('d M Y') }}</span>
        </div>

        <table class="table border">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sellingData as $sell)
                <tr>
                    <td>{{ $sell['product_name'] }}</td>
                    <td>{{ $sell['price'] }}</td>
                    <td>{{ $sell['qty'] }}</td>
                    <td>{{ $sell['subtotal'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row mt-4">
            <div class="col-md-8">
                <table class="table border">
                    <tr>
                        <td>Poin Digunakan</td>
                        <td class="text-end">0</td>
                    </tr>
                    <tr>
                        <td>Kasir</td>
                        <td class="text-end fw-bold">{{ $userName }}</td>
                    </tr>
                    <tr>
                        <td>Kembalian</td>
                        <td class="text-end text-success fw-bold">Rp. {{ number_format($kembalian, 0, ',', '.') }}</td>
                    </tr>
                    </tr>
                </table>
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-end">
                <div class="bg-dark text-white p-3 rounded w-100">
                    <div class="small">TOTAL</div>
                    <h3 class="fw-bold text-center text-white" id="total_prices">RP. {{ number_format($totalPrice, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const totalPrice = {{ $totalPrice }};
    const totalBayarInput = document.getElementById('totalBayar');
    const kembalianInput = document.getElementById('kembalian');

    // Format angka ke dalam format Rupiah
    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);
    }

    // Hitung kembalian
    function hitungKembalian() {
        let bayarValue = totalBayarInput.value.replace(/\D/g, ''); // Hapus karakter non-angka
        let bayar = parseInt(bayarValue || '0');
        let kembali = bayar - totalPrice;

        kembalianInput.value = formatRupiah(kembali);
    }

    // Event listener untuk memformat input pembayaran
    totalBayarInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Hapus karakter non-angka
        e.target.value = formatRupiah(value);

        hitungKembalian();
    });

    // Set nilai awal untuk input pembayaran dan kembalian
    totalBayarInput.value = formatRupiah(totalPrice);
    hitungKembalian();
</script>


@endsection
