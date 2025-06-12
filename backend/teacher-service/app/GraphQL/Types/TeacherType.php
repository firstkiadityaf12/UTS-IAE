<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Teacher;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class TeacherType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Teacher', // Ensure this is 'Teacher'
        'description' => 'A type for teacher information',
        'model' => Teacher::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'ID of the teacher',
            ],
            'nip' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'NIP of the teacher',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Name of the teacher',
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Email of the teacher',
            ],
            'phone' => [
                'type' => Type::string(),
                'description' => 'Phone number of the teacher',
            ],
            'address' => [
                'type' => Type::string(),
                'description' => 'Address of the teacher',
            ],
            'gender' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Gender of the teacher',
            ],
            'expertise' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Expertise of the teacher',
            ],
        ];
    }
}