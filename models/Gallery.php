<?php namespace Pensoft\Articles\Models;

use Model;

class Gallery extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_articles_galleries';

    /**
     * @var bool Indicates if the model should be timestamped.
     */

    public $timestamps = true;
    // define the relationship with the Article model
    public $belongsTo = [
        'article' => ['Pensoft\Articles\Models\Article']
    ];

    // define the relationship with the System\Models\File model for gallery images
    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    // default values for new instances
    public $attributes = [
        'name' => 'Gallery Name',
    ];
}
