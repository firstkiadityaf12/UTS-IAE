<?php

namespace App\GraphQL\Mutations;

use App\Models\Course;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DeleteCourseMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteCourse'
    ];

    public function type(): Type
    {
        return Type::boolean();
    }

    public function args(): array
    {
        return [
            'id_course' => [
                'name' => 'id_course',
                'type' => Type::nonNull(Type::int())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $course = Course::findOrFail($args['id_course']);
        return $course->delete();
    }
}
