<?php namespace Pensoft\Articles\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Articles\Models\Article;

class Gallery extends ComponentBase
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
        $this->page['galleries'] = $this->loadGallery();
    }


    protected function loadGallery()
    {
        $articleId = $this->param('id');
        $article = Article::where('slug', $articleId)->first();

        if($article && $article->galleries) {
            return $article->galleries()
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            return [];
        }
    }

}
