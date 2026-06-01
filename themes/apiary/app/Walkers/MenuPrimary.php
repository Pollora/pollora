<?php

declare(strict_types=1);

namespace Theme\Apiary\Walkers;

/**
 * Custom Walker for the primary navigation menu.
 *
 * Renders both desktop and mobile layouts from a single Walker, controlled
 * by the `'mobile' => true` argument passed to `wp_nav_menu()`.
 *
 * **Desktop**: horizontal nav bar with flyout dropdowns (Alpine.js `x-data`,
 * `@mouseenter`/`@mouseleave`), animated underline on the active item,
 * and a "See all" footer link in each dropdown.
 *
 * **Mobile**: vertical accordion layout using Alpine.js `x-collapse` for
 * smooth expand/collapse, with indented child items and a "See all" link.
 *
 * Uses the theme's Tailwind design tokens (`text-foreground`, `text-muted`,
 * `bg-surface`, `border-outline`, `text-primary`, etc.) for consistent styling.
 *
 * @see parts/header/navigation.blade.php  Desktop nav include
 * @see parts/header/mobile-nav.blade.php  Mobile nav include
 */
class MenuPrimary extends \Walker_Nav_Menu
{
    /** @var \WP_Post|null The current top-level menu item (for "See all" links). */
    protected $currentItem = null;

    /** @var bool Whether the walker is rendering the mobile variant. */
    protected bool $isMobile = false;

    /**
     * Starts the element output.
     *
     * Delegates to {@see createDropdownParent()} for top-level items with children,
     * or {@see createMenuItem()} for regular links (any depth).
     *
     * @param  string         $output  Passed by reference. Markup to append to.
     * @param  \WP_Post       $item    Menu item data object.
     * @param  int            $depth   Depth of menu item (0 = top-level).
     * @param  array|object   $args    wp_nav_menu() arguments. `$args->mobile` triggers mobile mode.
     * @param  int            $id      Current item ID (unused).
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0): void
    {
        if ($depth === 0) {
            $this->currentItem = $item;
        }

        // Detect mobile from args (passed via wp_nav_menu 'mobile' => true)
        if (is_object($args) && !empty($args->mobile)) {
            $this->isMobile = true;
        } elseif (is_array($args) && !empty($args['mobile'])) {
            $this->isMobile = true;
        }

        if (! is_array($item->classes)) {
            $item->classes = [$item->classes];
        }

        $hasChildren = in_array('menu-item-has-children', $item->classes);
        $isCurrent = in_array('current-menu-item', $item->classes)
            || in_array('current-menu-parent', $item->classes)
            || in_array('current-menu-ancestor', $item->classes);

        if ($hasChildren && $depth === 0) {
            $output .= $this->createDropdownParent($item, $isCurrent);
        } else {
            $output .= $this->createMenuItem($item, $depth, $isCurrent);
        }
    }

    /**
     * Ends the element output.
     *
     * Closes the dropdown wrapper `<div>` for top-level items with children.
     */
    public function end_el(&$output, $item, $depth = 0, $args = [], $id = 0): void
    {
        if (in_array('menu-item-has-children', $item->classes ?? []) && $depth === 0) {
            // Close the dropdown wrapper opened in createDropdownParent
            $output .= '</div>';
        }
    }

    /**
     * Starts the list of child items.
     *
     * Desktop: opens a positioned flyout panel with transition animations.
     * Mobile: opens a collapsible accordion panel with `x-collapse`.
     *
     * Only handles depth 0 (single-level dropdowns).
     */
    public function start_lvl(&$output, $depth = 0, $args = []): void
    {
        if ($depth !== 0) {
            return;
        }

        if ($this->isMobile) {
            // Accordion panel with x-collapse for smooth height animation
            $output .= '<div x-show="open" x-collapse class="mt-1 ml-3 pl-3 border-l-2 border-outline/60 space-y-0.5">';
        } else {
            // Desktop flyout dropdown
            $output .= '<div x-show="open" x-cloak
                            @mouseenter="open = true"
                            @mouseleave="open = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 top-full w-56 rounded-xl bg-white shadow-lg shadow-black/5 ring-1 ring-outline/80 z-30">
                         <div class="p-2">';
        }
    }

    /**
     * Ends the list of child items.
     *
     * Appends a "See all {category}" link at the bottom of the dropdown,
     * linking to the parent item's URL. Closes the panel markup.
     */
    public function end_lvl(&$output, $depth = 0, $args = []): void
    {
        if ($depth !== 0) {
            return;
        }

        $parent = $this->currentItem;

        if ($this->isMobile) {
            // "See all" link inside the accordion
            $output .= sprintf(
                '<a href="%s" class="flex items-center gap-1 px-3 py-2 mt-1 text-xs font-semibold text-primary hover:text-primary-hover transition-colors">
                    <span>%s %s</span>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>',
                esc_url($parent->url),
                esc_html__('See all', 'apiary'),
                esc_html($parent->title)
            );
            $output .= '</div>';
        } else {
            // Close items container, add separator + "See all" link
            $output .= '</div>';
            $output .= sprintf(
                '<div class="border-t border-outline/60 p-2">
                    <a href="%s" class="flex items-center justify-between px-3 py-2 text-xs font-semibold text-primary hover:bg-surface rounded-lg transition-colors">
                        <span>%s %s</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>',
                esc_url($parent->url),
                esc_html__('See all', 'apiary'),
                esc_html($parent->title)
            );
            $output .= '</div>';
        }
    }

    /**
     * Creates a dropdown parent element (depth 0 item with children).
     *
     * Desktop: a hoverable button with chevron, positioned relative for the flyout.
     * Mobile: an accordion toggle button with animated chevron rotation.
     *
     * @param  \WP_Post  $item       Menu item data object.
     * @param  bool      $isCurrent  Whether this item or an ancestor is active.
     * @return string Opening HTML (the closing `</div>` is handled by {@see end_el()}).
     */
    protected function createDropdownParent($item, bool $isCurrent): string
    {
        if ($this->isMobile) {
            $activeClass = $isCurrent ? 'text-primary bg-surface' : 'text-foreground';

            return sprintf(
                '<div x-data="{ open: false }" class="mb-0.5">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-surface-alt transition-colors focus:outline-hidden %s">
                        <span>%s</span>
                        <svg :class="open && \'rotate-180\'" class="w-4 h-4 text-subtle transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>',
                $activeClass,
                esc_html($item->title)
            );
        }

        $textClass = $isCurrent ? 'text-foreground' : 'text-muted';
        $underlineScale = $isCurrent ? 'after:scale-x-100' : 'after:scale-x-0 hover:after:scale-x-100';

        return sprintf(
            '<div class="relative" x-data="{ open: false, timer: null }"
                 @mouseenter="clearTimeout(timer); open = true"
                 @mouseleave="timer = setTimeout(() => open = false, 150)">
                <button @click="open = !open"
                        class="group inline-flex items-center gap-1 px-3 py-4 text-sm font-medium %s hover:text-foreground transition-colors relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-primary %s after:transition-transform after:origin-left focus:outline-hidden">
                    <span>%s</span>
                    <svg :class="open && \'rotate-180\'" class="w-3.5 h-3.5 text-subtle group-hover:text-foreground transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>',
            $textClass,
            $underlineScale,
            esc_html($item->title)
        );
    }

    /**
     * Creates a regular menu item `<a>` link.
     *
     * Applies different Tailwind classes based on depth (top-level vs child)
     * and mode (desktop vs mobile), with active-state highlighting.
     *
     * @param  \WP_Post  $item       Menu item data object.
     * @param  int       $depth      Nesting depth (0 = top-level, 1+ = dropdown child).
     * @param  bool      $isCurrent  Whether this item or an ancestor is active.
     * @return string Complete `<a>` element HTML.
     */
    protected function createMenuItem($item, int $depth, bool $isCurrent): string
    {
        $atts = [
            'href' => !empty($item->url) ? $item->url : '#',
            'title' => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target) ? $item->target : '',
            'rel' => !empty($item->xfn) ? $item->xfn : '',
        ];

        if ($depth === 0) {
            // Top-level item (no children)
            if ($this->isMobile) {
                $activeClass = $isCurrent ? 'text-primary bg-surface' : 'text-foreground';
                $atts['class'] = 'block px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-surface-alt transition-colors ' . $activeClass;
            } else {
                $textClass = $isCurrent ? 'text-foreground' : 'text-muted';
                $underlineScale = $isCurrent ? 'after:scale-x-100' : 'after:scale-x-0 hover:after:scale-x-100';
                $atts['class'] = 'inline-flex items-center px-3 py-4 text-sm font-medium ' . $textClass . ' hover:text-foreground transition-colors relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-primary ' . $underlineScale . ' after:transition-transform after:origin-left';
            }
        } else {
            // Child item (inside dropdown)
            if ($this->isMobile) {
                $activeClass = $isCurrent ? 'text-foreground font-medium' : 'text-muted';
                $atts['class'] = 'block px-3 py-2 text-sm hover:text-foreground transition-colors rounded-md ' . $activeClass;
            } else {
                $activeClass = $isCurrent ? 'text-foreground bg-surface' : 'text-muted';
                $atts['class'] = 'flex items-center gap-2 px-3 py-2 text-sm rounded-lg ' . $activeClass . ' hover:text-foreground hover:bg-surface transition-colors';
            }
        }

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        return sprintf('<a%s>%s</a>', $attributes, esc_html($item->title));
    }
}
