<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
    * @OA\Info(
    *      version="1.0.0",
    *      title="Dokumentasi API",
    *      description="Lorem Ipsum",
    *      @OA\Contact(
    *          email="hi.wasissubekti02@gmail.com"
    *      ),
    *      @OA\License(
    *          name="Apache 2.0",
    *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
    *      )
    * )
    *
    * @OA\Server(
    *      url=L5_SWAGGER_CONST_HOST,
    *      description="Demo API Server"
    * )
    */

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"nama", "gmail", "no_telp", "alamat", "username", "password"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nama", type="string"),
 *     @OA\Property(property="gmail", type="string"),
 *     @OA\Property(property="no_telp", type="string"),
 *     @OA\Property(property="alamat", type="string"),
 *     @OA\Property(property="username", type="string"),
 *     @OA\Property(property="password", type="string"),
 * )
 */

class UserController extends Controller
{
    // Menampilkan semua user
    /**
     * @OA\Get(
     *     path="/v1/users",
     *     summary="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Menyimpan user baru
    /**
     * @OA\Post(
     *     path="/v1/users",
     *     summary="Create new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'gmail' => 'required|email|unique:users,gmail',
            'no_telp' => 'required|string',
            'alamat' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'nama' => $request->nama,
            'gmail' => $request->gmail,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    // Menampilkan detail user berdasarkan ID
    /**
     * @OA\Get(
     *     path="/v1/users/{id}",
     *     summary="Get user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        return response()->json($user);
    }

    // Mengupdate data user
    /**
     * @OA\Put(
     *     path="/v1/users/{id}",
     *     summary="Update user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string',
            'gmail' => 'sometimes|required|email|unique:users,gmail,' . $id,
            'no_telp' => 'sometimes|required|string',
            'alamat' => 'sometimes|required|string',
            'username' => 'sometimes|required|string|unique:users,username,' . $id,
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'nama' => $request->nama ?? $user->nama,
            'gmail' => $request->gmail ?? $user->gmail,
            'no_telp' => $request->no_telp ?? $user->no_telp,
            'alamat' => $request->alamat ?? $user->alamat,
            'username' => $request->username ?? $user->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json($user);
    }

    // Menghapus user
    /**
     * @OA\Delete(
     *     path="/v1/users/{id}",
     *     summary="Delete user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus']);
    }
}
