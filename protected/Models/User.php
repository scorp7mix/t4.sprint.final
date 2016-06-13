<?php

namespace App\Models;

use T4\Core\Exception;
use T4\Orm\Model;

/**
 * Class User
 * @package App\Models
 *
 * @property string $email
 * @property string $password
 * @property string $firstName
 * @property string $lastName
 *
 * @property \T4\Core\Collection|\App\Models\UserRole[] $roles
 *
 * @method static \App\Models\User findByEmail(string $email)
 */
class User
    extends Model
{

    static public $schema = [
        'table'     => '__users',
        'columns'   => [
            'email'     => ['type' => 'string'],
            'password'  => ['type' => 'string'],
            'firstName' => ['type' => 'string'],
            'lastName'  => ['type' => 'string'],
        ],
        'relations' => [
            'roles' => ['type' => self::MANY_TO_MANY, 'model' => UserRole::class, 'on' => '__user_roles_to_users', 'that' => '__role_id'],
        ]
    ];

    protected function validateEmail(string $value)
    {
        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Incorrect email');
        }
        return true;
    }
}