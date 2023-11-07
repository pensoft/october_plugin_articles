<?php namespace Pensoft\Articles;

use Pensoft\Articles\Components\ArticleHighlights;
use Pensoft\Articles\Components\ArticleList;
use Pensoft\Articles\Components\Gallery;
use Pensoft\Articles\Components\PublicationsList;
use Pensoft\Articles\Components\RelatedArticles;
use System\Classes\PluginBase;
use SaurabhDhariwal\Revisionhistory\Classes\Diff as Diff;
use System\Models\Revision as Revision;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            ArticleList::class => 'list',
            PublicationsList::class => 'publications_list',
            ArticleHighlights::class => 'article_highlights',
            RelatedArticles::class => 'related_articles',
            Gallery::class => 'article_galleries',
        ];
    }


    public function boot(){
        /* Extetions for revision */
        Revision::extend(function($model){
            /* Revison can access to the login user */
            $model->belongsTo['user'] = ['Backend\Models\User'];

            /* Revision can use diff function */
            $model->addDynamicMethod('getDiff', function() use ($model){
                return Diff::toHTML(Diff::compare($model->old_value, $model->new_value));
            });
        });
    }

}
