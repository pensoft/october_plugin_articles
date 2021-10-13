<?php

namespace Pensoft\Articles\Components;

use Backend\Facades\BackendAuth;
use \Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Lang;
use Pensoft\Articles\Models\Article;

class ArticleList extends ComponentBase
{
	public $loggedIn;
	/**
     * Executed when this component is bound to a page or layout, part of
     * the page life cycle.
     */
    public function onRun()
    {
        $this->addJs('assets/js/def.js');
        $this->addCss('assets/css/pagination.css');
        $this->page['card_horizontal'] = true;
        $this->page['border'] = $this->page['border'] ?: true;
        $this->page['image_position'] = $this->page['image_position'] ?: "card-img-top";
        $this->page['encoded_title'] = trim(urlencode($this->page['title']));
        if ($this->page['card_horizontal']) {
            $this->page['class'] .= $this->page['class'] ? " card-horizontal" : "card-horizontal";
        }
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
            'name' => 'List',
            'description' => 'Displays a collection of articles.'
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
			'no_records_message' => [
				'title' => 'No records message',
				'description' => 'Message to be displeyed when no listems are added',
				'default' => 'No records found',
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

    public function getContent($value)
    {
        return strip_tags($value);
    }

    // depricated
    public function getReadmoreLink($item, $page_id)
    {
        return '<a href="/' .$page_id. '/' . $item->slug . '" ><b>'. Lang::get('pensoft.articles::lang.readmore') .'</b></a>';
    }

    public function getUrl($item, $page_id)
    {
        return $this->pageUrl($page_id, ['id' => $item->slug]);
    }

    public function getArticles()
    {
        $news = Article::news()->descPublished();
        if ($this->property('maxItems') > 0) {
//            return $news->take($this->property('maxItems'))->get();
            return $news->paginate($this->property('maxItems'), ['*'], 'p');
        }
        return $news->get();
    }
}
