<?php

namespace App\Models;

use T4\Orm\Model;

/**
 * Class UserRole
 * @package App\Models
 *
 * @property string $name
 * @property string $title
 *
 * @property \T4\Core\Collection|\App\Models\User[] $users
 *
 * @method static \App\Models\UserRole findByName(string $name)
 */
class UserRole
    extends Model
{

    static public $schema = [
        'table'     => '__user_roles',
        'columns'   => [
            'name'  => ['type' => 'string'],
            'title' => ['type' => 'string'],
        ],
        'relations' => [
            'users' => ['type' => self::MANY_TO_MANY, 'model' => User::class, 'on' => '__user_roles_to_users', 'this' => '__role_id'],
        ]
    ];

}