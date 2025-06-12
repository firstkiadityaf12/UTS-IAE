<?php

namespace App\GraphQL\Mutations;

use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Enrollment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CreateEnrollmentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createEnrollment',
        'description' => 'Create a new enrollment record',
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('Enrollment'));
    }

    public function args(): array
    {
        return [
            'id_student' => [
                'name' => 'id_student',
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the student',
            ],
            'id_teacher' => [
                'name' => 'id_teacher',
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the teacher',
            ],
            'id_course' => [
                'name' => 'id_course',
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the course',
            ],
            'status' => [
                'name' => 'status',
                'type' => Type::nonNull(Type::string()),
                'description' => 'The status of the enrollment (enroll or tidak)',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        // Validasi input
        $validator = Validator::make($args, [
            'id_student' => 'required|integer',
            'id_teacher' => 'required|integer',
            'id_course' => 'required|integer',
            'status' => 'required|in:enroll,tidak',
        ]);

        if ($validator->fails()) {
            throw new \GraphQL\Error\Error($validator->errors()->first());
        }

        try {
            // Validasi id_student dari Student Service
            $studentResponse = Http::timeout(5)->retry(3, 100)->get("http://student_nginx:80/api/v1/users/{$args['id_student']}");
            if ($studentResponse->failed()) {
                throw new \GraphQL\Error\Error('Student tidak ditemukan');
            }

            // Validasi id_teacher dari Teacher Service
            $teacherResponse = Http::timeout(5)->retry(3, 100)->get("http://teacher_nginx:80/api/v1/teacher/{$args['id_teacher']}");
            if ($teacherResponse->failed()) {
                throw new \GraphQL\Error\Error('Teacher tidak ditemukan');
            }

            // Validasi id_course dari Course Service
            $courseResponse = Http::timeout(5)->retry(3, 100)->get("http://course_nginx:80/api/courses/{$args['id_course']}");
            if ($courseResponse->failed()) {
                throw new \GraphQL\Error\Error('Course tidak ditemukan');
            }

            // Simpan enrollment di database
            $enrollment = Enrollment::create($args);

            return $enrollment;
        } catch (\Exception $e) {
            throw new \GraphQL\Error\Error('Terjadi kesalahan saat memproses enrollment: ' . $e->getMessage());
        }
    }
}
