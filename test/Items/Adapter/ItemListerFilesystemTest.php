<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Items\Adapter;

use DateInterval;
use DateTime;
use Mni\FrontYAML\Parser;
use org\bovigo\vfs\vfsStream;
use Override;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;

use function sprintf;

/**
 * @coversDefaultClass ItemListerFilesystem
 */
final class ItemListerFilesystemTest extends TestCase
{
    /** @var array<string,array<string,string>> */
    private array $structure;

    #[Override]
    protected function setUp(): void
    {
        $item001Content = <<<EOF
---
publish_date: "2015"
slug: item-0001
title: Getting Underway, <a href="">The E-Myth Revisited</a>, and Networking For Success
image: http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0001.mp3
synopsis: In this, the first item, Matt talks about what lead to the podcast getting started who motivated him and inspired him to get started. After that, he discusses a fantastic book that all freelancers should read.
tags:
  - "PHP"
  - "Docker"
categories:
  - "Software Development"
---
### Synopsis

In this, the first item, Matt talks about what lead to the podcast getting started who motivated him and inspired him to get started. After that, he discusses a fantastic book that all freelancers should read.

It's one which explains how you need to approach freelancing if you want to succeed, and you want to keep your sanity; it's called the E-Myth Revisited. Finally, Matt discusses why networking is essential to success, and some of the mistakes that some of techies make.

### Related Links

- [The E-Myth Revisited by Michael E. Gerber](http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK)
- [How to Network – Even if You’re Self-Conscious](http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/)

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF;

        $item002Content = <<<EOF
---
publish_date: 03.08.2015
slug: item-0002
title: The Mythical Man Month with Paul M. Jones & Speaking Engagements
image: http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3
synopsis: In this item, I have a fireside chat with internationally recognized PHP expert, and all around good fella Paul M. Jones, about one of his all-time favorite books, The Mythical Man Month.
tags:
  - "PHP"
  - "Paul M. Jones"
  - "The Mythical Man Month"
  - "Solving the N+1 Problem in PHP"
categories:
  - "Public Speaking"
---
### Synopsis

In this item, I have a fireside chat with internationally recognized PHP expert, and all around good fella [Paul M. Jones](http://paul-m-jones.com), about one of his all-time favorite books - [The Mythical Man Month](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959).

We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.

I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, **and much, much more**.

### Related Links

- [Paul M. Jones](http://paul-m-jones.com/)
- [Modernizing Legacy Applications in PHP](http://mlaphp.com/)
- [Solving the N+1 Problem in PHP](https://leanpub.com/sn1php?utm_campaign=sn1php&utm_medium=embed&utm_source=paul-m-jones.com)
- [The Action Domain Responder Pattern](http://pmjones.io/adr/)
- [The Mythical Man Month, by Frederick P. Brooks. Jr](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959)
- [Peopleware: Productive Projects and Teams](http://www.amazon.co.uk/Peopleware-Productive-Projects-Tom-DeMarco/dp/0932633439)
- [The Inmates are Running the Asylum](http://www.amazon.co.uk/The-Inmates-are-Running-Asylum/dp/0672326140)
- [Outliers by Malcolm Gladwell](http://gladwell.com/outliers/)
- [PHP South Coast Conference](http://2015.phpsouthcoast.co.uk/)
- [PHP[World] Conference](http://world.phparch.com)
- [Nomad PHP](https://nomadphp.com)
EOF;

        $item003Content = <<<EOF
---
publish_date: %s
slug: item-0003
title: The Mythical Man Month with Paul M. Jones & Speaking Engagements
image: http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3
synopsis: In this item, I have a fireside chat with internationally recognized PHP expert, and all around good fella Paul M. Jones, about one of his all-time favorite books, The Mythical Man Month.
tags:
  - "PHP"
  - "Docker"
  - "PHP World"
categories:
  - "Software Development"
---
### Synopsis

In this item, I have a fireside chat with internationally recognized PHP expert, and all around good fella [Paul M. Jones](http://paul-m-jones.com), about one of his all-time favorite books - [The Mythical Man Month](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959).

We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.

I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, **and much, much more**.

### Related Links

- [Paul M. Jones](http://paul-m-jones.com/)
EOF;

        $futureDate     = (new DateTime())->add(new DateInterval('P3D'))->format('d.m.Y');
        $item003Content = sprintf($item003Content, $futureDate);

        $item004Content = <<<EOF
---
publish_date: %s
slug: item-0004
title: Wisdom as a Service World Tour and Human Skills - with Yitzchok Willroth
image: http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0004.mp3
synopsis: In this item, I have a fireside chat with Yitzchok Willroth, the one and only coderabbi, about a his Wisdom as a Service World Tour.
tags:
  - "PHP"
  - "Docker"
categories:
  - "Software Development"
---
### Synopsis

In this item, I have a fireside chat with Yitzchok Willroth, the one and only [coderabbi](https://twitter.com/@coderabbi), about a his [Wisdom as a Service World Tour](http://wisdomworldtour.com/).

We talk about what it's like to run the tour, the time involved, the energy required, and how it's been received. We also talk about the value of human skills (otherwise known as soft skills), the value of getting up and sharing your knowledge with the community, via public speaking, **and much, much more**.

### Related Links

- [Wisdom as a Service World Tour](http://wisdomworldtour.com/)
- [coderabbi](https://twitter.com/@coderabbi)
- [ShorePHP User Group](http://shorephp.org/)
- [NYPHP User Group](http://nyphp.org/)
EOF;

        $futureDate     = (new DateTime())->add(new DateInterval('P5D'))->format('d.m.Y');
        $item004Content = sprintf($item004Content, $futureDate);

        $this->structure = [
            'posts' => [
                'item-0001.md' => $item001Content,
                'item-0002.md' => $item002Content,
                'item-0003.md' => $item003Content,
                'item-0004.md' => $item004Content,
            ],
        ];
        vfsStream::setup('root', null, $this->structure);
    }

    public function testCanRetrieveASortedUniqueListOfCategories(): void
    {
        $this->setUp();

        vfsStream::setup('root', null, $this->structure);

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
            null,
            null
        );

        $categories = $itemLister->getCategories();
        $this->assertCount(2, $categories);
        $this->assertSame(
            [
                'Public Speaking',
                'Software Development',
            ],
            $categories,
        );
    }

    public function testCanRetrieveASortedUniqueListOfTags(): void
    {
        $this->setUp();

        vfsStream::setup('root', null, $this->structure);

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
            null,
            null
        );

        $tags = $itemLister->getTags();
        $this->assertCount(6, $tags);
        $this->assertEqualsCanonicalizing(
            [
                "Docker",
                "PHP",
                "PHP World",
                "Paul M. Jones",
                "Solving the N+1 Problem in PHP",
                "The Mythical Man Month",
            ],
            $tags,
        );
    }

    public function testDataIsProperlyValidatedAndFiltered(): void
    {
        $this->setUp();

        vfsStream::setup('root', null, $this->structure);

        /** @var LoggerInterface&MockObject $log */
        $log = $this->createMock(LoggerInterface::class);
        $log
            ->expects($this->once())
            ->method('error')
            ->with(
                'Could not instantiate blog item for file vfs://root/posts/item-0001.md.',
                [
                    'publishDate' => [
                        'regexNotMatch' => "The input does not match against pattern '/\d{4}\-\d{2}\-\d{2}|(\d{2}\.){2}\d{4}/'",
                    ],
                ]
            );

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
            null,
            $log
        );

        $articles = $itemLister->getArticles();
        $this->assertCount(3, $articles);
    }
}
