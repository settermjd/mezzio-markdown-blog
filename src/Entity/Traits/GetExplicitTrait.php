<?php

declare(strict_types=1);

namespace MarkdownBlog\Entity\Traits;

/**
 * Trait GetExplicitTrait
 * As both the Show and BlogArticle entities use this function,
 * it's being shared via a trait.
 */
trait GetExplicitTrait
{
    protected string $explicit;

    /**
     * Is the item of an explicit nature or not.
     *
     * @return string
     */
    public function getExplicit()
    {
        return $this->explicit ?? 'no';
    }
}
