<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use DirectoryIterator;
use FilterIterator;
use Override;
use SplFileInfo;

use function in_array;

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
        /** @var SplFileInfo $item */
        $item = $this->getInnerIterator()->current();

        if (! $item instanceof SplFileInfo) {
            return false;
        }

        if (! $item->isFile() || ! $item->isReadable()) {
            return false;
        }

        if (! in_array($item->getExtension(), ['md', 'markdown'])) {
            return false;
        }

        return true;
    }
}
