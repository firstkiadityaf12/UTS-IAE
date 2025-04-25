<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    // Tampilkan semua enrollment
    public function index()
    {
        $enrollments = Enrollment::all();
        return response()->json($enrollments);
    }

    // Simpan data enrollment baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_student' => 'required|exists:students,id',
            'id_teacher' => 'required|exists:teachers,id',
            'id_course'  => 'required|exists:courses,id',
            'status'     => 'required|in:enroll,tidak'
        ]);

        $enrollment = Enrollment::create($validated);

        return response()->json([
            'message' => 'Enrollment berhasil ditambahkan',
            'data' => $enrollment
        ], 201);
    }

    // Tampilkan detail dari satu enrollment
    public function show($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        return response()->json($enrollment);
    }

    // Update data enrollment
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_student' => 'sometimes|required|exists:students,id',
            'id_teacher' => 'sometimes|required|exists:teachers,id',
            'id_course'  => 'sometimes|required|exists:courses,id',
            'status'     => 'sometimes|required|in:enroll,tidak'
        ]);

        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update($validated);

        return response()->json([
            'message' => 'Enrollment berhasil diperbarui',
            'data' => $enrollment
        ]);
    }

    // Hapus enrollment
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return response()->json(null, 204);
    }
}
