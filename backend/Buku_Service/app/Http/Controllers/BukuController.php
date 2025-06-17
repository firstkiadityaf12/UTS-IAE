<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Menampilkan semua data buku.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $buku = Book::all();
        return response()->json($buku, 200);
    }

    /**
     * Menyimpan data buku baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'published_year' => 'required|integer|between:1900,2100',
            'price' => 'required|numeric|min:0',
        ]);

        $buku = Book::create($validated);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ], 201);
    }


    /**
     * Menampilkan detail buku berdasarkan ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $buku = Book::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        return response()->json($buku, 200);
    }

    /**
     * Memperbarui data buku berdasarkan ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $buku = Book::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'isbn' => 'sometimes|nullable|string|max:20',
            'published_year' => 'sometimes|required|integer|between:1900,2100',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        $buku->update($validated);

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku
        ], 200);
    }


    /**
     * Menghapus buku berdasarkan ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $buku = Book::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $buku->delete();
        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }

    public function listByStudent(Request $request)
    {
        $studentId = $request->query('student_id');

        // Panggil enrollment-service untuk cek apakah student sudah enroll
        $response = Http::get("http://enrollment_app/api/check-enrollment", [
            'student_id' => $studentId
        ]);

        if ($response->ok() && $response->json('enrolled')) {
            return response()->json(Book::all(), 200);
        }

        return response()->json(['message' => 'Anda belum terdaftar dalam kursus apa pun'], 403);
    }

}
