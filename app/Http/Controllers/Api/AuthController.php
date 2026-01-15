<?php

namespace App\Http\Controllers\Api;

use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $pembeli = Pembeli::where('email', $request->email)->first();

        if (!$pembeli || !Hash::check($request->password, $pembeli->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data['success'] = true;
        $data['message'] = 'Login berhasil';
        $data['token'] = $pembeli->createToken('PembeliApp')->plainTextToken;
        $data['data'] = $pembeli;
        return response()->json($data, Response::HTTP_ACCEPTED);
    }
}
