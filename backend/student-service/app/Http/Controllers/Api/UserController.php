<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'gmail' => 'required|email|unique:users,gmail',
            'no_telp' => 'required|string',
            'alamat' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'nama' => $request->nama,
            'gmail' => $request->gmail,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string',
            'gmail' => 'sometimes|required|email|unique:users,gmail,' . $id,
            'no_telp' => 'sometimes|required|string',
            'alamat' => 'sometimes|required|string',
            'username' => 'sometimes|required|string|unique:users,username,' . $id,
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'nama' => $request->nama ?? $user->nama,
            'gmail' => $request->gmail ?? $user->gmail,
            'no_telp' => $request->no_telp ?? $user->no_telp,
            'alamat' => $request->alamat ?? $user->alamat,
            'username' => $request->username ?? $user->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus']);
    }
}
