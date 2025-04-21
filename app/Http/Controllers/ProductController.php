<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products =  Products::all();
        // dd($products);
        return  view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Product.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'stock' => 'required|numeric|min:0'
    ]);

    // Simpan gambar ke storage public/products
    $image = $request->file('image');
    $imageName = time() . '_' . $image->getClientOriginalName();
    $image->move(public_path('image'), $imageName);


    Products::create([
        'name' => $validated['name'],
        'price' => (int) str_replace(['.', ','], '', $validated['price']),
        'image' => $imageName,
        'stock' => $validated['stock'],
    ]);


    return redirect()->route('products.index')->with('success', 'Berhasil menambahkan data');
}

// public function fileUpload(Request $request)
// {
//     $image = $request->file('image');
//     $input ['imagename'] = time().'.'. $image->getClientOriginalExtension();
//     $destinatioanPath = public_path('/images');
//     $image->move($destinatioanPath, $inp)

// }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Products::find($id);

        return view('product.edit', compact('product'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Products::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:' . $product->stock,
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Bersihkan harga dari titik/koma
        $validated['price'] = (int) str_replace(['.', ','], '', $validated['price']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama dari folder public/image
            if ($product->image && file_exists(public_path('image/' . $product->image))) {
                unlink(public_path('image/' . $product->image));
            }

            // Simpan gambar baru ke public/image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('image'), $imageName);

            $validated['image'] = $imageName;
        } else {
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }


    public function updateStock (Request $request, string $id) {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $product = Products::findOrFail($id);
        $product->stock = $request->stock;
        $product->save();

        return redirect()->back()->with('success', 'Berhasil update stock');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Products::where('id',$id)->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data');
    }
}
