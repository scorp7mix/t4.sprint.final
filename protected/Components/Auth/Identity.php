<?php

namespace App\Components\Auth;

use App\Models\User;
use App\Models\UserRole;
use App\Models\UserSession;
use T4\Core\Exception;
use T4\Core\MultiException;
use T4\Core\Std;
use T4\Http\Helpers;

class Identity
{
    const AUTH_COOKIE_NAME = 't4auth';

    public function getUser()
    {
        if (Helpers::issetCookie(self::AUTH_COOKIE_NAME)) {
            if (!empty($hash = Helpers::getCookie(self::AUTH_COOKIE_NAME))) {
                if (!empty($session = UserSession::findByHash($hash))) {
                    return $session->user;
                }
            }
        }

        return null;
    }

    public function login(Std $data)
    {
        $errors = new MultiException();

        if (empty($data->email)) {
            $errors->add(new Exception('Email cannot be empty'));
        }
        if (empty($data->password)) {
            $errors->add(new Exception('Password cannot be empty'));
        }
        if (!$errors->isEmpty()) {
            throw $errors;
        }

        $user = User::findByEmail($data->email);
        if (empty($user)) {
            $errors->add(new Exception('User not found'));
            throw $errors;
        }

        if (!password_verify($data->password, $user->password)) {
            $errors->add(new Exception('Password wrong'));
            throw $errors;
        }

        $hash = sha1(microtime() . mt_rand());
        $session = new UserSession();
        $session->hash = $hash;
        $session->user = $user;
        $session->save();

        Helpers::setCookie(self::AUTH_COOKIE_NAME, $hash);
    }

    public function register(Std $data)
    {
        $errors = new MultiException();

        if (empty($data->firstName)) {
            $errors->add(new Exception('First name cannot be empty'));
        }
        if (empty($data->lastName)) {
            $errors->add(new Exception('Last name cannot be empty'));
        }
        if (empty($data->email)) {
            $errors->add(new Exception('Email cannot be empty'));
        } elseif (!empty(User::findByEmail($data->email))) {
            $errors->add(new Exception('Email already registered'));
        }
        if (empty($data->password) || empty($data->password2)) {
            $errors->add(new Exception('Password cannot be empty'));
        } elseif ($data->password !== $data->password2) {
            $errors->add(new Exception('Passwords don\'t match'));
        }
        if (!$errors->isEmpty()) {
            throw $errors;
        }

        $user = new User;
        $user->fill($data->toArray());
        $user->roles->add(UserRole::findByName('user'));
        $user->password = password_hash($data->password, PASSWORD_DEFAULT);
        $user->save();

        return $user;
    }

    public function logout()
    {
        if (Helpers::issetCookie(self::AUTH_COOKIE_NAME)) {
            if (!empty($hash = Helpers::getCookie(self::AUTH_COOKIE_NAME))) {
                Helpers::unsetCookie(self::AUTH_COOKIE_NAME);
                $session = UserSession::findByHash($hash);
                if (!empty($session)) {
                    $session->delete();
                }
            }
        }
    }

}