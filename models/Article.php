<?php

namespace Pensoft\Articles\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Model;
use BackendAuth;
use Validator;

/**
 * Model
 */
class Article extends Model
{
    use \October\Rain\Database\Traits\Validation;

    // For Revisionable namespace
    use \October\Rain\Database\Traits\Revisionable;

    public $timestamps = false;

    // Add  for revisions limit
    public $revisionableLimit = 200;

    // Add for revisions on particular field
    protected $revisionable = ["id","title","content"];


    /**
     * @var array Translatable fields
     */
    public $translatable = [
        'title',
        'cover',
        'slug',
        'content',
        'caption',
        'keywords',
        'external',
        'type',
        'published'
    ];

    const TYPE_NEWS = 1;
    const TYPE_PUBLICATIONS = 2;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_articles_article';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
        'published_at' => 'required',
        'cover' => 'required',
    ];

    public $attachOne = [
        'cover' => 'System\Models\File'
    ];

    // Add  below relationship with Revision model
    public $morphMany = [
        'revision_history' => ['System\Models\Revision', 'name' => 'revisionable']
    ];

    public $belongsToMany = [
        'galleries' => [
            'Pensoft\Media\Models\Galleries',
            'table' => 'pensoft_gallery_article_pivot',
            'key' => 'article_id',
            'otherKey' => 'gallery_id',
            'order' => 'created_at desc'
        ],
        'categories' => [
            'Pensoft\Articles\Models\Category',
            'table' => 'pensoft_articles_article_category_pivot',
            'key' => 'article_id',
            'otherKey' => 'category_id',
            // 'order' => 'sort_order'
        ]
    ];

    public function scopeNews($query)
    {
        return $query->where('type', self::TYPE_NEWS);
    }

    public function scopePublications($query)
    {
        return $query->where('type', self::TYPE_PUBLICATIONS);
    }

    public function scopeDescPublished($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($query) use ($categoryId) {
            $query->where('id', $categoryId);
        });
    }

    public function getPrettyAllowShareAttribute()
    {
        return filter_var($this->allow_share, FILTER_VALIDATE_BOOLEAN) ? "yes" : "no";
    }

    public function getReadmoreAttribute($value)
    {
        return '<a href="' . $this->slug . '" >'. Lang::get('pensoft.articles::lang.readmore') .'</a>' . $value;
    }

    public function getPublishedAtAttribute($value)
    {
        return $value;
    }

    public function getPrettyPublishedAtAttribute()
    {
        $date = new Carbon($this->published_at);
        return $date->day . ' ' . $date->englishMonth . ' ' . $date->year;
    }

    public function getContentLimitAttribute(){
        $content = strip_tags($this->content);
        if(strlen($content) > 500){
            $limit = min(max(strlen($content), 0), 500);
            return str_limit($content, $limit, $end = '...');
        }

        return $content;
    }

    public function getTitleEncodedAttribute()
    {
        return trim(urlencode($this->title));
    }


    // Add below function use for get current user details
    public function diff(){
        $history = $this->revision_history;
    }

    public function getRevisionableUser()
    {
        return BackendAuth::getUser()->id;
    }


        /**
     * Add translation support to this model, if available.
     *
     * @return void
     */
    public static function boot()
    {
        Validator::extend(
            'json',
            function ($attribute, $value, $parameters) {
                json_decode($value);

                return json_last_error() == JSON_ERROR_NONE;
            }
        );

        // Call default functionality (required)
        parent::boot();

        // Check the translate plugin is installed
        if (!class_exists('RainLab\Translate\Behaviors\TranslatableModel')) {
            return;
        }

        // Extend the constructor of the model
        self::extend(
            function ($model) {
                // Implement the translatable behavior
                $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
            }
        );
    }

}
