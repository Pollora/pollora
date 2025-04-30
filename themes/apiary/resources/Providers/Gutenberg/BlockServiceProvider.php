<?php

namespace Theme\Providers\Gutenberg;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;
use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

class BlockServiceProvider extends ServiceProvider
{
    public function register()
    {
        Action::add('init', [$this, 'registerBlocks']);
        Action::remove('acf_block_render_template', 'acf_block_render_template', 10, 6);
        Action::add('acf_block_render_template', [$this, 'renderTemplate'], 9, 6);
        Filter::add('block_categories_all', [$this, 'registerThemeBlockCategories']);
    }

    /**
     * @throws EntryNotFoundException
     */
    public function registerThemeBlockCategories($categories): array
    {
        $categories = collect($categories);
        foreach (array_reverse(config('gutenberg.categories.blocks')) as $key => $args) {
            $args['slug'] = ! isset($args['slug']) ? $key : $args['slug'];
            $args['title'] = $args['label'] ?? $args['title'];
            $categories->prepend($args);
        }

        return $categories->toArray();
    }

    public function renderTemplate($block, $content, $is_preview, $post_id, $wp_block, $context)
    {
        if (! str_contains($block['render_template'], '.blade.php')) {
            Action::add('acf_block_render_template', 'acf_block_render_template', 10, 6);
        }

        $bladeView = str_replace(['.blade.php', DS], ['', '.'], $block['render_template']);

        if (view()->exists($bladeView)) {
            echo view($bladeView);
        }
    }

    public function registerBlocks()
    {
        $theme = $this->app->make('wp.theme');

        foreach (Finder::create()->files()->name('block.json')->in($theme->getPath('views'.DS.'blocks'))->sortByName() as $file) {
            /** @var \SplFileInfo $file */
            $path = $file->getPath();
            $args = [];
            if (file_exists($path.DS.'config.php')) {
                $args = include $path.DS.'config.php';
                register_block_type($path, $args);
            }
        }
    }
}
