<?php

declare(strict_types=1);

use Mni\FrontYAML\Parser;
use Settermjd\MarkdownBlog\Handler\BlogIndexHandler;

return [
    // This provides the configuration for the blog
    'blog' => [

        /**
         * Setting type to 'filesystem', which currently is the only choice,
         * will invoke the ItemListerFilesystem adapter to retrieve blog files
         * from the local filesystem.
         */
        'type' => $_ENV['BLOG_ADAPTER_TYPE'] ?? 'filesystem',

        /**
         * 'path' sets the path on the local filesystem to retrieve the Markdown
         * files from. This directory needs to be manually initialised before it
         * can be used.
         */
        'path' => __DIR__ . '/../../../' . $_ENV['BLOG_POSTS_DIR'] ?? __DIR__ . '/../../../data/posts',

        /**
         * 'items_per_page' sets the maximum number of blog items to render on each
         * page of records. This amount will be rendered, if there are enough records
         * to render that many, either in total, or for that page of records.
         */
        'items_per_page' => $_ENV['BLOG_ITEMS_PER_PAGE'] ?? BlogIndexHandler::ITEMS_PER_PAGE,

        /**
         * 'parser' is the class to use to parse the Markdown file's YAML front-matter.
         * In future releases, other front-matter formats may be supported. However,
         * for the time being, only YAML is supported.
         */
        'parser' => Parser::class,

    ]
];
