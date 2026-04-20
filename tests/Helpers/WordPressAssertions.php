<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Support\Facades\DB;

trait WordPressAssertions
{
    protected function getPrefix(): string
    {
        return config('database.connections.mysql.prefix', 'wp_');
    }

    protected function assertWordPressTableExists(string $table): void
    {
        $fullTable = $this->getPrefix() . $table;

        $results = DB::select("SHOW TABLES LIKE '{$fullTable}'");
        $this->assertNotEmpty($results, "WordPress table '{$fullTable}' does not exist.");
    }

    protected function assertWordPressOptionEquals(string $option, string $expected): void
    {
        $result = DB::table('options')
            ->where('option_name', $option)
            ->value('option_value');

        $this->assertEquals($expected, $result, "WordPress option '{$option}' does not match expected value.");
    }

    protected function getWordPressOption(string $option): ?string
    {
        return DB::table('options')
            ->where('option_name', $option)
            ->value('option_value');
    }
}