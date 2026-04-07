<?php

namespace Pensoft\Articles\Components;

use Backend\Facades\BackendAuth;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Str;
use Pensoft\Articles\Models\Article;
use Pensoft\Articles\Models\Category;

class ArticleList extends ComponentBase
{
    public bool $loggedIn = false;

    public function onRun(): void
    {
        $this->addJs('assets/js/def.js');
        $this->addCss('assets/css/pagination.css');

        $this->loggedIn = !empty(BackendAuth::getUser());

        // Detail page
        $slug = $this->param('id');
        if ($slug) {
            $article = Article::where('slug', $slug)->where('published', true)->first();
            if ($article) {
                $this->page['is_detail_page'] = true;
                $this->page['article'] = $article;
                $this->page['page_url'] = $this->pageUrl('');
                $this->page['encoded_title'] = urlencode($article->title);
                $this->page['slug'] = $article->slug;
                $this->page['related'] = $this->getRelatedArticles($article);
                $this->setSeoMeta($article);
                return;
            }
        }

        // List page
        $this->page['categories'] = $this->getCategories();
        $this->page['categoryId'] = $this->getCategoryId();
        $this->page['records'] = $this->getFilteredArticles();
    }

    public function componentDetails(): array
    {
        return [
            'name' => 'Article List',
            'description' => 'Displays a collection of articles with detail view.'
        ];
    }

    public function defineProperties(): array
    {
        return [
            'maxItems' => [
                'title' => 'Max items',
                'description' => 'Max items per page',
                'default' => 15,
            ],
            'thumbWidth' => [
                'title' => 'Cover image width',
                'default' => 250,
            ],
            'thumbHeight' => [
                'title' => 'Cover image height',
                'default' => 250,
            ],
            'noRecordsMessage' => [
                'title' => 'No records message',
                'default' => 'No records found',
            ],
            'relatedLimit' => [
                'title' => 'Related articles limit',
                'default' => 4,
            ],
        ];
    }

    public function getCategories()
    {
        return Category::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
    }

    public function getCategoryId(): string
    {
        $categoryId = input('categoryId', 'all');
        if ($categoryId !== 'all' && !is_numeric($categoryId)) {
            return 'all';
        }
        return $categoryId;
    }

    public function getFilteredArticles()
    {
        $categoryId = $this->getCategoryId();
        $query = Article::news()
            ->where('published_at', '<=', now())
            ->where('published', true)
            ->orderBy('published_at', 'DESC');

        if ($categoryId !== 'all') {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        $records = $query->paginate($this->property('maxItems'));

        if ($categoryId !== 'all') {
            $records->appends(['categoryId' => $categoryId]);
        }

        return $records;
    }

    protected function getRelatedArticles(Article $article)
    {
        $limit = (int) $this->property('relatedLimit');
        $related = collect();

        // First, try to find related by tags
        if (!empty($article->keywords)) {
            $tags = array_map('trim', explode(',', $article->keywords));
            $tags = array_filter($tags);

            if (!empty($tags)) {
                $related = Article::where('slug', '!=', $article->slug)
                    ->where('published', true)
                    ->where('published_at', '<=', now())
                    ->where(function ($query) use ($tags) {
                        foreach ($tags as $tag) {
                            if ($tag) {
                                $query->orWhereRaw('LOWER(keywords) LIKE ?', ['%' . strtolower($tag) . '%']);
                            }
                        }
                    })
                    ->orderBy('published_at', 'desc')
                    ->limit($limit)
                    ->get();
            }
        }

        // If not enough related articles found, fill with latest articles
        if ($related->count() < $limit) {
            $excludeIds = $related->pluck('id')->push($article->id)->toArray();
            $remaining = $limit - $related->count();

            $latest = Article::whereNotIn('id', $excludeIds)
                ->where('published', true)
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->limit($remaining)
                ->get();

            $related = $related->merge($latest);
        }

        return $related;
    }

    protected function setSeoMeta(Article $article): void
    {
        if (!class_exists('\BennoThommo\Meta\Meta')) {
            return;
        }

        $seoTitle = $article->title;
        $seoKeywords = $article->meta_keywords ?: $article->keywords;
        $seoDescription = Str::limit(strip_tags($article->content), 255);

        if ($seoTitle) {
            \BennoThommo\Meta\Meta::set('title', $seoTitle);
        }
        if ($seoKeywords) {
            \BennoThommo\Meta\Meta::set('keywords', $seoKeywords);
        }
        if ($seoDescription) {
            \BennoThommo\Meta\Meta::set('description', $seoDescription);
        }

        \BennoThommo\Meta\Meta::set('twitter:card', 'summary_large_image');
        \BennoThommo\Meta\Meta::set('twitter:title', $seoTitle);
        \BennoThommo\Meta\Meta::set('twitter:description', $seoDescription);
        \BennoThommo\Meta\Meta::set('og:title', $seoTitle);
        \BennoThommo\Meta\Meta::set('og:description', $seoDescription);
        \BennoThommo\Meta\Meta::set('og:type', 'article');
        \BennoThommo\Meta\Meta::set('og:url', $this->pageUrl(''));

        if ($article->cover) {
            \BennoThommo\Meta\Meta::set('twitter:image', $article->cover->getThumb(600, null, ['mode' => 'auto']));
            \BennoThommo\Meta\Meta::set('og:image', $article->cover->getThumb(600, 314, ['mode' => 'crop']));
            \BennoThommo\Meta\Meta::set('og:image:width', 600);
            \BennoThommo\Meta\Meta::set('og:image:height', 314);
        }
    }
}
