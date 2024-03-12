<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesCategory extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_category', function(Blueprint $table)
        {
            $table->integer('sort_order')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pensoft_articles_category', function(Blueprint $table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
