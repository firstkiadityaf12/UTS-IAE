<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\RequestBook;

class RequestController extends Controller
{
    public function index()
    {
        $requestbooks = RequestBook::all();
        return response()->json($requestbooks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_student' => 'required|integer',
            'requested_title' => 'required|string',
            'notes' => 'required|string',
            'status' => 'required|in:menunggu,disetujui,ditolak',
        ]);

        try {
            // Cek keberadaan student dari API eksternal
            $studentResponse = Http::get("http://127.0.0.1:8001/api/v1/users/{$validated['id_student']}");
            if ($studentResponse->failed()) {
                return response()->json(['message' => 'Student tidak ditemukan'], 404);
            }

            // Simpan ke database
            $requestbook = RequestBook::create($validated);

            return response()->json([
                'message' => 'Request buku berhasil ditambahkan',
                'data' => $requestbook,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan request buku',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $requestbook = RequestBook::findOrFail($id);
        return response()->json($requestbook);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_student' => 'sometimes|required|integer',
            'requested_title' => 'sometimes|required|string',
            'notes' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:menunggu,disetujui,ditolak',
        ]);

        try {
            if (isset($validated['id_student'])) {
                $studentResponse = Http::get("http://127.0.0.1:8001/api/v1/users/{$validated['id_student']}");
                if ($studentResponse->failed()) {
                    return response()->json(['message' => 'Student tidak ditemukan'], 404);
                }
            }

            $requestbook = RequestBook::findOrFail($id);
            $requestbook->update($validated);

            return response()->json([
                'message' => 'Request buku berhasil diperbarui',
                'data' => $requestbook,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui request buku',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $requestbook = RequestBook::findOrFail($id);
            $requestbook->delete();

            return response()->json(['message' => 'Request buku berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus request buku',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
