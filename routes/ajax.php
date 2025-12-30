<?php

use Leaf\Http\Session;
use RECHARGE\Enums\SessionKey;

Flight::group('/ajax', static function (): void {
    Flight::group('/ajustes', static function (): void {
        Flight::route('POST /tema', static function (): void {
            $data = Flight::request()->data;

            $tema = $data->tema ?: Session::get(SessionKey::UI_THEME->name, 'light');

            Session::set(SessionKey::UI_THEME->name, $tema);
        });
    });
});
