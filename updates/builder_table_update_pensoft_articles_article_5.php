<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesArticle5 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_article', function ($table) {
            if (!Schema::hasColumn('pensoft_articles_article', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }

            if (!Schema::hasColumn('pensoft_articles_article', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_articles_article', function ($table) {
            if (Schema::hasColumn('pensoft_articles_article', 'meta_description')) {
                $table->dropColumn('meta_description');
            }

            if (Schema::hasColumn('pensoft_articles_article', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
        });
    }
}
