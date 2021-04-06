<?php

namespace Pensoft\Articles\Components;

use \Cms\Classes\ComponentBase;
use Pensoft\Articles\Models\Article;

class ArticleHighlights extends ComponentBase
{
    /**
     * Executed when this component is bound to a page or layout, part of
     * the page life cycle.
     */
    public function onRun()
    {
        $this->addJs('assets/js/slick.min.js');
        $this->addJs('assets/js/def.js');
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
        ];
    }

	public function getTemplatesOptions()
	{
		return [
			'template1' => 'Template 1',
			'template2' => 'Template 2',
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
        
        return Article::news()->descPublished()->take($this->property('maxItems'))->get()->map(function($item){
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
