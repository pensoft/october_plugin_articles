<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesArticle6 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_article', function ($table) {
            if (!Schema::hasColumn('pensoft_articles_article', 'label')) {
                $table->text('label')->nullable();
            }
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_articles_article', function ($table) {
            if (Schema::hasColumn('pensoft_articles_article', 'label')) {
                $table->dropColumn('label');
            }
        });
    }
}