<?php

declare(strict_types=1);

namespace Module\BlocksDemo\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Block\Infrastructure\Services\BlockRegistrar;

class BlocksServiceProvider extends ServiceProvider
{
    public function boot(BlockRegistrar $registrar): void
    {
        $registrar->registerDirectory(
            directory: dirname(__DIR__, 2) . '/resources/blocks',
            containerName: 'module.blocks-demo',
        );
    }
}
