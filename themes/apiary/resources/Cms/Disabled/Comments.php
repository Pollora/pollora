<?php

namespace Theme\Cms\Disabled;

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

class Comments
{
    public function __construct()
    {
        if (! config('disable.comment')) {
            return;
        }

        // Disable comments.
        Action::add('admin_init', [$this, 'adminDisable']);

        // Remove comments page in menu
        Action::add('admin_menu', [$this, 'disableAdminMenu']);

        // Close comments on the front-end
        Filter::add('comments_open', '__return_false', 20, 2);
        Filter::add('pings_open', '__return_false', 20, 2);

        // Hide existing comments
        Filter::add('comments_array', '__return_empty_array', 10, 2);

        // Remove comment link from admin bar
        Action::add('wp_before_admin_bar_render', [$this, 'disableAdminBarMenu']);

        // Disables comments feeds.
        Filter::add('feed_links_show_comments_feed', '__return_false');
        Filter::add('do_feed_rss2_comments', __NAMESPACE__.'\disable_feeds', 1);
        Filter::add('do_feed_atom_comments', __NAMESPACE__.'\disable_feeds', 1);
    }

    public function adminDisable()
    {
        // Redirect any user trying to access comments page
        global $pagenow;

        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }

        // Remove comments metabox from dashboard
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        // Disable support for comments and trackbacks in post types
        foreach (get_post_types() as $post_type) {
            if (! post_type_supports($post_type, 'comments')) {
                continue;
            }
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }

    public function disableAdminMenu()
    {
        remove_menu_page('edit-comments.php'); // Remove Settings -> Discussion
        remove_submenu_page('options-general.php', 'options-discussion.php');
    }

    public function disableAdminBarMenu()
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    }
}
