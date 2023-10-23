<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesArticle3 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_article', function($table)
        {
            $table->text('caption')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_articles_article', function($table)
        {
            $table->dropColumn('caption');
        });
    }
}
