<?php

namespace Theme\Providers;

use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\View;
use Illuminate\View\ViewServiceProvider as ViewServiceProviderBase;

class ViewServiceProvider extends ViewServiceProviderBase
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMacros();
    }

    /**
     * Register View Macros
     *
     * @return void
     */
    public function registerMacros()
    {
        $app = $this->app;

        /**
         * Get the compiled path of the view
         *
         * @return string
         */
        View::macro('getCompiled', function () {
            /** @var string $file path to file */
            $file = $this->getPath();

            /** @var \Illuminate\Contracts\View\Engine $engine */
            $engine = $this->getEngine();

            return ($engine instanceof CompilerEngine)
                ? $engine->getCompiler()->getCompiledPath($file)
                : $file;
        });

        /**
         * Creates a loader for the view to be called later
         *
         * @return string
         */
        View::macro('makeLoader', function () use ($app) {
            $view = $this->getName();
            $path = $this->getPath();
            $id = md5($this->getCompiled());
            $compiled_path = $app['config']['view.compiled'];

            $content = "<?= \\view('{$view}', \$data ?? get_defined_vars())->render(); ?>"
                ."\n<?php /**PATH {$path} ENDPATH**/ ?>";

            if (! file_exists($loader = "{$compiled_path}/{$id}-loader.php")) {
                file_put_contents($loader, $content);
            }

            return $loader;
        });
    }
}
