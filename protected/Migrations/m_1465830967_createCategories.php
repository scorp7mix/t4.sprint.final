<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1465830967_createCategories
    extends Migration
{

    public function up()
    {
        $this->createTable('categories', [
            'title' => ['type' => 'string'],
        ], [], ['tree']);
    }

    public function down()
    {
        $this->dropTable('categories');
    }

}