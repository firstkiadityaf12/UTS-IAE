<?php

namespace App\GraphQL\Types;

use App\Models\Enrollment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class EnrollmentType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Enrollment',
        'description' => 'A type representing an enrollment record',
        'model' => Enrollment::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the enrollment',
            ],
            'id_student' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the student',
            ],
            'id_teacher' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the teacher',
            ],
            'id_course' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the course',
            ],
            'status' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The status of the enrollment (enroll or tidak)',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The creation date of the enrollment',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The last update date of the enrollment',
            ],
            'deleted_at' => [
                'type' => Type::string(),
                'description' => 'The deletion date of the enrollment, if applicable',
                'resolve' => function ($root) {
                    return $root->deleted_at ? $root->deleted_at->toDateTimeString() : null;
                },
            ],
        ];
    }
}
