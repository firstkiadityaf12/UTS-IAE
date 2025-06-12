<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Teacher;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Validation\Rule;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateTeacherMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateTeacher',
        'description' => 'Update an existing teacher',
    ];

    public function type(): Type
    {
        return GraphQL::type('Teacher');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['required', 'exists:teachers,id'],
            ],
            'nip' => [
                'name' => 'nip',
                'type' => Type::string(),
                'rules' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        $id = $this->args['id'];
                        if (Teacher::where('nip', $value)->where('id', '!=', $id)->exists()) {
                            $fail('The ' . $attribute . ' has already been taken.');
                        }
                    },
                ],
            ],
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
                'rules' => ['nullable'],
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::string(),
                'rules' => [
                    'nullable',
                    'email',
                    function ($attribute, $value, $fail) {
                        $id = $this->args['id'];
                        if (Teacher::where('email', $value)->where('id', '!=', $id)->exists()) {
                            $fail('The ' . $attribute . ' has already been taken.');
                        }
                    },
                ],
            ],
            'phone' => [
                'name' => 'phone',
                'type' => Type::string(),
                'rules' => ['nullable'],
            ],
            'address' => [
                'name' => 'address',
                'type' => Type::string(),
                'rules' => ['nullable'],
            ],
            'gender' => [
                'name' => 'gender',
                'type' => Type::string(),
                'rules' => ['nullable', Rule::in(['male', 'female'])],
            ],
            'expertise' => [
                'name' => 'expertise',
                'type' => Type::string(),
                'rules' => ['nullable'],
            ],
        ];
    }

    public function resolve($root, array $args)
    {
        $teacher = Teacher::find($args['id']);

        if (!$teacher) {
            return null; // Atau throw new \Exception('Teacher not found!');
        }

        $teacher->update($args);

        return $teacher;
    }
}