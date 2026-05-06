<?php

function wrapper_open(string $classes = '', string $tag = 'div', bool $echo = true)
{
    $html = "<{$tag} class=\"{$classes}\">";
    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}

function wrapper_close(string $tag = 'div', bool $echo = true)
{
    $tag = $tag === '' ? 'div' : $tag;
    $html = "</{$tag}>";
    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}
