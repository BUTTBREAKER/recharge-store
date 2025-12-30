<?php

namespace App\Http\Middleware;

use Flight;
use Leaf\Http\Session;

class AdminMiddleware
{
    public function before()
    {
        if (Flight::request()->url !== '/admin/login' && !Session::has('admin_id')) {
            Flight::redirect('/admin/login');
            return false;
        }
    }
}
