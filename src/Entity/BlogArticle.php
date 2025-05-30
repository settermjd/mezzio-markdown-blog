<?php

declare(strict_types=1);

namespace MarkdownBlog\Entity;

use DateTime;
use Michelf\MarkdownExtra;

use function array_key_exists;
use function get_class_vars;

final class BlogArticle
{
    private DateTime $publishDate;
    private string $slug     = '';
    private string $title    = '';
    private string $image    = '';
    private string $synopsis = '';
    private string $content  = '';

    /** @var string[] */
    private array $categories = [];

    /** @var string[] */
    private array $tags = [];

    /**
     * @param array<string,list<string|null>|string> $options
     */
    public function __construct(array $options = [])
    {
        $this->populate($options);
    }

    /**
     * @param array<string,string> $options
     */
    public function populate(array $options = []): void
    {
        $properties = get_class_vars(self::class);
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $properties) && ! empty($value)) {
                $this->$key = $key === 'publishDate'
                    ? new DateTime($value)
                    : $value;
            }
        }
    }

    /**
     * Returns a \DateTime object, which can be used to determine the publish date.
     */
    public function getPublishDate(): DateTime
    {
        return $this->publishDate;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        $markdownParser = new MarkdownExtra();
        return $markdownParser->defaultTransform($this->content);
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getSynopsis(): string
    {
        return $this->synopsis ?? '';
    }
}
