<?php

namespace App\GraphQL\Queries;

use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Enrollment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class EnrollmentsQuery extends Query
{
    protected $attributes = [
        'name' => 'enrollments',
        'description' => 'Retrieve a list of all enrollments',
    ];

    public function type(): Type
    {
        return Type::listOf(Type::nonNull(GraphQL::type('Enrollment')));
    }

    public function resolve($root, $args)
    {
        return Enrollment::all();
    }
}
