<?php

declare(strict_types=1);

namespace App\Themes\Apiary\Walkers;

class MenuPrimary extends \Walker_Nav_Menu
{
    /**
     * Store the current item for later use.
     */
    protected $currentItem = null;

    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0): void
    {
        if ($depth === 0) {
            $this->currentItem = $item;
        }

        if (! is_array($item->classes)) {
            $item->classes = [$item->classes];
        }

        $hasChildren = in_array('menu-item-has-children', $item->classes);

        if ($hasChildren && $depth === 0) {
            $output .= $this->createDropdownParent($item, $args);
        } else {
            $output .= $this->createMenuItem($item, $depth, $args);
        }
    }

    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = [], $id = 0): void
    {
        if (in_array('menu-item-has-children', $item->classes) && $depth === 0) {
            $output .= '</div></div>';
        }
    }

    /**
     * Starts the list wrapper.
     */
    public function start_lvl(&$output, $depth = 0, $args = []): void
    {
        if ($depth === 0) {
            $output .= '<div x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="md:absolute right-0 w-full mt-2 origin-top-right rounded-md md:shadow-lg md:w-48 z-30">
                        <div class="md:px-2 md:py-2 bg-white rounded-md md:shadow divide-y divide-gray-100"><div>';
        }
    }

    /**
     * Ends the list wrapper.
     */
    public function end_lvl(&$output, $depth = 0, $args = []): void
    {
        if ($depth === 0) {
            $parent_item = $this->currentItem;
            $output .= '</div>';
            $output .= sprintf(
                '<a href="%s" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            %s %s
                        </a>',
                esc_url($parent_item->url),
                __('Access to'),
                $parent_item->title
            );
            $output .= '</div>';
        }
    }

    /**
     * Creates a dropdown parent element.
     */
    protected function createDropdownParent($item, $args): string
    {
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'relative';

        return sprintf(
            '<div @click.away="open = false" class="%s" x-data="{ open: false }">
                <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-2 mt-2 text-sm font-semibold text-left bg-transparent rounded-lg md:w-auto md:inline md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <span>%s</span>
                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{\'rotate-180\': open, \'rotate-0\': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>',
            implode(' ', $classes),
            $item->title
        );
    }

    /**
     * Creates a regular menu item.
     */
    protected function createMenuItem($item, $depth, $args): string
    {
        $classes = empty($item->classes) ? [] : (array) $item->classes;

        // Classes de base pour les liens
        if ($depth === 0) {
            $classes[] = 'px-4 py-2 mt-2 text-sm font-semibold text-gray-900 bg-transparent rounded-lg  md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline';
        } else {
            $classes[] = 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white';
        }

        $atts = [
            'href' => !empty($item->url) ? $item->url : '#',
            'title' => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target) ? $item->target : '',
            'rel' => !empty($item->xfn) ? $item->xfn : '',
            'class' => implode(' ', $classes)
        ];

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        return sprintf('<a%s>%s</a>', $attributes, $item->title);
    }
}
