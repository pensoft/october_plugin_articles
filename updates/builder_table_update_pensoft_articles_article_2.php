<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesArticle2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_article', function($table)
        {
            $table->boolean('published')->nullable()->default(true);
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_articles_article', function($table)
        {
            $table->dropColumn('published');
        });
    }
}
