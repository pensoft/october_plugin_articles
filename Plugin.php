<?php namespace Pensoft\Articles;

use Pensoft\Articles\Components\ArticleHighlights;
use Pensoft\Articles\Components\ArticleList;
use Pensoft\Articles\Components\PublicationsList;
use Pensoft\Articles\Components\RelatedArticles;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            ArticleList::class => 'list',
			PublicationsList::class => 'publications_list',
            ArticleHighlights::class => 'article_highlights',
            RelatedArticles::class => 'related_articles',
        ];
    }

}
