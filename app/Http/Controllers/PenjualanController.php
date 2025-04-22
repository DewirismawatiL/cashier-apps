<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\detail_transact;
use App\Models\Members;
use App\Models\Selling;
use App\Models\Products;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SellingExport;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index(Request $request)
{
    $entries = $request->input('entries', 10);
    $search = $request->input('search');
    $filter = $request->input('filter'); // filter waktu: today, this_week, etc.

    $transaction = Selling::with('user', 'member', 'details.product')
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->whereHas('member', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('phone_number', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        })
        ->when($filter, function ($query, $filter) {
            switch ($filter) {
                case 'today':
                    return $query->whereDate('created_at', Carbon::today());
                case 'this_week':
                    return $query->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                case 'this_month':
                    return $query->whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year);
                case 'this_year':
                    return $query->whereYear('created_at', Carbon::now()->year);
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate($entries);

        return view('penjualan.index', compact('transaction', 'entries', 'search', 'filter'));
}


public function exportExcel(Request $request)
{
    return Excel::download(new SellingExport(), 'laporan-penjualan.xlsx');
}
    public function create()
    {
        $products = Products::all();
        return view('penjualan.tambah', compact('products'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'total_bayar' => str_replace(['.', ','], '', $request->total_bayar),
        ]);

        $request->validate([
            'member' => 'required|in:non-member,member',
            'total_bayar' => 'required|numeric',
        ]);


        $user = Auth::user();
        $carts = Cart::with('product')->get();

        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->product->price * $cart->qty;
        }

        $kembalian = $request->total_bayar - $totalPrice;

        if ($request->member == 'member') {
            $request->validate([
                'phoneNumber' => 'required|numeric',
            ]);

            $phonenumber = $request->phoneNumber;
            $member = Members::where('phone_number', $phonenumber)->first();

            if ($member == null) {
                $poinmember = $totalPrice * 10 / 100;

                $member = Members::create([
                    'phone_number' => $phonenumber,
                    'poin_member' => $poinmember,
                ]);
            }

            $sellingData = [];
            $checkpoin = 0;

            foreach ($carts as $cart) {
                $sellingData[] = [
                    'product_name' => $cart->product->name,
                    'price' => $cart->product->price,
                    'qty' => $cart->qty,
                    'subtotal' => $cart->product->price * $cart->qty,
                ];
            }

            if ($member) {
                $checkpoin = Selling::where('member_id', $member->id)->count();
            }

           return view('penjualan.checkMember', [
    'dataTransaction' => $sellingData,
    'member' => $member,
    'totalBayar' => $request->total_bayar,
    'subtotal' => $totalPrice,
    'poinmember' => $member->poin_member,
    'checkPoint' => $checkpoin
]);
        }

        // Non-member process
        $transaction = Selling::create([
            'member_id' => null,
            'total_price' => $totalPrice,
            'total_pay' => $request->total_bayar,
            'kembalian' => $request->total_bayar - $totalPrice,
            'user_id' => $user->id,
        ]);

        foreach ($carts as $cart) {
            detail_transact::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product->id,
                'qty' => $cart->qty,
            ]);

            $product = Products::find($cart->product->id);
            $product->stock -= $cart->qty;
            $product->save();

            $sellingData[] = [
                'product_name' => $cart->product->name,
                'price' => $cart->product->price,
                'qty' => $cart->qty,
                'subtotal' => $cart->product->price * $cart->qty,
            ];
        }

        Cart::truncate();

        $invoiceNumber = Selling::orderBy('created_at', 'desc')->count();
        $userName = $user->name;

        return view('pembelian.result', [
            'sellingData' => $sellingData,
            'totalPrice' => $totalPrice,
            'userName' => $userName,
            'kembalian' => $kembalian,
            'invoiceNumber' => $invoiceNumber,
            'transactionId' => $transaction->id
        ]);
    }

    public function show(Request $request)
    {
        $cartData = $request->query('products', []);
        $products = Products::whereIn('id', array_keys($cartData))->get();

        return view('pembelian.member', compact('products', 'cartData'));
    }

    public function checkMember(Request $request)
    {
        $request->merge([
            'total_bayar' => str_replace(['.', ','], '', $request->total_bayar),
        ]);

        $member = Members::where('phone_number', $request->phone_number)->first();

        if ($member && $request->name) {
            $member->name = $request->name;
            $member->save();
        }

        $user = Auth::user();
        $carts = Cart::with('product')->get();

        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->product->price * $cart->qty;
        }

        $poinmember = $totalPrice * 10 / 100;

        if ($request->checkPoin) {
            $totalPrice -= $member->poin_member;
            if ($totalPrice < 0) {
                $totalPrice = 0;
            }
            $member->poin_member = 0;
            $member->save();
        }

        $totalPrice = (int) $totalPrice;
        $kembalian = $request->total_bayar - $totalPrice;

        $sellingData = [];
        $transaction = Selling::create([
            'member_id' => $member->id,
            'total_price' => $totalPrice,
            'total_pay' => $request->total_bayar,
            'user_id' => $user->id,
            'kembalian' => $kembalian,
        ]);

        foreach ($carts as $cart) {
            detail_transact::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product->id,
                'qty' => $cart->qty,
            ]);

            $product = Products::find($cart->product->id);
            $product->stock -= $cart->qty;
            $product->save();

            $sellingData[] = [
                'product_name' => $cart->product->name,
                'price' => $cart->product->price,
                'qty' => $cart->qty,
                'subtotal' => $cart->product->price * $cart->qty,
            ];
        }

        $member->poin_member += $poinmember;
        $member->save();
        Cart::truncate();

        $invoiceNumber = Selling::orderBy('created_at', 'desc')->count() + 1;
        $userName = $user->name;

        return view('penjualan.result', [
            'sellingData' => $sellingData,
            'totalPrice' => $totalPrice,
            'userName' => $userName,
            'kembalian' => $kembalian,
            'invoiceNumber' => $invoiceNumber,
            'transactionId' => $transaction->id
        ]);
    }

    public function CetakPdf(Request $request, $id)
    {
        $transaction = Selling::where('id', $id)->with('user', 'member', 'details.product')->first();

        $member = $transaction->member;
        $details = $transaction->details;
        $cash = $transaction->total_pay;
        $kembalian = $cash - $transaction->total_price;

        // $cash = $transaction->cash !== null ? $transaction->cash : $transaction->total_price;
        // $kembalian = $cash - $transaction->total_price;


        $data = [
            'transaction' => $transaction,
            'member' => $member,
            'details' => $details,
            'cash' => $cash,
            'kembalian' => $kembalian,
        ];

        $pdf = Pdf::loadView('pembelian.invoice', $data);
        return $pdf->stream('bukti-pembelian.pdf');
    }


    public function edit(string $id) { }
    public function update(Request $request, string $id) { }
    public function destroy(string $id) { }

    public function cart(Request $request)
    {
        $request->validate([
            'cart_data' => 'required|json'
        ]);

        Cart::truncate();

        $cartItem = json_decode($request->cart_data, true);

        foreach ($cartItem as $productList => $qty) {
            Cart::create([
                'product_id' => $productList,
                'qty' => $qty,
            ]);
        }

        $cartItems = Cart::all();
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $product = Products::find($item->product_id);
            if ($product) {
                $totalPrice += $product->price * $item->qty;
            }
        }

        return view('pembelian.member', compact('cartItems', 'totalPrice'));
    }

    public function cancelCart()
    {
        Cart::truncate();
        return redirect()->route('penjualan.create')->with('message', 'Cart berhasil dibatalkan.');
    }
}
