<?php

namespace Pensoft\Articles\Components;

use Backend\Facades\BackendAuth;
use \Cms\Classes\ComponentBase;
use Pensoft\Articles\Models\Article;

class ArticleHighlights extends ComponentBase
{
	public $loggedIn;
	/**
     * Executed when this component is bound to a page or layout, part of
     * the page life cycle.
     */
    public function onRun()
    {
        $this->addJs('assets/js/slick.min.js');
        $this->addJs('assets/js/def.js');
		// by default users are not logged in
		$this->loggedIn = false;
		// end then if getUser returns other value than NULL then our user is logged in
		if (!empty(BackendAuth::getUser())) {
			$this->loggedIn = true;
		}
    }

    public function componentDetails()
    {
        return [
            'name' => 'Article Hightlights',
            'description' => ''
        ];
    }

    public function defineProperties()
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
			'templates' => [
				'title' => 'Select templates',
				'type' => 'dropdown',
				'default' => 'template1'
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
        ];
    }

	public function getTemplatesOptions()
	{
		return [
			'template1' => 'Template 1',
			'template2' => 'Template 2',
			'template3' => 'Template 3',
			'template4' => 'Template 4',
		];
	}

    private function getBaseUrl($slug = null)
    {
        return url(rtrim($this->property('baseUrl'), '/') . '/' . $slug);
    }

    public function getTitle()
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

        return Article::news()->where('published_at', '<=', 'now()')->where('published', 'true')->take($this->property('maxItems'))->orderBy('published_at', 'DESC')->get()->map(function($item){
            $item->content = str_limit(strip_tags($item->content), 100);
            $item->href = $this->getBaseUrl($item->slug);
            return $item;
        });
    }

    public function isEmpty()
    {
        return !Article::count();
    }
}
