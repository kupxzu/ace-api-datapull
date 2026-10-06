<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Basic Auth Credentials
    |--------------------------------------------------------------------------
    |
    | The shared SQL Server database (sqlsrv connection) must never be
    | altered — no new tables, no migrations — so API credentials are NOT
    | stored in any database. They live only here, sourced from the .env
    | file, with the password kept as a bcrypt hash (never plain text).
    |
    | Manage these values with:
    |
    |     php artisan api:credentials {username} {password}
    |
    */

    'username' => env('API_AUTH_USERNAME', ''),

    'password_hash' => env('API_AUTH_PASSWORD_HASH', ''),

];
