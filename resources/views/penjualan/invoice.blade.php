<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            max-width: 800px;
            margin: auto;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            width: 150px;
        }

        h1 {
            margin-top: 30px;
        }

        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        table-layout: fixed; /* ini penting */
}


        td, th {
        border: 1px solid #ddd;
        padding: 8px; /* dikurangi dari 13px */
        text-align: left;
        word-break: break-word; /* supaya teks panjang tidak keluar kolom */
        white-space: normal;
        font-size: 10px; /* bisa diturunkan kalau masih kepanjangan */
}

.nowrap {
    white-space: nowrap;
}


        th {
            background-color: #f4f4f4;
        }

        tfoot th {
            text-align: right;
        }

        .notes {
            margin-top: 20px;
            font-size: 14px;
        }

        address {
            margin-top: 20px;
            font-size: 14px;
            font-style: normal;
        }
    </style>
</head>

<body>
    <h1>Invoice - #2</h1>
    @if ($member !== null)
    <p>Member Name : {{ $member->name }}</p>
    <p>No. HP : {{ $member->phone_number }}</p>
    <p>Bergabung Sejak : {{ $member->created_at }}</p>
    <p>Point Member : {{ $member->poin_member }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Sub total</th>
                <th>Tunai</th>
                <th>Kembalian</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="nowrap">Rp.{{ number_format($item->product->price, 0, '.', '.') }}</td>
                <td>{{ $item->qty }}</td>
                <td class="nowrap">Rp.{{ number_format($item->qty * $item->product->price, 0, '.', '.') }}</td>
                <td>Rp.{{ number_format($cash, 0, '.', '.') }}</td>
                <td class="nowrap">Rp.{{ number_format($kembalian, 0, '.', '.') }}</td>
                <td class="nowrap">Rp.{{ number_format($transaction->total_price, 0, '.', '.') }}</td>

            </tr>
            @endforeach
            @if ($member)
            <tr>
                <td colspan="3"><strong>Poin Member</strong></td>
                <td>{{ $member->poin_member }}</td>
            </tr>
            @endif

        </tbody>
    </table>
    <div class="notes">
        Terima kasih atas pembelian Anda.
    </div>
    <hr>
    <address>
        Dewi R.<br>
        Alamat: Jl. Bogor R. Tech Group gedung A<br>
        Email: rtechgroup@gmail.com
    </address>
</body>

</html>
