<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Enrollment;


class EnrollmentController extends Controller
{
    // Tampilkan semua enrollment
    public function index()
    {
        $enrollments = Enrollment::all();
        return response()->json($enrollments);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id_student' => 'required',
            'id_teacher' => 'required',
            'id_course'  => 'required',
            'status'     => 'required|in:enroll,tidak',
        ]);

        try {
            // Validasi id_student dari Student Service
            $studentResponse = Http::get("http://127.0.0.1:8001/api/users{$validated['id_student']}");
            if ($studentResponse->failed()) {
                return response()->json(['message' => 'Student tidak ditemukan'], 404);
            }

            // Validasi id_teacher dari Teacher Service
            $teacherResponse = Http::get("http://127.0.0.1:8000/api/teacher{$validated['id_teacher']}");
            if ($teacherResponse->failed()) {
                return response()->json(['message' => 'Teacher tidak ditemukan'], 404);
            }

            // Validasi id_course dari Course Service
            $courseResponse = Http::get("http://127.0.0.1:8003/api/courses{$validated['id_course']}");
            if ($courseResponse->failed()) {
                return response()->json(['message' => 'Course tidak ditemukan'], 404);
            }

            // Simpan enrollment di database
            $enrollment = Enrollment::create($validated);
            return response()->json([
                'message' => 'Enrollment berhasil ditambahkan',
                'data' => $enrollment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses enrollment',
                'error' => $e->getMessage(),
            ], 500);
        }
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
