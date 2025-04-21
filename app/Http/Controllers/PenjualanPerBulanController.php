<?php

namespace App\Http\Controllers;

use App\Models\Selling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanPerBulan extends Controller
{
    public function penjualanPerBulan()
{
    $penjualanPerBulan = Selling::select(
        DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
        DB::raw("SUM(total_price) as total_penjualan")
    )
    ->groupBy('bulan')
    ->orderBy('bulan', 'asc')
    ->get();

    // return response()->json($penjualanPerBulan);
}
}
