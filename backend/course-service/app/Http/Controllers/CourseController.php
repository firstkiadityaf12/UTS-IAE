<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/v1/courses",
 *     summary="Ambil semua courses",
 *     tags={"Course"},
 *     @OA\Response(
 *         response=200,
 *         description="Sukses mengambil courses"
 *     )
 * )
 */
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
    }
/**
 * @OA\Post(
 *     path="/api/v1/courses",
 *     summary="Tambah course",
 *     tags={"Course"},
 *     @OA\Response(
 *         response=200,
 *         description="Sukses tambah course"
 *     )
 * )
 */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_course' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'kat_bidang' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1'
        ]);

        $course = Course::create($validated);
        return response()->json($course, 201);
    }
/**
 * @OA\Get(
 *     path="/api/v1/courses/{id}",
 *     summary="Ambil satu course berdasarkan ID",
 *     tags={"Course"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID dari course",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sukses mengambil course"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Course tidak ditemukan"
 *     )
 * )
 */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }
/**
 * @OA\Put(
 *     path="/api/v1/courses/{id}",
 *     summary="Update course berdasarkan ID",
 *     tags={"Course"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID dari course yang mau diupdate",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sukses update course"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Course tidak ditemukan"
 *     )
 * )
 */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'nama_course' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string',
            'tgl_mulai' => 'sometimes|required|date',
            'tgl_selesai' => 'sometimes|required|date|after:tgl_mulai',
            'kat_bidang' => 'sometimes|required|string|max:255',
            'kapasitas' => 'sometimes|required|integer|min:1'
        ]);

        $course->update($validated);
        return response()->json($course);
    }
/**
 * @OA\Delete(
 *     path="/api/v1/courses/{id}",
 *     summary="Hapus course berdasarkan ID",
 *     tags={"Course"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID dari course yang mau dihapus",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Sukses hapus course"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Course tidak ditemukan"
 *     )
 * )
 */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(null, 204);
    }
} 