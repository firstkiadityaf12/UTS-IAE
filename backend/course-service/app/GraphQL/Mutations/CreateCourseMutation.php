<?php

namespace App\GraphQL\Mutations;

use App\Models\Course;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateCourseMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createCourse',
        'description' => 'Create a new course',
    ];

    public function type(): Type
    {
        return GraphQL::type('Course');
    }

    public function args(): array
    {
        return [
            'nama_course' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the course',
            ],
            'deskripsi' => [
                'type' => Type::string(),
                'description' => 'The description of the course',
            ],
            'tgl_mulai' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The start date of the course (YYYY-MM-DD)',
            ],
            'tgl_selesai' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The end date of the course (YYYY-MM-DD)',
            ],
            'kat_bidang' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The category of the course',
            ],
            'kapasitas' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The capacity of the course',
            ],
        ];
    }

    public function rules(array $args = []): array
    {
        return [
            'nama_course' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tgl_mulai' => ['required', 'date', 'before_or_equal:tgl_selesai'],
            'tgl_selesai' => ['required', 'date', 'after_or_equal:tgl_mulai'],
            'kat_bidang' => ['required', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1'],
        ];
    }

    public function resolve($root, $args)
    {
        $course = new Course();
        $course->fill($args);
        $course->save();

        return $course;
    }
}