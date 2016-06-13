<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_0000000001_createWebApp
    extends Migration
{

    public function up()
    {
        $this->createTable('__users', [
            'email'     => ['type' => 'string'],
            'password'  => ['type' => 'string'],
            'firstName' => ['type' => 'string'],
            'lastName'  => ['type' => 'string'],
        ], [
            'email_idx' => ['type' => 'unique', 'columns' => ['email']],
        ]);

        $this->createTable('__user_roles', [
            'name'  => ['type' => 'string'],
            'title' => ['type' => 'string'],
        ], [
            'name_idx' => ['type' => 'unique', 'columns' => ['name']],
        ]);

        $this->createTable('__user_roles_to_users', [
            '__user_id' => ['type' => 'link'],
            '__role_id' => ['type' => 'link'],
        ]);

        $adminRole = $this->insert('__user_roles', [
            'name'  => 'admin',
            'title' => 'Administrator',
        ]);
        $userRole = $this->insert('__user_roles', [
            'name'  => 'user',
            'title' => 'User',
        ]);

        $adminUser = $this->insert('__users', [
            'email'    => 'admin@t4.local',
            'password' => '$2y$10$zAI6xqLiHgja1QwyabaeMe1WeyMjEWsibvCKYDKWllju5546x5dPW',
        ]);

        $this->insert('__user_roles_to_users', [
            '__user_id' => $adminUser,
            '__role_id' => $adminRole,
        ]);
        $this->insert('__user_roles_to_users', [
            '__user_id' => $adminUser,
            '__role_id' => $userRole,
        ]);

        $this->createTable('__user_sessions', [
            'hash'      => ['type' => 'string'],
            '__user_id' => ['type' => 'link'],
        ], [
            'hash_idx' => ['columns' => ['hash']],
        ]);
    }

    public function down()
    {
        $this->dropTable('__user_sessions');
        $this->dropTable('__user_roles_to_users');
        $this->dropTable('__user_roles');
        $this->dropTable('__users');
    }

}