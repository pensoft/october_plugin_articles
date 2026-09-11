<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesArticle7 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_article', function ($table) {
            if (!Schema::hasColumn('pensoft_articles_article', 'news_of_the_day')) {
                $table->boolean('news_of_the_day')->nullable()->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('pensoft_articles_article', function ($table) {
            if (Schema::hasColumn('pensoft_articles_article', 'news_of_the_day')) {
                $table->dropColumn('news_of_the_day');
            }
        });
    }
}
