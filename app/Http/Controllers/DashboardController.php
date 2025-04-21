<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Selling;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $transaction = Selling::whereBetween('created_at', [$today, $tomorrow])->get();
        $count = $transaction->count();

        $updated = Selling::orderBy('created_at', 'desc')->first();

        $last7Days = Carbon::now()->subDays(6);
        $salesPerDay = Selling::where('created_at', '>=', $last7Days)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->locale('id')->translatedFormat('l');
            })
            ->map(function($sales) {
                return $sales->count();
            });

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $chartLabels = [];
        $chartData = [];

        foreach ($days as $day) {
            $chartLabels[] = $day;
            $chartData[] = $salesPerDay->get($day, 0);
        }

        return view('dashboard', compact('count', 'updated', 'chartLabels', 'chartData'));
    }

}



