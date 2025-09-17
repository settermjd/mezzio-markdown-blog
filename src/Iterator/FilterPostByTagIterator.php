<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Iterator;

use FilterIterator;
use Iterator;
use Settermjd\MarkdownBlog\Entity\BlogArticle;

use function array_filter;
use function array_map;
use function in_array;
use function strtolower;

/**
 * @template TKey of int
 * @template TValue of BlogArticle
 * @template TIterator of Iterator
 * @template-extends FilterIterator<TKey,TValue,TIterator>
 */
final class FilterPostByTagIterator extends FilterIterator
{
    private string $tag;

    public function __construct(Iterator $iterator, string $tag)
    {
        parent::__construct($iterator);
        $this->tag = strtolower($tag);
    }

    /**
     * Filter out articles that don't have a matching category.
     * A lowercase comparison is made to reduce the likelihood of false negative matches.
     */
    public function accept(): bool
    {
        $post = $this->getInnerIterator()->current();

        // Filter out empty/null entries, which will break array_map's use of strtolower
        $tags = array_filter($post->getTags());

        if (! empty($tags)) {
            return in_array($this->tag, array_map('strtolower', $tags));
        }

        return false;
    }
}
