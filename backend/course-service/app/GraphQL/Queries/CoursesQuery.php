<?php

namespace App\GraphQL\Queries;

use App\Models\Course;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CoursesQuery extends Query
{
    protected $attributes = [
        'name' => 'courses',
        'description' => 'Retrieve a list of courses',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Course')); // Pastikan tipe 'Course' terdaftar
    }

    public function resolve($root, $args, $context, $resolveInfo, \Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        return Course::select($fields->getSelect())->with($fields->getRelations())->get();
    }
}