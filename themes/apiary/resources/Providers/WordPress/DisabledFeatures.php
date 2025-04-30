<?php

namespace Theme\Providers\WordPress;

use Illuminate\Container\EntryNotFoundException;
use Theme\Cms\Disabled\Comments as DisabledComments;
use Theme\Cms\Disabled\jQuery as DisabledjQuery;
use Theme\Cms\Disabled\Medias as DisabledMedias;
use Theme\Cms\Disabled\RestUser as DisabledRestUser;
use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

class DisabledFeatures extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Action::add('init', [$this, 'basicDisabledFeatures']);
    }

    /**
     * @throws EntryNotFoundException
     */
    public function basicDisabledFeatures()
    {
        new DisabledComments();
        new DisabledjQuery();
        new DisabledMedias();
        new DisabledRestUser();

        if (config('disable.login_display_language_dropdown')) {
            // Remove language dropdown on login screen.
            Filter::add('login_display_language_dropdown', '__return_false');
        }

        if (config('disable.wp_generator')) {
            // Removes WordPress version.
            Action::remove('wp_head', 'wp_generator');
        }

        if (config('disable.wp_site_icon')) {
            // Removes generated icons.
            Action::remove('wp_head', 'wp_site_icon', 99);
        }

        if (config('disable.wp_shortlink_wp_head')) {
            // Removes shortlink tag from <head>.
            Action::remove('wp_head', 'wp_shortlink_wp_head', 10);
        }

        if (config('disable.wp_shortlink_header')) {
            // Removes shortlink tag from HTML headers.
            Action::remove('template_redirect', 'wp_shortlink_header', 11);
        }

        if (config('disable.wlwmanifest_link')) {
            // Removes wlwmanifest.xml.
            Action::remove('wp_head', 'wlwmanifest_link');
        }

        if (config('disable.wp_resource_hints')) {
            // Removes meta rel=dns-prefetch href=//s.w.org
            Action::remove('wp_head', 'wp_resource_hints', 2);
        }

        if (config('disable.adjacent_posts_rel_link_wp_head')) {
            // Removes relational links for the posts.
            Action::remove('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        }

        if (config('disable.rest_output_link_wp_head')) {
            // Removes REST API link tag from <head>.
            Action::remove('wp_head', 'rest_output_link_wp_head', 10);
        }

        if (config('disable.rest_output_link_header')) {
            // Removes REST API link tag from HTML headers.
            Action::remove('template_redirect', 'rest_output_link_header', 11);
        }

        if (config('disable.emoji')) {
            // Removes emojis.
            Action::remove('wp_head', 'print_emoji_detection_script', 7);
            Action::remove('admin_print_scripts', 'print_emoji_detection_script');
            Action::remove('wp_print_styles', 'print_emoji_styles');
            Action::remove('admin_print_styles', 'print_emoji_styles');
            Filter::remove('the_content_feed', 'wp_staticize_emoji');
            Filter::remove('comment_text_rss', 'wp_staticize_emoji');
            Filter::remove('wp_mail', 'wp_staticize_emoji_for_email');
        }

        if (config('disable.rss')) {
            // Disables feeds.
            Action::add('do_feed', __NAMESPACE__.'\disable_feeds', 1);
            Action::add('do_feed_rdf', __NAMESPACE__.'\disable_feeds', 1);
            Action::add('do_feed_rss', __NAMESPACE__.'\disable_feeds', 1);
            Action::add('do_feed_rss2', __NAMESPACE__.'\disable_feeds', 1);
            Action::add('do_feed_atom', __NAMESPACE__.'\disable_feeds', 1);

            // Removes RSS feed links.
            Action::remove('wp_head', 'feed_links', 2);

            // Removes all extra RSS feed links.
            Action::remove('wp_head', 'feed_links_extra', 3);
        }

        if (config('disable.oembed')) {
            // Removes oEmbeds.
            Action::remove('wp_head', 'wp_oembed_add_discovery_links', 10);
            Action::remove('wp_head', 'wp_oembed_add_host_js');
        }

        if (config('disable.xmlrpc')) {
            // Disable XML RPC for security.
            Filter::add('xmlrpc_enabled', '__return_false');
            Filter::add('xmlrpc_methods', '__return_false');

            // Removes Really Simple Discovery link.
            Action::remove('wp_head', 'rsd_link');
        }
    }
}
