<?php

namespace Theme\Core\ViteJs;

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

class AssetLoader
{
    use Helpers;

    /**
     * Get last segment in theme path for use in public path.
     */
    public function getLastPath($path)
    {
        $publicPath = get_template_directory_uri();
        // Last segment in public path.
        $lastSegment = explode('/', $publicPath);
        $lastSegment = end($lastSegment);
        // Split path from last segment.
        $path = explode($lastSegment, $path);
        $path = count($path) > 0 ? $path[1] : '';

        return $path;
    }

    /**
     * Get path after wp-content.
     */
    public function getWpPath($path)
    {
        // Split path from wp-content.
        $_path_ = explode('/content/', $path);

        return count($_path_) > 0 ? $_path_[1] : '';
    }

    /**
     * Enqueue scripts.
     */
    public function addScript($slug, $path, $port, $location)
    {
        // For theme.
        $publicPath = get_template_directory_uri();
        $dirPath = $this->getLastPath($path);

        if (config('app.env') !== 'local') {
            // Get files name list from manifest.
            $config = $this->getManifest(substr($dirPath, 1).'/dist/'.$location.'/manifest.json');

            if (! $config) {
                return;
            }
            // Load others files.
            $files = get_object_vars($config);
            // Order files.
            $ordered = $this->orderManifest($files);

            // Loop for enqueue script.
            foreach ($ordered as $key => $value) {
                wp_enqueue_script($slug.'-'.$key, $publicPath.$dirPath.'/dist/'.$location.'/'.$value->file, [], $key, true);
            }
        } else {
            // Development.
            wp_enqueue_script($slug, 'https://localhost:'.$port.'/content/'.$this->getWpPath($path).'/assets/'.$location.'.js', [], strtotime('now'), true);
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueueScripts($slug, $path, $port, $location)
    {
        // Update script tag with module attribute.
        Filter::add('script_loader_tag', function ($tag, $handle, $src) use ($slug) {
            if (strpos($handle, $slug) === false) {
                return $tag;
            }
            // Change the script tag by adding type="module" and return it.
            $tag = '<script type="module" crossorigin src="'.esc_url($src).'"></script>';

            return $tag;
        }, 10, 3);

        $wpAction = $this->getWpHook($location);

        Action::add($wpAction, function () use ($slug, $path, $port, $location) {
            $this->addScript($slug, $path, $port, $location);
        });
    }

    public function getWpHook($location)
    {
        return match ($location) {
            'login' => 'login_enqueue_scripts',
            'customizer' => 'customize_preview_init',
            'backend' => 'admin_enqueue_scripts',
            'editor' => 'enqueue_block_editor_assets',
            default => 'wp_enqueue_scripts',
        };
    }

    /**
     * Register the CSS
     */
    public function enqueueStyles($slug, $path, $location)
    {
        if (! file_exists($path.'/dist/'.$location.'/manifest.json')) {
            return;
        }

        Action::add(
            $this->getWpHook($location),
            function () use ($slug, $path, $location) {
                // Theme path.
                $publicPath = get_template_directory_uri();
                $dirPath = $this->getLastPath($path);

                if (config('app.env') !== 'local') {
                    // Get file name from manifest.
                    $config = $this->getManifest(substr($dirPath, 1).'/dist/'.$location.'/manifest.json');
                    if (! $config) {
                        return;
                    }
                    $files = get_object_vars($config);

                    // Order files.
                    $ordered = $this->orderManifest($files);
                    // Search CSS key.
                    foreach ($ordered as $key => $value) {
                        // Only entry and css.
                        if (property_exists($value, 'css') === false) {
                            continue;
                        }
                        $css = $value->css;
                        // $css is array.
                        foreach ($css as $file) {
                            // Get token file.
                            $token = $this->getTokenName($file);
                            wp_enqueue_style(
                                $slug.'-'.$key,
                                $publicPath.$dirPath.'/dist/'.$location.'/'.$file,
                                [],
                                $key,
                                'all'
                            );
                        }
                    }
                }
            }
        );
    }

    public function loadAssets($slug = 'themosis/frontend', $path = false, $port = '8000', $location = 'frontend')
    {
        $this->enqueueScripts($slug, $path, $port, $location);
        $this->enqueueStyles($slug, $path, $location);
    }
}
