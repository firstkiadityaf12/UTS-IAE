<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Teacher;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteTeacherMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteTeacher',
        'description' => 'Delete an existing teacher',
    ];

    public function type(): Type
    {
        return Type::boolean(); // Mengembalikan boolean untuk menandakan sukses atau gagal
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['required', 'exists:teachers,id'],
            ],
        ];
    }

    public function resolve($root, array $args)
    {
        $teacher = Teacher::find($args['id']);

        if (!$teacher) {
            return false;
        }

        return (bool) $teacher->delete();
    }
}