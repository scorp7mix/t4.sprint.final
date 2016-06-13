<?php

namespace App\Modules\Admin\Components;

class Controller
    extends \T4\Mvc\Controller
{

    public function access($action, $params = [])
    {
        return (!empty($this->app->user) && $this->app->user->hasRole('admin'));
    }

}