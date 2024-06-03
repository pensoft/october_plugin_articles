<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesArticle4 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_article', function($table)
        {
            $table->text('meta_keywords')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_articles_article', function($table)
        {
            $table->dropColumn('meta_keywords');
        });
    }
}
