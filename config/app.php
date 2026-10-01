<?php

declare(strict_types=1);

date_default_timezone_set('America/Caracas');

// Los montos/precios de la API son floats: sin esto, entornos con
// serialize_precision alto emiten JSON con expansión binaria
// (p. ej. 1.51000000000000000888...) en vez de 1.51.
ini_set('serialize_precision', $_ENV['SERIALIZE_PRECISION']);
