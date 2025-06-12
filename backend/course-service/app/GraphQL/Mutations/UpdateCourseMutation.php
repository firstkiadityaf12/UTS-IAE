<?php

namespace App\GraphQL\Mutations;

use App\Models\Course;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateCourseMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateCourse'
    ];

    public function type(): Type
    {
        return GraphQL::type('Course');
    }

    public function args(): array
    {
        return [
            'id_course' => [
                'name' => 'id_course',
                'type' => Type::nonNull(Type::int())
            ],
            'nama_course' => [
                'name' => 'nama_course',
                'type' => Type::string()
            ],
            'deskripsi' => [
                'name' => 'deskripsi',
                'type' => Type::string()
            ],
            'tgl_mulai' => [
                'name' => 'tgl_mulai',
                'type' => Type::string()
            ],
            'tgl_selesai' => [
                'name' => 'tgl_selesai',
                'type' => Type::string()
            ],
            'kat_bidang' => [
                'name' => 'kat_bidang',
                'type' => Type::string()
            ],
            'kapasitas' => [
                'name' => 'kapasitas',
                'type' => Type::int()
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $course = Course::findOrFail($args['id_course']);
        $course->update($args);
        return $course;
    }
}
