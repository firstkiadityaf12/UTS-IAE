<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

/**
 * @OA\Info(
 *     title="Enrollment API Documentation",
 *     version="1.0.0",
 *     description="API Documentation for Enrollment"
 * )
 * 
 * @OA\Schema(
 *     schema="Enrollment",
 *     required={"id_student", "id_teacher", "id_course", "status"},
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="id_student", type="integer", example=1),
 *     @OA\Property(property="id_teacher", type="integer", example=1),
 *     @OA\Property(property="id_course", type="integer", example=1),
 *     @OA\Property(property="status", type="string", enum={"enroll", "tidak"}),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime"),
 *     @OA\Property(property="deleted_at", type="string", format="datetime", nullable=true)
 * )
 */

class EnrollmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/enrollments",
     *     tags={"Enrollments"},
     *     summary="Get list of enrollments",
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
     *     path="/api/enrollments",
     *     tags={"Enrollments"},
     *     summary="Create a new enrollment",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_student", "id_teacher", "id_course", "status"},
     *             @OA\Property(property="id_student", type="integer", example=1),
     *             @OA\Property(property="id_teacher", type="integer", example=1),
     *             @OA\Property(property="id_course", type="integer", example=1),
     *             @OA\Property(property="status", type="string", enum={"enroll", "tidak"})
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
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The id_student field is required.")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Get enrollment by ID",
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
     *     path="/api/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Update enrollment by ID",
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
     *             @OA\Property(property="status", type="string", enum={"enroll", "tidak"})
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
     *     path="/api/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Delete enrollment by ID",
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