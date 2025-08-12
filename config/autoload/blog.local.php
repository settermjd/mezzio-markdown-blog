<?php

declare(strict_types=1);

use Mni\FrontYAML\Parser;

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
         * 'parser' is the class to use to parse the Markdown file's YAML front-matter.
         * In future releases, other front-matter formats may be supported. However,
         * for the time being, only YAML is supported.
         */
        'parser' => Parser::class,

    ]
];
