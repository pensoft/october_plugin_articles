<?php

namespace Pensoft\Articles\Components;

use Backend\Facades\BackendAuth;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Str;
use Pensoft\Articles\Models\Article;

class ArticleHighlights extends ComponentBase
{
	public bool $loggedIn = false;
	/**
     * Executed when this component is bound to a page or layout, part of
     * the page life cycle.
     */
    public function onRun(): void
    {
        //$this->addJs('assets/js/slick.min.js');
        //$this->addJs('assets/js/def.js');
		// by default users are not logged in
		$this->loggedIn = false;
		// end then if getUser returns other value than NULL then our user is logged in
		if (!empty(BackendAuth::getUser())) {
			$this->loggedIn = true;
		}
    }

    public function componentDetails(): array
    {
        return [
            'name' => 'Article Hightlights',
            'description' => ''
        ];
    }

    public function defineProperties(): array
    {
        return [
            'maxItems' => [
                'title' => 'Max items',
                'description' => 'Max items allowed',
                'default' => 10,
            ],
            'title' => [
                'title' => 'Head title',
                'description' => '',
                'default' => false
            ],
            'baseUrl' => [
                'title' => 'Base url',
                'default' => '/news',
            ],
			'thumb_width' => [
				'title' => 'Cover image width',
				'description' => 'Cover image width in pixels',
				'default' => 250,
			],
			'thumb_height' => [
				'title' => 'Cover image height',
				'description' => 'Cover image height in pixels',
				'default' => 250,
			],
			'title_max_length' => [
				'title' => 'Title max length',
				'description' => 'Title max length',
				'default' => 150,
			],
        ];
    }

    private function getBaseUrl($slug = null): string
    {
        return url(rtrim($this->property('baseUrl'), '/') . '/' . $slug);
    }

    public function getTitle(): string|false
    {
        if(!$this->property('title')){
            return false;
        }
        return $this->property('title');
    }

    public function getHighlights()
    {
        if (!$this->property('maxItems')) {
            return;
        }

        return Article::news()->where('published_at', '<=', 'now()')->where('published', 'true')->where('is_highlight', true)->take($this->property('maxItems'))->orderBy('published_at', 'DESC')->get()->map(function($item){
            $item->content = Str::limit(strip_tags($item->content), 100);
            $item->href = $this->getBaseUrl($item->slug);
            return $item;
        });
    }

    public function isEmpty(): bool
    {
        return !Article::count();
    }
}