<?php
/**
 * Title: Design system
 * Slug: buzz/design-system
 * Categories: buzz/patterns
 * Inserter: true
 */
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|lg"}},"layout":{"type":"constrained","contentSize":"42rem"}} -->
<div class="wp-block-group alignwide">

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Design system</h4>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"lede"} -->
<p class="has-lede-font-size">Every native block, in the theme's own type and colour. Delete this page once the system has been reviewed — it ships as a pattern, not as content.</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Headings</h4>
<!-- /wp:heading -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Heading level one</h1>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Heading level two</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Heading level three</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Heading level four</h4>
<!-- /wp:heading -->

<!-- wp:heading {"level":5} -->
<h5 class="wp-block-heading">Heading level five</h5>
<!-- /wp:heading -->

<!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading">Heading level six</h6>
<!-- /wp:heading -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Paragraph &amp; drop cap</h4>
<!-- /wp:heading -->

<!-- wp:paragraph {"dropCap":true} -->
<p class="has-drop-cap">This paragraph opens with a drop cap, on by default in this theme. It carries on long enough to show how the capital letter sits against the running text, and how the body face reads across a few lines of ordinary prose set at the theme's base size.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>An ordinary paragraph, no drop cap, immediately after &#8212; for comparing the two side by side. It carries a plain link to <a href="https://pollora.dev">an external site</a>, underlined and turning to the rubric colour on hover.</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Lists</h4>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:list -->
<ul class="wp-block-list">
<!-- wp:list-item --><li>A bulleted item</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>A second item</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>A third, slightly longer item to see how the line wraps</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list">
<!-- wp:list-item --><li>First step</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Second step</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Third step</li><!-- /wp:list-item -->
</ol>
<!-- /wp:list -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Quote &amp; pull quote</h4>
<!-- /wp:heading -->

<!-- wp:quote -->
<blockquote class="wp-block-quote">
<!-- wp:paragraph -->
<p>A quotation set in the display face, italic, with a rule down the left — used inline, at running width.</p>
<!-- /wp:paragraph -->
<cite>A source, named</cite>
</blockquote>
<!-- /wp:quote -->

<!-- wp:pullquote -->
<figure class="wp-block-pullquote"><blockquote><p>A pull quote breaks the column and carries a rule above and below — reserved for the one line a page wants a reader to stop on.</p></blockquote></figure>
<!-- /wp:pullquote -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Table</h4>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table">
<table>
<thead><tr><th>Column one</th><th>Column two</th><th>Column three</th></tr></thead>
<tbody>
<tr><td>Row one</td><td>A value</td><td>Another value</td></tr>
<tr><td>Row two</td><td>A value</td><td>Another value</td></tr>
</tbody>
</table>
</figure>
<!-- /wp:table -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Code &amp; preformatted</h4>
<!-- /wp:heading -->

<!-- wp:code -->
<pre class="wp-block-code"><code>function example(): string
{
    return &quot;set in the body face's monospace fallback&quot;;
}</code></pre>
<!-- /wp:code -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Buttons</h4>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">A button</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Outline style</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":4,"fontSize":"small"} -->
<h4 class="wp-block-heading has-small-font-size">Post terms, date &amp; author (as used in patterns)</h4>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"caption"} -->
<p class="has-caption-font-size" style="text-transform:uppercase;letter-spacing:0.08em;color:var(--wp--preset--color--rubric)">Category rubric</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"caption"} -->
<p class="has-caption-font-size">&middot;</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"caption"} -->
<p class="has-caption-font-size" style="font-style:italic;color:var(--wp--preset--color--caption)">A byline date</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->
