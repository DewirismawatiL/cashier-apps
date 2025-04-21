@extends('main')
@section('title', 'Result Member Page')
@section('breadcrumb', 'Member')
@section('page-title', 'Member')

@section('content')

<div class="container mt-5">
    <div class="row">
        <!-- Bagian Produk -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataTransaction as $sell)
                            <tr>
                                <td>{{ $sell['product_name'] }}</td>
                                <td>{{ $sell['qty']  }}</td>
                                <td>{{ $sell['price'] }}</td>
                                <td>Rp.{{ $sell['subtotal'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <h5 class="fw-bold">Harga Satuan: <span class="float-end">Rp. {{ $totalBayar }}</span></h5>
                    <h5 class="fw-bold">Total Harga: <span class="float-end">Rp. {{ $subtotal }}</span></h5>
                </div>
            </div>
        </div>

        <!-- Bagian Member -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('orderMember') }}" method="post">
                            @csrf
                            @method('PATCH')
                        <label class="form-label">Nama Member (identitas)</label>
                        <input type="text" class="form-control" value="{{ $member->name ?? ''}}" name="name" placeholder="Masukkan nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="text" class="form-control" value="{{ $poinmember }}" name="poinMember" readonly>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="gunakanPoin" name="checkPoin"
                            {{ $checkPoint <= 0 ? 'disabled' : '' }}>
                        <label class="form-check-label" for="gunakanPoin">Gunakan poin</label>
                    </div>
                    <input type="hidden" class="form-control hidden" name="phone_number" value="{{ $member->phone_number ?? '' }}">
                    <input type="hidden" class="form-control hidden" name="total_bayar" value="{{ $totalBayar ?? '' }}">
                    <button class="btn btn-primary">Selanjutnya</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
