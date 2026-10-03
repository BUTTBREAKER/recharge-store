<?php

use App\Enums\SessionKey;
use Leaf\Http\Session;

Flight::group('/ajax', static function (): void {
    Flight::group('/settings', static function (): void {
        Flight::route('POST /theme', static function (): void {
            $data = Flight::request()->data;

            $theme = $data->theme ?: Session::get(
                SessionKey::UI_THEME->name,
                'light',
            );

            Session::set(SessionKey::UI_THEME->name, $theme);
        });
    });
});
