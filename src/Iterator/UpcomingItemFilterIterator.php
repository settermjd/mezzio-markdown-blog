<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use DateTime;
use FilterIterator;
use Iterator;
use MarkdownBlog\Entity\BlogArticle;
use Override;

/**
 * @template-implements \FilterIterator
 */
final class UpcomingItemFilterIterator extends FilterIterator
{
    public function __construct(Iterator $iterator)
    {
        parent::__construct($iterator);
        $this->rewind();
    }

    /**
     * Determine if the current episode has a publish date of later than today.
     */
    #[Override]
    public function accept(): bool
    {
        /** @var BlogArticle $episode */
        $episode = $this->getInnerIterator()?->current();

        return $episode instanceof BlogArticle
            ? $episode->getPublishDate() > new DateTime()
            : false;
    }
}
