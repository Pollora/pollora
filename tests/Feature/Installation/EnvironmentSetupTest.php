<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

// The failure messages are kept: Pest's expect() has no message argument,
// so these stay PHPUnit assertions bound to the test case.

it('contains the app key in the env', function (): void {
    $this->assertNotEmpty(config('app.key'), 'APP_KEY is not set in .env');
});

it('contains the db database in the env', function (): void {
    $this->assertNotEmpty(config('database.connections.mysql.database'), 'DB_DATABASE is not set');
});

it('contains the app url in the env', function (): void {
    $this->assertNotEmpty(config('app.url'), 'APP_URL is not set');
});

it('has an app url matching the env', function (): void {
    $envUrl = env('APP_URL');
    $configUrl = config('app.url');

    $this->assertEquals($envUrl, $configUrl, 'config(app.url) does not match APP_URL');
});

it('connects to the database', function (): void {
    $this->assertNotNull(DB::connection()->getPdo(), 'Database connection failed');
});
