<?php

declare(strict_types=1);

namespace Theme\Buzz\Cms\Bindings;

use Pollora\Attributes\BlockBinding;
use Pollora\Attributes\BlockBinding\BindingField;
use Pollora\BlockBinding\Domain\Models\BindingContext;

/**
 * What the byline says about an article, computed from the post itself.
 *
 * Bound in the article and index patterns:
 * "metadata": {"bindings": {"content": {"source": "buzz/article", "args": {"field": "reading_time"}}}}
 */
#[BlockBinding('buzz/article', label: 'Article')]
final class ArticleBinding
{
    /**
     * Words read in a minute, for the reading time.
     */
    private const int WORDS_PER_MINUTE = 230;

    #[BindingField(label: 'Reading time')]
    public function readingTime(BindingContext $context): ?string
    {
        $words = $this->wordCount($context);

        if ($words === null) {
            return null;
        }

        $minutes = max(1, (int) ceil($words / self::WORDS_PER_MINUTE));

        return sprintf(_n('%d min read', '%d min read', $minutes, 'buzz'), $minutes);
    }

    #[BindingField(label: 'Word count')]
    public function wordCount(BindingContext $context): ?int
    {
        $post = $context->post();

        if ($post === null) {
            return null;
        }

        $text = wp_strip_all_tags(strip_shortcodes((string) $post->post_content));

        return count(preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: []);
    }
}
