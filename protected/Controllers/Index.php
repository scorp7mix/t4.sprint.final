<?php

namespace App\Controllers;

use App\Models\Category;
use T4\Mvc\Controller;

class Index
    extends Controller
{

    public function actionDefault()
    {
        $this->data->categories = Category::findAll()->sort(function(Category $c1, Category $c2) {
            return $c2->countChildProducts() <=> $c1->countChildProducts();
        });
    }

}