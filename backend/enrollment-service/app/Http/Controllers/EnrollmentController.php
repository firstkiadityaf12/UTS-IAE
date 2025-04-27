<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Enrollment;

/**
 * @OA\Info(
 *     title="Enrollment API Documentation",
 *     version="1.0.0",
 *     description="API Documentation for Enrollment",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://127.0.0.1:8005/api",
 *     description="Local server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format **Bearer {token}**"
 * )
 * 
 * @OA\Schema(
 *     schema="Enrollment",
 *     required={"id_student", "id_teacher", "id_course", "status"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="id_student", type="integer", example=1),
 *     @OA\Property(property="id_teacher", type="integer", example=1),
 *     @OA\Property(property="id_course", type="integer", example=1),
 *     @OA\Property(property="status", type="string", enum={"enroll", "tidak"}, example="enroll"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-01T12:00:00Z"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true)
 * )
 */
class EnrollmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/enrollments",
     *     tags={"Enrollments"},
     *     summary="Get list of enrollments",
     *     description="Retrieve a list of all enrollments",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Enrollment")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $enrollments = Enrollment::all();
        return response()->json($enrollments);
    }

    /**
     * @OA\Post(
     *     path="/enrollments",
     *     tags={"Enrollments"},
     *     summary="Create a new enrollment",
     *     description="Create a new enrollment record by validating student, teacher, and course from external services",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_student", "id_teacher", "id_course", "status"},
     *             @OA\Property(property="id_student", type="integer", example=1),
     *             @OA\Property(property="id_teacher", type="integer", example=1),
     *             @OA\Property(property="id_course", type="integer", example=1),
     *             @OA\Property(property="status", type="string", enum={"enroll", "tidak"}, example="enroll")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Enrollment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Enrollment berhasil ditambahkan"),
     *             @OA\Property(property="data", ref="#/components/schemas/Enrollment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Student tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The id_student field is required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat memproses enrollment"),
     *             @OA\Property(property="error", type="string", example="cURL error 7: Failed to connect to server")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Get enrollment by ID",
     *     description="Retrieve a single enrollment by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Enrollment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Enrollment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Enrollment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Enrollment not found")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        return response()->json($enrollment);
    }

    /**
     * @OA\Put(
     *     path="/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Update enrollment by ID",
     *     description="Update an existing enrollment by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Enrollment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_student", type="integer", example=1),
     *             @OA\Property(property="id_teacher", type="integer", example=1),
     *             @OA\Property(property="id_course", type="integer", example=1),
     *             @OA\Property(property="status", type="string", enum={"enroll", "tidak"}, example="enroll")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Enrollment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Enrollment berhasil diperbarui"),
     *             @OA\Property(property="data", ref="#/components/schemas/Enrollment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Enrollment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Enrollment not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The id_student field is required.")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Delete enrollment by ID",
     *     description="Delete an enrollment by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
         *         required=true,
     *         description="Enrollment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Enrollment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Enrollment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Enrollment not found")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return response()->json(null, 204);
    }
}