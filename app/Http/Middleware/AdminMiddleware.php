<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Flight;
use Leaf\Http\Session;
use Override;

final readonly class AdminMiddleware implements BeforeMiddleware
{
    #[Override]
    public function before()
    {
        if (
            Flight::request()->url !== '/admin/login'
            && !Session::has('admin_id')
        ) {
            Flight::redirect('/admin/login');

            return false;
        }
    }
}
