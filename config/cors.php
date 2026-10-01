<?php

declare(strict_types=1);

use App\Http\Middleware\ApiCors;

// API: CORS global para que las respuestas de error (404/401/403) también
// salgan con los headers CORS y el navegador pueda leerlas. La autenticación
// es middleware por grupo de rutas (ver routes/api.php).
Flight::before('start', ApiCors::handle(...));
