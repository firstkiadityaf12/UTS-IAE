<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="Teacher API Documentation",
 *     version="1.0.0",
 *     description="API Documentation for Teacher Microservice"
 * )
 *
 * @OA\Schema(
 *     schema="Teacher",
 *     required={"nip", "name", "email", "gender", "expertise"},
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="nip", type="string", example="123456789"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="gender", type="string", enum={"male","female"}),
 *     @OA\Property(property="expertise", type="string", example="Mathematics"),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime"),
 *     @OA\Property(property="deleted_at", type="string", format="datetime", nullable=true)
 * )
 */

class TeacherController extends Controller
{
    /**
     * @OA\Get(
     *     path="/teachers",
     *     tags={"Teachers"},
     *     summary="Get list of teachers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Teacher")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $teachers = Teacher::all();
        return response()->json([
            'status' => 'success',
            'data' => $teachers
        ]);
    }

    /**
     * @OA\Post(
     *     path="/teachers",
     *     tags={"Teachers"},
     *     summary="Create a new teacher",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nip","name","email","gender","expertise"},
     *             @OA\Property(property="nip", type="string", example="123456789"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="gender", type="string", enum={"male","female"}),
     *             @OA\Property(property="expertise", type="string", example="Mathematics")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Teacher created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:teachers,nip',
            'name' => 'required',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'gender' => 'required|in:male,female',
            'expertise' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $teacher = Teacher::create($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $teacher
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/teachers/{id}",
     *     tags={"Teachers"},
     *     summary="Get teacher by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Teacher ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Teacher not found"
     *     )
     * )
     */
    public function show(string $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $teacher
        ]);
    }

     /**
     * @OA\Put(
     *     path="/teachers/{id}",
     *     tags={"Teachers"},
     *     summary="Update teacher by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Teacher")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Teacher updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Teacher not found"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:teachers,nip,'.$id,
            'name' => 'required',
            'email' => 'required|email|unique:teachers,email,'.$id,
            'phone' => 'nullable',
            'address' => 'nullable',
            'gender' => 'required|in:male,female',
            'expertise' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $teacher->update($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $teacher
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/teachers/{id}",
     *     tags={"Teachers"},
     *     summary="Delete teacher by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Teacher deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Teacher not found"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found'
            ], 404);
        }

        $teacher->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Teacher deleted successfully'
        ]);
    }
}
