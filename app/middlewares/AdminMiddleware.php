<?php

namespace RECHARGE\middlewares;

use Flight;

class AdminMiddleware {
    public function before() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $url = Flight::request()->url;
        if ($url !== '/admin/login' && !isset($_SESSION['admin_id'])) {
            Flight::redirect('/admin/login');
            return false;
        }
    }
}
