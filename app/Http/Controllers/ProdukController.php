<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with('pembeli')->get();
        return response()->json($produk, 200);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'pembeli_id' => 'nullable|exists:pembelis,id',
        ]);

        $produk = Produk::create($validate);
        if ($produk) {
            $data['success'] = true;
            $data['message'] = "Data Produk Berhasil Disimpan";
            $data['data'] = $produk;
            return response()->json($data, 201);
        }
    }

    public function show(string $id)
    {
        $produk = Produk::with('pembeli')->find($id);
        if ($produk) {
            return response()->json($produk, 200);
        }
        return response()->json(['success' => false, 'message' => 'Data Produk Tidak Ditemukan'], 404);
    }

    public function update(Request $request, string $id)
    {
        $produk = Produk::find($id);
        if ($produk) {
            $validate = $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'pembeli_id' => 'nullable|exists:pembelis,id',
            ]);

            $produk->update($validate);
            $data['success'] = true;
            $data['message'] = "Data Produk Berhasil Diperbarui";
            $data['data'] = $produk;
            return response()->json($data, 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Produk Tidak Ditemukan'], 404);
        }
    }

    public function destroy(string $id)
    {
        $produk = Produk::find($id);
        if ($produk) {
            $produk->delete();
            $data['success'] = true;
            $data['message'] = "Data Produk Berhasil Dihapus";
            return response()->json($data, 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Produk Tidak Ditemukan'], 404);
        }
    }
}
