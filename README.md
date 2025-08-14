<!-- markdownlint-disable MD013 -->
# Mezzio Markdown Blog

This is a blog module for Mezzio applications.
It provides the basics of converting a series of, minimalist, Markdown files with Yaml front-matter into an array of `BlogArticle` entities.
These entities can be used to provide a list of blog articles, or to render a given article.

Here is a sample article, so that you know, roughly, what to expect.

```markdown
---
publish_date: 13.07.2025
slug: episode-0001
synopsis: In this blogArticle, I have a fireside chat about one of my all-time favorite books, The Mythical Man Month.
title: Talking about The Mythical Man Month.
categories:
  - software development
tags:
  - Books
  - Mythical Man Month
---

### Synopsis

Suspendisse viverra mauris ac urna gravida, vel malesuada dolor interdum. Nullam ultrices urna erat, non venenatis turpis placerat eget. Etiam vitae magna non tortor congue volutpat. Integer ut ornare ante. Etiam hendrerit vehicula turpis, sit amet pulvinar nunc dictum eu. In tincidunt sollicitudin eros, quis ultrices turpis maximus ut. Ut eu erat eget magna congue ornare vel et tortor. Curabitur laoreet neque et ex aliquet tempus.

### Related Links

- [The Mythical Man Month (on Wikipedia)][mythical-man-month-book-url]
```

## Usage

The package is designed to be used as part of [Mezzio][mezzio-url]-based applications, and goes to a lot of effort to make doing so as simple as possible.

### Install the package

To install the package, use Composer (just as you would install any other package) by running the following command:

```bash
composer require settermjd/mezzio-markdown-blog
```

During installation, the project's `ConfigProvider` will be loaded into `config/config.php`, loading all of the required dependencies, routes, and template paths.
Given that, most of the work is done for you, including registering the routes and accompanying handlers for:

- **Listing all blog articles** (the blog index page) – with pagination.
  The route is `/blog`.
- **Viewing individual blog articles.**
  The route is `/blog/article/{slug}`.

### Complete the installation

Now, there are two things that you need to do:

- [Set up the articles directory](#install-step-two)
- [Override the default templates](#install-step-three)

<!-- markdownlint-disable MD033 -->
<a name="install-step-two"></a>
<!-- markdownlint-enable MD033 -->
### Set up the articles directory

There is no way to create the articles (posts) directory as part of the installation process, so you need to do this yourself.
So, in the _data_ directory, create a new directory named _posts_.
The path needs to match the `path` element that you set in the application's configuration, outlined in the previous section.

> [!NOTE]
> In a future version, there will be tooling to automate this.

<!-- markdownlint-disable MD033 -->
<a name="install-step-three"></a>
<!-- markdownlint-enable MD033 -->
### Override the default templates

The next thing that you need to do is to override the blog templates.
There are default versions in the project's _templates/blog_ directory.
But these are quite generic and only meant as a way of quickly getting you started.
They're not designed to be a professional design for every application.

The three templates are:

- _blog.html.{{your view renderer extension}}_.
  This template is the blog index page that renders all of the available articles or posts for your blog.
- _blog-article.html.{{your view renderer extension}}_.
  This template renders details of a specific blog post when it is viewed.
- _includes/pagination.html.{{your view renderer extension}}_, e.g., _includes/pagination.html.twig_.
  This is the pagination template which is called by the blog index template, so that users can step through the available blog records a page at a time.

> [!NOTE]
> There are three versions of each template:
>
> - One for [Twig][twig-url]
> - One for [laminas-view][laminas-view-url]
> - One for [Plates][plates-url]

### Update the application's configuration (_optional_)

If you want or need to, you can also update the module's configuration as well.
By default, its configuration is set in `Settermjd\MarkdownBlog\ConfigProvider`.
However, you can override this by copying the default configuration file, _config/autoload/blog.local.php_ to the application's _config/autoload_ directory.
You can find documentation for each option in both the config file and in `Settermjd\MarkdownBlog\ConfigProvider`.

### When using Twig as your view renderer

If you use Twig as your view renderer, while you don't need to, you can create more feature-rich templates by installing [PHP's Intl extension][php-intl-ext-url], and the [twig/markdown-extra][twig-markdown-extra-ext-url] and [twig/intl-extra][twig-intl-ext-url] packages.

After installing PHP's Intl extension using your package manager or from source, install the two Twig packages using the following command:

```bash
composer require twig/intl-extra twig/markdown-extra
```

<!-- Document links -->
[laminas-view-url]: https://docs.laminas.dev/laminas-view/
[mezzio-url]: https://docs.mezzio.dev/mezzio/
[mythical-man-month-book-url]: https://en.wikipedia.org/wiki/The_Mythical_Man-Month
[php-intl-ext-url]: https://www.php.net/manual/en/intro.intl.php
[plates-url]: https://platesphp.com/
[twig-intl-ext-url]: https://github.com/twigphp/intl-extra
[twig-markdown-extra-ext-url]: https://github.com/twigphp/markdown-extra
[twig-url]: https://twig.symfony.com/
<!-- markdownlint-enable MD013 -->
