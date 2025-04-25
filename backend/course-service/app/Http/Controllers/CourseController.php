<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
    }

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

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

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

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(null, 204);
    }
} 