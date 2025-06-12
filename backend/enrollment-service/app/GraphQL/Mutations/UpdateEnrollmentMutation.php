<?php

namespace App\GraphQL\Mutations;

use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Enrollment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UpdateEnrollmentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateEnrollment',
        'description' => 'Update an existing enrollment by ID',
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('Enrollment'));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the enrollment to update',
            ],
            'id_student' => [
                'name' => 'id_student',
                'type' => Type::int(),
                'description' => 'The ID of the student',
            ],
            'id_teacher' => [
                'name' => 'id_teacher',
                'type' => Type::int(),
                'description' => 'The ID of the teacher',
            ],
            'id_course' => [
                'name' => 'id_course',
                'type' => Type::int(),
                'description' => 'The ID of the course',
            ],
            'status' => [
                'name' => 'status',
                'type' => Type::string(),
                'description' => 'The status of the enrollment (enroll or tidak)',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        // Validasi input
        $validator = Validator::make($args, [
            'id' => 'required|integer',
            'id_student' => 'sometimes|required|integer',
            'id_teacher' => 'sometimes|required|integer',
            'id_course' => 'sometimes|required|integer',
            'status' => 'sometimes|required|in:enroll,tidak',
        ]);

        if ($validator->fails()) {
            throw new \GraphQL\Error\Error($validator->errors()->first());
        }

        try {
            // Validasi data jika ada perubahan
            if (isset($args['id_student'])) {
                $studentResponse = Http::timeout(5)->retry(3, 100)->get("http://student_nginx:80/api/v1/users/{$args['id_student']}");
                if ($studentResponse->failed()) {
                    throw new \GraphQL\Error\Error('Student tidak ditemukan');
                }
            }

            if (isset($args['id_teacher'])) {
                $teacherResponse = Http::timeout(5)->retry(3, 100)->get("http://teacher_nginx:80/api/v1/teacher/{$args['id_teacher']}");
                if ($teacherResponse->failed()) {
                    throw new \GraphQL\Error\Error('Teacher tidak ditemukan');
                }
            }

            if (isset($args['id_course'])) {
                $courseResponse = Http::timeout(5)->retry(3, 100)->get("http://course_nginx:80/api/courses/{$args['id_course']}");
                if ($courseResponse->failed()) {
                    throw new \GraphQL\Error\Error('Course tidak ditemukan');
                }
            }

            // Update enrollment
            $enrollment = Enrollment::find($args['id']);
            if (!$enrollment) {
                throw new \GraphQL\Error\Error('Enrollment not found');
            }
            $enrollment->update(array_filter($args, fn($key) => $key !== 'id', ARRAY_FILTER_USE_KEY));

            return $enrollment;
        } catch (\Exception $e) {
            throw new \GraphQL\Error\Error('Terjadi kesalahan saat memproses pembaruan: ' . $e->getMessage());
        }
    }
}
