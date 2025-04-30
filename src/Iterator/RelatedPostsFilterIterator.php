<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use FilterIterator;
use Iterator;
use MarkdownBlog\Entity\BlogArticle;
use Override;

use function array_intersect;

/**
 * @template TKey of int
 * @template TValue of BlogArticle
 * @template TIterator of Iterator
 * @template-extends FilterIterator<TKey,TValue,TIterator>
 */
final class RelatedPostsFilterIterator extends FilterIterator
{
    private BlogArticle $blogArticle;

    public function __construct(Iterator $iterator, BlogArticle $blogArticle)
    {
        parent::__construct($iterator);

        $this->blogArticle = $blogArticle;
    }

    /**
     * Allow an article if it has any of the same tags or categories but doesn't have the same slug,
     * i.e, isn't the same article.
     */
    #[Override]
    public function accept(): bool
    {
        $post = $this->getInnerIterator()->current();

        $matchingTags       = array_intersect($post->getTags(), $this->blogArticle->getTags());
        $matchingCategories = array_intersect($post->getCategories(), $this->blogArticle->getCategories());

        return $this->blogArticle->getSlug() !== $post->getSlug()
            && (! empty($matchingTags) || ! empty($matchingCategories));
    }
}
