<?php namespace Pensoft\Articles\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Articles\Models\Article;

class ImageGallery extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Image Gallery',
            'description' => 'Displays an image gallery'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->page['gallery'] = $this->loadGallery();
    }

    protected function loadGallery()
    {
        $articleId = $this->param('id');
        $article = Article::where('slug', $articleId)->first();

        if($article && $article->gallery) {
            return $article->gallery()->get();
        } else {
            return [];
        }
    }
}
