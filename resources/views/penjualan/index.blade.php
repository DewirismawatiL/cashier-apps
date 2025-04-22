@extends('main')
@section('title', 'Penjualan')
@section('breadcrumb', 'Penjualan')
@section('page-title', 'Penjualan')

@section('content')
    <div class="container">
       <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <form method="GET" action="{{ route('penjualan.index') }}">
            <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="">-- Filter Waktu --</option>
                <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="this_week" {{ request('filter') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="this_month" {{ request('filter') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="this_year" {{ request('filter') == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </form>

        <a href="{{ route('formatexcel') . '?' . http_build_query(request()->only('filter')) }}" class="btn btn-primary">
            Export Penjualan (.xlsx)
        </a>

    </div>

    @if (Auth::user()->role == 'kasir')
        <a class="btn btn-success" href="{{ route('penjualan.create') }}">Tambah Penjualan</a>
    @endif
</div>


        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="dropdown me-2">
                Tampilkan
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    {{ request('entries', 10) }}
                </button>
                Entri
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['entries' => 10]) }}">10</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['entries' => 15]) }}">15</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['entries' => 20]) }}">20</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['entries' => 25]) }}">25</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['entries' => 50]) }}">50</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['entries' => 100]) }}">100</a></li>
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
                    <th scope="col" class="text-center">Tanggal Penjualan</th>
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
                Menampilkan {{ $transaction->firstItem() }} hingga {{ $transaction->lastItem() }} dari {{ $transaction->total() }} entri
            </div>
            <div>
                <nav aria-label="Page navigation example">
                    {{ $transaction->links('pagination::bootstrap-4') }}
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
                        <h5 class="modal-title" id="modalDetailLabel{{ $item->id }}">Detail Penjualan</h5>
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
