@extends('main')
@section('title', 'Pembelian')
@section('breadcrumb', 'Pembelian')
@section('page-title', 'Pembelian')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <button class="btn btn-primary"><a href="{{ route('formatexcel') }}" class="text-white">Export Pembelian
                        (.xlsx)</a></button>
            </div>
            @if (Auth::user()->role == 'kasir')
                <div>
                    <a class="btn btn-success" href="{{ route('pembelians.create') }}">Tambah Pembelian</a>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="dropdown me-2">
                Tampilkan
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    10
                </button>
                Entri
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">10</a></li>
                    <li><a class="dropdown-item" href="#">15</a></li>
                    <li><a class="dropdown-item" href="#">20</a></li>
                </ul>
            </div>
            <div>
                <form method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Cari..."
                        value="{{ request('search') }}">
                </form>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="text-center">No</th>
                    <th scope="col" class="text-center">Nama Pelanggan</th>
                    <th scope="col" class="text-center">Tanggal Pembelian</th>
                    <th scope="col" class="text-center">Total Harga</th>
                    <th scope="col" class="text-center">Dibuat Oleh</th>
                    <th scope="col" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction as $key => $item)
                    <tr>
                        <th scope="row" class="text-center">{{ $key + 1 }}</th>
                        <td class="text-center">
                            {{ $item->member ? $item->member->name : 'Non Member' }}
                        </td>
                        <td class="text-center">{{ $item->created_at->format('Y M d') }}</td>
                        <td class="text-center">{{ 'Rp. ' . number_format( $item->total_price, 0, ',', '.')}}</td>
                        <td class="text-center">{{ $item->user->name }}</td>
                        <td class="text-center">
                            <div class="d-grid gap-4 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalDetail{{ $item->id }}">Lihat</button>
                                <button class="btn btn-primary" type="button">
                                    <a href="{{ route('formatpdf', $item->id) }}" class="text-white">Unduh Bukti</a>
                                </button>
                            </div>
                        </td>
                @endforeach
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                Menampilkan 1 hingga 10 dari 100 entri
            </div>
            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    @foreach ($transaction as $item)
        <!-- Modal Detail Penjualan -->
        <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1"
            aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailLabel{{ $item->id }}">Detail Pembelian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <p>Member Status : <strong>{{ $item->member ? 'Member' : 'Non Member' }}</strong></p>
                            <p>No. HP : {{ $item->member->phone_number ?? '-' }}</p>
                            <p>Poin Member : {{ $item->member->poin_member ?? '-' }}</p>
                            <p>Bergabung Sejak :
                                {{ $item->member
                                    ? \Carbon\Carbon::parse($item->member->created_at)->format('d
                                                        F Y')
                                    : '-' }}
                            </p>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->details as $detail)
                                    <tr>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        <td>Rp. {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($detail->product->price * $detail->qty, 0, ',', '.') }}
                                        </td>
                                @endforeach
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td><strong>{{'Rp. ' . number_format($item->total_price, 0, ',', '.' )}}</strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        <p class="mt-3 text-muted"><small>Dibuat pada : {{ $item->created_at }}<br>Oleh :
                                {{ $item->user->name }}</small></p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
