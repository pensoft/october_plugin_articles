<?php namespace Pensoft\Articles\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Lang;
use Pensoft\Articles\Models\Article;

class PublicationsList extends ComponentBase
{
	    /**
     * Executed when this component is bound to a page or layout, part of
     * the page life cycle.
     */
    public function onRun()
    {
		$this->addJs('assets/js/def.js');
	}
	
    public function componentDetails()
    {
        return [
            'name'        => 'PublicationsList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
		return [
			'maxItems' => [
				'title' => 'Max items',
				'description' => 'Max items allowed',
				'default' => null,
			],
			'showCover' => [
				'title' => 'Show covers',
				'default' => false,
			],
			'baseUrl' => [
				'title' => 'Base url',
				'default' => '/news',
			]
		];
	}

	private function getBaseUrl($slug = null)
	{
		return url(rtrim($this->property('baseUrl'), '/') . '/' . $slug);
	}

	public function getReadmoreLink($item)
	{
		return '<a href="' . $this->getBaseUrl($item->slug) . '" ><b>'. Lang::get('pensoft.articles::lang.readmore') .'</b></a>';
	}

	public function getUrl($item)
	{
		return $this->getBaseUrl($item->slug);
	}

	public function getPublications()
	{
		$publications = Article::publications()->descPublished();

		if ($this->property('maxItems') > 0) {
			return $publications->take($this->property('maxItems'))->get();
		}
		return $publications->get();
	}
}
