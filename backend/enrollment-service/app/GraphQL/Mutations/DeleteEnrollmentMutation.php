<?php

namespace App\GraphQL\Mutations;

use App\Models\Enrollment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteEnrollmentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteEnrollment',
        'description' => 'Delete an enrollment by ID',
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::boolean());
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
                'description' => 'The ID of the enrollment to delete',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $enrollment = Enrollment::find($args['id']);
        if (!$enrollment) {
            throw new \GraphQL\Error\Error('Enrollment not found');
        }

        return $enrollment->delete();
    }
}
