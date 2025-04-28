<?php

declare(strict_types=1);

namespace MarkdownBlogTest\Entity;

use DateTime;
use MarkdownBlog\Entity\BlogArticle;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \MarkdownBlog\Entity\BlogArticle
 */
final class BlogArticleTest extends TestCase
{
    #[DataProvider('blogArticleDataProvider')]
    public function testCanHydrateBlogArticleCorrectly(array $input, array $output): void
    {
        $blogArticle = new BlogArticle();
        $blogArticle->populate($input);

        $this->assertEquals(new DateTime((string) $input['publishDate']), $blogArticle->getPublishDate());
        $this->assertEquals($output['slug'], $blogArticle->getSlug());
        $this->assertEquals($output['synopsis'], $blogArticle->getSynopsis());
        $this->assertEquals($output['title'], $blogArticle->getTitle());
        $this->assertEquals($output['content'], $blogArticle->getContent());
        $this->assertEquals($output['image'], $blogArticle->getImage());
        $this->assertEquals($output['categories'], $blogArticle->getCategories());
        $this->assertEquals($output['tags'], $blogArticle->getTags());
    }

    /**
     * @return (string|string[])[][][]
     */
    public static function blogArticleDataProvider(): array
    {
        return [
            [
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "blogArticle-001",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
In this blogArticle, I have a fireside chat with internationally recognized PHP expert, and all around good fella [Paul M. Jones](http://paul-m-jones.com), about one of his all-time favorite books - [The Mythical Man Month](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959).

We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.

I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, **and much, much more**.

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF,
                    "synopsis"    => 'In this blogArticle, I have a fireside chat with internationally recognized PHP expert Paul M. Jones about one of his all-time favorite books, The Mythical Man Month.',
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => ['PHP', 'Docker'],
                    'categories'  => ['Software Development'],
                ],
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "blogArticle-001",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
<p>In this blogArticle, I have a fireside chat with internationally recognized PHP expert, and all around good fella <a href="http://paul-m-jones.com">Paul M. Jones</a>, about one of his all-time favorite books - <a href="http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959">The Mythical Man Month</a>.</p>

<p>We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.</p>

<p>I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, <strong>and much, much more</strong>.</p>

<blockquote>
  <p><strong>Correction:</strong> Thanks to <a href="https://twitter.com/@asgrim">@asgrim</a> for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.</p>
</blockquote>

EOF,
                    "synopsis"    => 'In this blogArticle, I have a fireside chat with internationally recognized PHP expert Paul M. Jones about one of his all-time favorite books, The Mythical Man Month.',
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => ['PHP', 'Docker'],
                    'categories'  => ['Software Development'],
                ],
            ],
            [
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "blogArticle-001",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
### Synopsis

In this blogArticle, I have a fireside chat with internationally recognized PHP expert, and all around good fella [Paul M. Jones](http://paul-m-jones.com), about one of his all-time favorite books - [The Mythical Man Month](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959).

We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.

I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, **and much, much more**.

### Related Links

- [The E-Myth Revisited by Michael E. Gerber](http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK)
- [How to Network – Even if You’re Self-Conscious](http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/)

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF,
                    "synopsis"    => "In this blogArticle, I have a fireside chat with internationally recognized PHP expert Paul M. Jones, about one of his all-time favorite books, The Mythical Man Month. We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975. I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, and much, much more.",
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => ['PHP', 'Docker'],
                    'categories'  => ['Software Development'],
                ],
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "blogArticle-001",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
<h3>Synopsis</h3>

<p>In this blogArticle, I have a fireside chat with internationally recognized PHP expert, and all around good fella <a href="http://paul-m-jones.com">Paul M. Jones</a>, about one of his all-time favorite books - <a href="http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959">The Mythical Man Month</a>.</p>

<p>We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.</p>

<p>I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, <strong>and much, much more</strong>.</p>

<h3>Related Links</h3>

<ul>
<li><a href="http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK">The E-Myth Revisited by Michael E. Gerber</a></li>
<li><a href="http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/">How to Network – Even if You’re Self-Conscious</a></li>
</ul>

<blockquote>
  <p><strong>Correction:</strong> Thanks to <a href="https://twitter.com/@asgrim">@asgrim</a> for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.</p>
</blockquote>

EOF,
                    "synopsis"    => "In this blogArticle, I have a fireside chat with internationally recognized PHP expert Paul M. Jones, about one of his all-time favorite books, The Mythical Man Month. We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975. I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, and much, much more.",
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => ['PHP', 'Docker'],
                    'categories'  => ['Software Development'],
                ],
            ],
            [
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "blogArticle-001",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
### Related Links

- [The E-Myth Revisited by Michael E. Gerber](http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK)
- [How to Network – Even if You’re Self-Conscious](http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/)

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF,
                    "synopsis"    => '',
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => [],
                    'categories'  => [],
                ],
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "blogArticle-001",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
<h3>Related Links</h3>

<ul>
<li><a href="http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK">The E-Myth Revisited by Michael E. Gerber</a></li>
<li><a href="http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/">How to Network – Even if You’re Self-Conscious</a></li>
</ul>

<blockquote>
  <p><strong>Correction:</strong> Thanks to <a href="https://twitter.com/@asgrim">@asgrim</a> for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.</p>
</blockquote>

EOF,
                    "synopsis"    => '',
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => [],
                    'categories'  => [],
                ],
            ],
            [
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "blogArticle-001",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
### Related Links

- [The E-Myth Revisited by Michael E. Gerber](http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK)
- [How to Network – Even if You’re Self-Conscious](http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/)

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF,
                    "synopsis"    => '',
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => [],
                    'categories'  => [],
                ],
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "blogArticle-001",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
<h3>Related Links</h3>

<ul>
<li><a href="http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK">The E-Myth Revisited by Michael E. Gerber</a></li>
<li><a href="http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/">How to Network – Even if You’re Self-Conscious</a></li>
</ul>

<blockquote>
  <p><strong>Correction:</strong> Thanks to <a href="https://twitter.com/@asgrim">@asgrim</a> for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.</p>
</blockquote>

EOF,
                    "synopsis"    => '',
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => [],
                    'categories'  => [],
                ],
            ],
        ];
    }
}
