<?php namespace Pensoft\Articles;

use Pensoft\Articles\Components\ArticleHighlights;
use Pensoft\Articles\Components\ArticleList;
use Pensoft\Articles\Components\RelatedArticles;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public $require = ['Pensoft.Media'];

    public function pluginDetails(): array
    {
        return [
            'name'        => 'Articles',
            'icon'        => 'oc-icon-newspaper-o',
            'author'      => 'Pensoft'
        ];
    }


    public function registerComponents(): array
    {
        return [
            ArticleList::class => 'list',
            ArticleHighlights::class => 'article_highlights',
            RelatedArticles::class => 'related_articles',
        ];
    }


    public function boot(): void {}

    public function registerPermissions(): array
    {
        return [
            'pensoft.articles.access' => [
                'tab' => 'Articles',
                'label' => 'Manage articles/news'
            ],
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'articles' => [
                'label'       => 'Articles',
                'url'         => \Backend::url('pensoft/articles/article'),
                'icon'        => 'icon-newspaper-o',
                'permissions' => ['pensoft.articles.*'],
                'sideMenu' => [
                    'side-menu-media-articles' => [
                        'label'       => 'Articles',
                        'url'         => \Backend::url('pensoft/articles/article'),
                        'icon'        => 'icon-newspaper-o',
                        'permissions' => ['pensoft.articles.*'],
                    ],
                    'side-menu-item2' => [
                        'label'       => 'Categories',
                        'url'         => \Backend::url('pensoft/articles/categories'),
                        'icon'        => 'icon-list',
                        'permissions' => ['pensoft.articles.*'],
                    ],

                ]
            ],
        ];
    }

}