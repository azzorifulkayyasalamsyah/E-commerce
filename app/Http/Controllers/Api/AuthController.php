<?php

namespace App\Http\Controllers\API;

use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pembelis',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string'
        ]);

        $validate['password'] = bcrypt($request->password);
        $pembeli = Pembeli::create($validate);

        if ($pembeli) {
            $data['success'] = true;
            $data['message'] = 'Pembeli berhasil disimpan';
            $data['data'] = $pembeli->nama;
            $data['token'] = $pembeli->createToken('PembeliApp')->plainTextToken;
            return response()->json($data, Response::HTTP_CREATED);
        } else {
            $data['success'] = false;
            $data['message'] = 'Pembeli gagal disimpan';
            return response()->json($data, 500);
        }
    }

    public function login(Request $request)
    {
        if (Auth::guard('pembeli')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $pembeli = Auth::guard('pembeli')->user();
            $data['success'] = true;
            $data['message'] = 'Login berhasil';
            $data['token'] = $pembeli->createToken('PembeliApp')->plainTextToken;
            $data['data'] = $pembeli;
            return response()->json($data, Response::HTTP_ACCEPTED);
        } else {
            $data['success'] = false;
            $data['message'] = 'Email atau password salah';
            return response()->json($data, Response::HTTP_UNAUTHORIZED);
        }
    }
}
