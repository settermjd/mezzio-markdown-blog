<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use DirectoryIterator;
use FilterIterator;
use Iterator;
use Override;
use SplFileInfo;

use function in_array;

/**
 * @template TKey of int
 * @template TValue of SplFileInfo
 * @template TIterator of Iterator
 * @template-extends FilterIterator<TKey,TValue,TIterator>
 */
final class MarkdownFileFilterIterator extends FilterIterator
{
    public function __construct(DirectoryIterator $iterator)
    {
        parent::__construct($iterator);
        $this->rewind();
    }

    /**
     * Determine what is a valid element in this iterator.
     */
    #[Override]
    public function accept(): bool
    {
        $item = $this->getInnerIterator()->current();

        if (! $item->isFile() || ! $item->isReadable()) {
            return false;
        }

        if (! in_array($item->getExtension(), ['md', 'markdown'])) {
            return false;
        }

        return true;
    }
}
