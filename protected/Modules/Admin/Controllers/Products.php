<?php
namespace App\Modules\Admin\Controllers;

use App\Models\Category;
use App\Models\Product;
use T4\Core\Exception;
use T4\Core\MultiException;
use App\Modules\Admin\Components\Controller;

class Products
    extends Controller
{
    public function actionDefault()
    {
        $this->data->items = Product::findAll();
    }

    public function actionEdit($id = null)
    {
        if (null !== $id) {
            $item = Product::findByPK($id);
            if (empty($item)) {
                $this->app->flash->error = 'No products found for id ' . $id;
                $this->redirect('/admin/products/');
            }
        } else {
            $item = new Product();
        }
        $this->data->item = $item;
        $this->data->categories = Category::findAllTree();
    }

    public function actionSave()
    {
        $post = $this->app->request->post;
        if (!empty($post->id)) {
            $item = Product::findByPK($post->id);
            if (empty($item)) {
                $this->app->flash->error = 'Trying to edit not existed product ' . $post->id;
                $this->redirect('/admin/products/');
            }
        } else {
            $item = new Product();
        }

        try {
            $item->fill($post);
            $item->save();
            $this->app->flash->message = 'Product #' . $item->getPk() . ' successfully saved';
        } catch (MultiException $e) {
            $this->app->flash->error = implode('<br>', $e->collect('message'));
        } catch (Exception $e) {
            $this->app->flash->error = $e->getMessage();
        }
        $this->redirect('/admin/products/');
    }

    public function actionDelete($id = null)
    {
        if (null !== $id) {
            $item = Product::findByPK($id);
            if (empty($item)) {
                $this->app->flash->error = 'No product found for id ' . $id;
                $this->redirect('/admin/products/');
            } else {
                $item->delete();
                $this->app->flash->message = 'Product #' . $id . ' successfully deleted';
            }
        } else {
            $this->app->flash->error = 'Can\'t delete object without Id';
        }
        $this->redirect('/admin/products/');
    }
}