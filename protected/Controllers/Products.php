<?php

namespace App\Controllers;

use App\Models\Product;
use T4\Mvc\Controller;

class Products
    extends Controller
{

    public function actionLatest()
    {
        $this->data->items = Product::findAll(['order' => '__id DESC', 'limit' => 3]);
    }

    public function actionCheapest()
    {
        $this->data->items = Product::findAll(['order' => 'price ASC', 'limit' => 3]);
    }
}