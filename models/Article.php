<?php

namespace Pensoft\Articles\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Model;

/**
 * Model
 */
class Article extends Model
{
    use \October\Rain\Database\Traits\Validation;
    const TYPE_NEWS = 1;
    const TYPE_PUBLICATIONS = 2;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_articles_article';

    /**
     * @var array Validation rules
     */
    public $rules = [];

    public $attachOne = [
        'cover' => 'System\Models\File'
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

}
