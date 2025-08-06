<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Iterator;

use DateTime;
use FilterIterator;
use Iterator;
use Settermjd\MarkdownBlog\Entity\BlogArticle;

/**
 * @template TKey of int
 * @template TValue of BlogArticle
 * @template TIterator of Iterator
 * @template-extends FilterIterator<TKey,TValue,TIterator>
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
        $episode = $this->getInnerIterator()->current();

        return $episode->getPublishDate() > new DateTime();
    }
}
