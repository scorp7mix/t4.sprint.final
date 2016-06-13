<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1465831005_createProperties
    extends Migration
{

    public function up()
    {
        $this->createTable('products', [
            'title'         => ['type' => 'string'],
            'price'         => ['type' => 'float'],
            '__category_id' => ['type' => 'link'],
        ], [
            'price_idx' => ['columns' => ['price']]
        ]);
    }

    public function down()
    {
        $this->dropTable('products');
    }

}