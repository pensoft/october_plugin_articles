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
        $this->page['relatedArticles'] = $this->related();
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
        if (!$this->param('id')) {
            return [];
        }

        $article = Article::where('slug', $this->param('id'))->where('published', true)->first();

        if (!$article || empty($article->keywords)) {
            return [];
        }

        $tags = array_map('trim', explode(",", $article->keywords));

        $relatedQuery = Article::where('slug', '!=', $this->param('id'))
            ->where('published', true)
            ->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhereRaw('LOWER(keywords) LIKE ?', ['%' . strtolower($tag) . '%']);
                }
            });

        $relatedArticles = $relatedQuery
            ->where('published_at', '<=', now())
            ->limit($this->property('limit'))
            ->get();

        return $relatedArticles;
    }

}
