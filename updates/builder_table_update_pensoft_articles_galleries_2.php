<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesGalleries2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_galleries', function($table)
        {
            $table->integer('article_id')->unsigned()->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pensoft_articles_galleries', function($table)
        {
            $table->integer('article_id')->unsigned()->change();
        });
    }
}
