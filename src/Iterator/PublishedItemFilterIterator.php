<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use Countable;
use DateTime;
use Exception;
use FilterIterator;
use Iterator;
use MarkdownBlog\Entity\BlogArticle;
use Override;

use function iterator_count;

/**
 * @template TKey of int
 * @template TValue of BlogArticle
 * @template TIterator of Iterator
 * @template-extends FilterIterator<TKey,TValue,TIterator>
 */
final class PublishedItemFilterIterator extends FilterIterator implements Countable
{
    public function __construct(Iterator $iterator)
    {
        parent::__construct($iterator);
        $this->rewind();
    }

    /**
     * Determine if the current item has a publish date of later than today.
     *
     * @throws Exception
     */
    #[Override]
    public function accept(): bool
    {
        $episode = $this->getInnerIterator()->current();

        return $episode->getPublishDate() <= new DateTime();
    }

    public function count(): int
    {
        return iterator_count($this->getInnerIterator());
    }
}
