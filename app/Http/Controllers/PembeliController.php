<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    public function index()
    {
        $pembeli = Pembeli::all();
        return response()->json($pembeli, 200);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pembelis',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $pembeli = Pembeli::create($validate);
        if ($pembeli) {
            $data['success'] = true;
            $data['message'] = "Data Pembeli Berhasil Disimpan";
            $data['data'] = $pembeli;
            return response()->json($data, 201);
        }
    }

    public function show(string $id)
    {
        $pembeli = Pembeli::with('produk')->find($id);
        if ($pembeli) {
            return response()->json($pembeli, 200);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function update(Request $request, string $id)
    {
        $pembeli = Pembeli::find($id);
        if ($pembeli) {
            $validate = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:pembelis,email,' . $id,
                'telepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string',
            ]);

            $pembeli->update($validate);
            $data['success'] = true;
            $data['message'] = "Data Pembeli Berhasil Diperbarui";
            $data['data'] = $pembeli;
            return response()->json($data, 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Pembeli Tidak Ditemukan'], 404);
        }
    }

    public function destroy(string $id)
    {
        $pembeli = Pembeli::find($id);
        if ($pembeli) {
            $pembeli->delete();
            $data['success'] = true;
            $data['message'] = "Data Pembeli Berhasil Dihapus";
            return response()->json($data, 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Pembeli Tidak Ditemukan'], 404);
        }
    }
}
