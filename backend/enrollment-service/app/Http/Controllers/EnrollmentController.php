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
        return view('enrollments.index', compact('enrollments'));
    }

    // Tampilkan form untuk membuat enrollment baru
    public function create()
    {
        return view('enrollments.create');
    }

    // Simpan data enrollment baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'id_student' => 'required|exists:students,id',
            'id_teacher' => 'required|exists:teachers,id',
            'id_course'  => 'required|exists:courses,id',
            'status'     => 'required|in:enroll,tidak'
        ]);

        Enrollment::create($request->all());

        return redirect()->route('enrollments.index')->with('success', 'Enrollment berhasil ditambahkan');
    }

    // Tampilkan detail dari satu enrollment
    public function show($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        return view('enrollments.show', compact('enrollment'));
    }

    // Tampilkan form edit enrollment
    public function edit($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        return view('enrollments.edit', compact('enrollment'));
    }

    // Update data enrollment
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_student' => 'required|exists:students,id',
            'id_teacher' => 'required|exists:teachers,id',
            'id_course'  => 'required|exists:courses,id',
            'status'     => 'required|in:enroll,tidak'
        ]);

        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update($request->all());

        return redirect()->route('enrollments.index')->with('success', 'Enrollment berhasil diperbarui');
    }

    // Hapus enrollment
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return redirect()->route('enrollments.index')->with('success', 'Enrollment berhasil dihapus');
    }
}
