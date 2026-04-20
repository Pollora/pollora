<?php

declare(strict_types=1);

namespace Tests\Feature\Installation;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EnvironmentSetupTest extends TestCase
{
    public function test_env_contains_app_key(): void
    {
        $this->assertNotEmpty(config('app.key'), 'APP_KEY is not set in .env');
    }

    public function test_env_contains_db_database(): void
    {
        $this->assertNotEmpty(config('database.connections.mysql.database'), 'DB_DATABASE is not set');
    }

    public function test_env_contains_app_url(): void
    {
        $this->assertNotEmpty(config('app.url'), 'APP_URL is not set');
    }

    public function test_app_url_matches_env(): void
    {
        $envUrl = env('APP_URL');
        $configUrl = config('app.url');

        $this->assertEquals($envUrl, $configUrl, 'config(app.url) does not match APP_URL');
    }

    public function test_database_connection_works(): void
    {
        $this->assertNotNull(DB::connection()->getPdo(), 'Database connection failed');
    }
}
