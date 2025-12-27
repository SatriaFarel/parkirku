<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function dashboard()
    {
        $products = Product::all();
        return view('products.dashboard', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'pic_product' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $fileName = null;

        if ($request->hasFile('pic_product')) {
            $fileName = time() . '.' . $request->pic_product->extension();
            $request->pic_product->move(public_path('uploads/products'), $fileName);
            $validated['pic_product'] = $fileName;
        }


        Product::create([
            'name' => $validated['name'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'price' => $validated['price'],
            'pic_product' => $fileName
        ]);


        // Simpan flash message
        return redirect()->route('dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }



    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'pic_product' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $pic = $product->pic_product; // Simpan nama file lama

        if ($request->hasFile('pic_product')) {
            // Bikin nama file baru
            $fileName = time() . '.' . $request->pic_product->extension();

            // Pindahin ke folder public/uploads/products
            $request->pic_product->move(public_path('uploads/products'), $fileName);

            // Update nama file
            $pic = $fileName;
        }

        $product->update([
            'name' => $validated['name'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'price' => $validated['price'],
            'pic_product' => $pic
        ]);

        return redirect()->route('dashboard')->with('success', 'Produk berhasil diupdate!');
    }


    public function destroy(Product $product)
    {
        // Hapus file gambar kalau ada
        if ($product->pic_product && file_exists(public_path('uploads/products/' . $product->pic_product))) {
            unlink(public_path('uploads/products/' . $product->pic_product));
        }

        // Hapus data dari database
        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Produk berhasil dihapus!');
    }

}
