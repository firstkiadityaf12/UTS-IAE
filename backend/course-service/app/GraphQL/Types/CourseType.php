<?php

namespace App\GraphQL\Types;

use App\Models\Course;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CourseType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Course',
        'description' => 'A course type',
        'model' => Course::class,
    ];

    public function fields(): array
    {
        return [
            'id_course' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the course',
            ],
            'nama_course' => [
                'type' => Type::string(),
                'description' => 'The name of the course',
            ],
            'deskripsi' => [
                'type' => Type::string(),
                'description' => 'The description of the course',
            ],
            'tgl_mulai' => [
                'type' => Type::string(),
                'description' => 'The start date of the course',
            ],
            'tgl_selesai' => [
                'type' => Type::string(),
                'description' => 'The end date of the course',
            ],
            'kat_bidang' => [
                'type' => Type::string(),
                'description' => 'The category of the course',
            ],
            'kapasitas' => [
                'type' => Type::int(),
                'description' => 'The capacity of the course',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The creation date of the course',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The last update date of the course',
            ],
        ];
    }
}