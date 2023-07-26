<?php

namespace Pensoft\Articles\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Model;
use BackendAuth;

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

    /**
     * Actions to perform before deleting an article.
     * It checks if the Galleries model exists in the Media plugin.
     * If so, it dissociates the galleries linked to this article.
     */
    public function beforeDelete()
    {
        if (class_exists('\Pensoft\Media\Models\Galleries')) {
            \Pensoft\Media\Models\Galleries::where('article_id', $this->id)
                ->update(['article_id' => null, 'related' => false]);
        }
    }

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
}
