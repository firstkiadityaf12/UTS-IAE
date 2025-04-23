<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'gmail' => 'required|email',
            'no_telp' => 'required|string',
            'alamat' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::create($validated);
        return response()->json($user, 201);
    }
}
