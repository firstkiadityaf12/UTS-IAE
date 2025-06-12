<?php

namespace App\GraphQL\Queries;

use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Enrollment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class EnrollmentQuery extends Query
{
    protected $attributes = [
        'name' => 'enrollment',
        'description' => 'Retrieve an enrollment by ID',
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
                'description' => 'The ID of the enrollment',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $enrollment = Enrollment::find($args['id']);
        if (!$enrollment) {
            throw new \GraphQL\Error\Error('Enrollment not found');
        }
        return $enrollment;
    }
}
