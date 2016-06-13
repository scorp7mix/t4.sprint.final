<?php
namespace App\Modules\Admin\Controllers;

use App\Models\Category;
use T4\Core\Exception;
use T4\Core\MultiException;
use App\Modules\Admin\Components\Controller;

class Categories
    extends Controller
{
    public function actionDefault()
    {
        $this->data->items = Category::findAllTree();
    }

    public function actionEdit($id = null)
    {
        if (null !== $id) {
            $item = Category::findByPK($id);
            if (empty($item)) {
                $this->app->flash->error = 'No categories found for id ' . $id;
                $this->redirect('/admin/categories/');
            }
        } else {
            $item = new Category();
        }
        $this->data->item = $item;
        $this->data->categories = Category::findAllTree();
    }

    public function actionSave()
    {
        $post = $this->app->request->post;
        if (!empty($post->id)) {
            $item = Category::findByPK($post->id);
            if (empty($item)) {
                $this->app->flash->error = 'Trying to edit not existed category ' . $post->id;
                $this->redirect('/admin/categories/');
            }
        } else {
            $item = new Category();
        }

        try {
            $item->fill($post);
            $item->save();
            $this->app->flash->message = 'Category #' . $item->getPk() . ' successfully saved';
        } catch (MultiException $e) {
            $this->app->flash->error = implode('<br>', $e->collect('message'));
        } catch (Exception $e) {
            $this->app->flash->error = $e->getMessage();
        }
        $this->redirect('/admin/categories/');
    }

    public function actionDelete($id = null)
    {
        if (null !== $id) {
            $item = Category::findByPK($id);
            if (empty($item)) {
                $this->app->flash->error = 'No category found for id ' . $id;
                $this->redirect('/admin/categories/');
            } else {
                $item->delete();
                $this->app->flash->message = 'Category #' . $id . ' successfully deleted';
            }
        } else {
            $this->app->flash->error = 'Can\'t delete object without Id';
        }
        $this->redirect('/admin/categories/');
    }

    public function actionUp($id)
    {
        $item = Category::findByPK($id);
        if (empty($item)) {
            $this->redirect('/admin/categories/');
        }
        $prevSibling = $item->getPrevSibling();
        if (!empty($prevSibling)) {
            $item->insertBefore($prevSibling);
        }
        $this->redirect('/admin/categories/');
    }

    public function actionDown($id)
    {
        $item = Category::findByPK($id);
        if (empty($item)) {
            $this->redirect('/admin/categories/');
        }
        $nextSibling = $item->getNextSibling();
        if (!empty($nextSibling)) {
            $item->insertAfter($nextSibling);
        }
        $this->redirect('/admin/categories/');
    }
}