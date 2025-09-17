<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\InputFilter;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;

use function array_key_exists;

final class BlogArticleInputFilterFactoryTest extends TestCase
{
    #[DataProvider('filterDataProvider')]
    public function testCanSetAndRetrieveDataCorrectly(array $filterData, array $expectedResult): void
    {
        $filter = (new BlogArticleInputFilterFactory())();
        $filter->setData($filterData);
        $this->assertTrue($filter->isValid());
        $this->assertEmpty($filter->getMessages());

        $this->assertSame(
            $filter->getValue('categories'),
            $expectedResult['categories']
        );
        $this->assertSame(
            $filter->getValue('content'),
            $expectedResult['content']
        );
        $this->assertSame(
            $filter->getValue('publishDate'),
            $expectedResult['publishDate']
        );
        $this->assertSame(
            $filter->getValue('slug'),
            $expectedResult['slug']
        );
        $this->assertSame(
            $filter->getValue('synopsis'),
            $expectedResult['synopsis']
        );
        $this->assertSame(
            $filter->getValue('title'),
            $expectedResult['title']
        );

        if (array_key_exists('image', $expectedResult)) {
            $this->assertSame(
                $filter->getValue('title'),
                $expectedResult['title']
            );
        }

        if (array_key_exists('tags', $expectedResult)) {
            $this->assertSame(
                $filter->getValue('tags'),
                $expectedResult['tags']
            );
        }
    }

    /**
     * @return (string|string[])[][][]
     */
    public static function filterDataProvider(): array
    {
        return [
            [
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'image'       => 'learning-golang-day13.png',
                    'publishDate' => '02.04.2023',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'tags'        => [
                        'Golang',
                        'Go',
                        'Gorilla Mux',
                        'Regular Expressions',
                        'vim-go',
                    ],
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'image'       => 'learning-golang-day13.png',
                    'publishDate' => '02.04.2023',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'tags'        => [
                        'Golang',
                        'Go',
                        'Gorilla Mux',
                        'Regular Expressions',
                        'vim-go',
                    ],
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
            ],
            [
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'image'       => 'learning-golang-day13.png',
                    'publishDate' => '2023-04-02',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'tags'        => [
                        'Golang',
                        'Go',
                        'Gorilla Mux',
                        'Regular Expressions',
                        'vim-go',
                    ],
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'image'       => 'learning-golang-day13.png',
                    'publishDate' => '2023-04-02',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'tags'        => [
                        'Golang',
                        'Go',
                        'Gorilla Mux',
                        'Regular Expressions',
                        'vim-go',
                    ],
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
            ],
            [
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'publishDate' => '2023-04-02',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'tags'        => [
                        'Golang',
                        'Go',
                        'Gorilla Mux',
                        'Regular Expressions',
                        'vim-go',
                    ],
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'publishDate' => '2023-04-02',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'tags'        => [
                        'Golang',
                        'Go',
                        'Gorilla Mux',
                        'Regular Expressions',
                        'vim-go',
                    ],
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
            ],
            [
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'image'       => 'learning-golang-day13.png',
                    'publishDate' => '2023-04-02',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'image'       => 'learning-golang-day13.png',
                    'publishDate' => '2023-04-02',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
            ],
            [
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'publishDate' => '2023-04-02',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
                [
                    'categories'  => [
                        'Education',
                    ],
                    'content'     => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'publishDate' => '2023-04-02',
                    'slug'        => 'learning-golang/day-13/',
                    'synopsis'    => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'title'       => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
            ],
        ];
    }
}
