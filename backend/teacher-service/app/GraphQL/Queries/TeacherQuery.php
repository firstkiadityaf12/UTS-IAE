<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Teacher;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL; // Import GraphQL facade

class TeacherQuery extends Query
{
    protected $attributes = [
        'name' => 'teacher',
        'description' => 'A query for teachers',
    ];

    public function type(): Type
    {
        // Use GraphQL::type('Teacher') instead of resolve('teacher')
        return Type::listOf(Type::nonNull(GraphQL::type('Teacher')));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'description' => 'The ID of the teacher',
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, SelectFields $selectFields)
    {
        if (isset($args['id'])) {
            $teacher = Teacher::find($args['id']);
            if (!$teacher) {
                throw new \GraphQL\Error\Error('Teacher not found');
            }
            return [$teacher]; // Return as array to match listOf type
        }

        return Teacher::all();
    }
}