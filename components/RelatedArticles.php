<?php namespace Pensoft\Articles\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Articles\Models\Article;

/**
 * RelatedArticles Component
 */
class RelatedArticles extends ComponentBase
{
	public function onRun()
	{

	}

    public function componentDetails()
    {
        return [
            'name' => 'RelatedArticles Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
		return [
			'limit' => [
				'title' => 'Limit',
				'description' => 'Max items allowed',
				'default' => 4,
			],
		];
    }

	public function getUrl($item, $page_id)
	{
		return $this->pageUrl($page_id, ['id' => $item->slug]);
	}

	public function related()
	{
		$related = array();
		if($this->param('id')){
			$article = Article::where('slug', $this->param('id'))->first();
			$arrayOfTags = explode(",", $article->keywords);

			$related = Article::where('slug', '!=', $this->param('id'))
				->latest()
				->limit($this->property('limit'));
			foreach($arrayOfTags as $tag){
				$related->orWhere('keywords', 'ILIKE', '%'.$tag.'%');
			}
			$related = $related->get();

			return $related;
		}

	}
}
