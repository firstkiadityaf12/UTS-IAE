<?php

namespace App\GraphQL\Queries;

use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Enrollment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Http;

class EnrollmentsByCourseQuery extends Query
{
    protected $attributes = [
        'name' => 'enrollmentsByCourse',
        'description' => 'Retrieve all enrollments for a specific course',
    ];

    public function type(): Type
    {
        return Type::listOf(Type::nonNull(GraphQL::type('Enrollment')));
    }

    public function args(): array
    {
        return [
            'courseId' => [
                'name' => 'courseId',
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the course',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        try {
            // Validasi course dari Course Service
            $courseResponse = Http::timeout(5)->retry(3, 100)->get("http://course_nginx:80/api/courses/{$args['courseId']}");
            if ($courseResponse->failed()) {
                throw new \GraphQL\Error\Error('Course tidak ditemukan');
            }

            // Ambil enrollment berdasarkan course_id
            $enrollments = Enrollment::where('id_course', $args['courseId'])->get();

            if ($enrollments->isEmpty()) {
                throw new \GraphQL\Error\Error('No enrollments found for this course');
            }

            return $enrollments;
        } catch (\Exception $e) {
            throw new \GraphQL\Error\Error($e->getMessage());
        }
    }
}
