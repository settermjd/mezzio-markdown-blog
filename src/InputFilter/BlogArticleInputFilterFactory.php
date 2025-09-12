<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\InputFilter;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\IsCountable;
use Laminas\Validator\Regex;

final class BlogArticleInputFilterFactory
{
    public const string FILTER_REGEX = '/\d{4}\-\d{2}\-\d{2}|(\d{2}\.){2}\d{4}/';

    /**
     * @psalm-return InputFilter<mixed>
     */
    public function __invoke(): InputFilter
    {
        $publishDate = new Input('publishDate');
        $publishDate
            ->getValidatorChain()
            ->attach(new Regex(
                [
                    'pattern' => self::FILTER_REGEX,
                ]
            ));
        $publishDate
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $categories = new Input('categories');
        $categories
            ->getValidatorChain()
            ->attach(new IsCountable());

        $tags = new Input('tags');
        $tags->setAllowEmpty(true);
        $tags->setRequired(false);
        $tags
            ->getValidatorChain()
            ->attach(new IsCountable());

        $slug = new Input('slug');
        $slug
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $synopsis = new Input('synopsis');
        $synopsis
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $title = new Input('title');
        $title
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $image = new Input('image');
        $image->setAllowEmpty(true);
        $image->setRequired(false);
        $image
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $content = new Input('content');
        $content
            ->getFilterChain()
            ->attach(new StringTrim());

        return (new InputFilter())
            ->add($publishDate)
            ->add($slug)
            ->add($synopsis)
            ->add($title)
            ->add($image)
            ->add($content)
            ->add($tags)
            ->add($categories);
    }
}
