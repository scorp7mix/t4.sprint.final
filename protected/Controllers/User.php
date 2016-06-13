<?php

namespace App\Controllers;

use App\Components\Auth\Identity;
use T4\Core\MultiException;
use T4\Core\Std;
use T4\Mvc\Controller;

class User
    extends Controller
{

    public function actionLogin(Std $form = null)
    {
        if (null !== $form) {
            try {
                $auth = new Identity();
                $auth->login($form);
                $this->redirect('/');
            } catch (MultiException $e) {
                $this->data->errors = $e;
                $this->data->user = $form;
            }
        }
    }

    public function actionRegister(Std $form = null)
    {
        if (null !== $form) {
            try {
                $auth = new Identity();
                $auth->login($auth->register($form));
                $this->redirect('/');
            } catch (MultiException $e) {
                $this->data->errors = $e;
                $this->data->user = $form;
            }
        }
    }

    public function actionLogout()
    {
        $auth = new Identity();
        $auth->logout();
        $this->redirect('/');
    }
}