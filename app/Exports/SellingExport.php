<?php

namespace App\Exports;

use App\Models\Selling;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SellingExport implements FromArray, WithStyles
{
    protected $filter;

    public function __construct()
    {
        $this->filter = request()->input('filter'); // Ambil filter langsung dari query string
    }

    public function array(): array
    {
        $header = [
            'Tanggal',
            'Nama Member',
            'No HP Member',
            'Produk',
            'Total Harga',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Kasir',
        ];

        $query = Selling::with('member', 'user', 'details.product');

        // Terapkan filter waktu
        if ($this->filter) {
            switch ($this->filter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'this_year':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        }

        $data = $query->get()->map(function ($selling) {
            $products = $selling->details->map(function ($detail) {
                return $detail->product->name . ' (' . $detail->qty . ' x Rp. ' . number_format($detail->price, 0, ',', '.') . ')';
            })->join(', ');

            return [
                'Tanggal' => $selling->created_at->format('Y-m-d'),
                'Nama Member' => $selling->member ? $selling->member->name : 'Non Member',
                'No HP Member' => $selling->member->phone_number ?? '-',
                'Produk' => $products,
                'Total Harga' => 'Rp. ' . number_format($selling->total_price, 0, ',', '.'),
                'Total Bayar' => 'Rp. ' . number_format($selling->total_pay, 0, ',', '.'),
                'Total Diskon Poin' => 'Rp. ' . number_format($selling->total_discount, 0, ',', '.'),
                'Total Kembalian' => 'Rp. ' . number_format($selling->kembalian, 0, ',', '.'),
                'Kasir' => $selling->user->name,
            ];
        })->toArray();

        return array_merge([$header], $data);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
