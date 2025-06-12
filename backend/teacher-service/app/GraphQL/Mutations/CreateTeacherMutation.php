<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Teacher;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Validation\Rule;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateTeacherMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createTeacher',
    ];

    public function type(): Type
    {
        return GraphQL::type('Teacher');
    }

    public function args(): array
    {
        return [
            'nip' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'unique:teachers,nip'],
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required'],
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'email', 'unique:teachers,email'],
            ],
            'phone' => [
                'type' => Type::string(),
                'rules' => ['nullable'],
            ],
            'address' => [
                'type' => Type::string(),
                'rules' => ['nullable'],
            ],
            'gender' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', Rule::in(['male', 'female'])],
            ],
            'expertise' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        try {
            return Teacher::create($args);
        } catch (\Exception $e) {
            throw new \GraphQL\Error\Error('Failed to create teacher: ' . $e->getMessage());
        }
    }

}