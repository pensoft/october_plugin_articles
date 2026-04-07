<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesGalleries2 extends Migration
{
    public function up(): void
    {
        Schema::table('pensoft_articles_galleries', function(Blueprint $table)
        {
            $table->integer('article_id')->unsigned()->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pensoft_articles_galleries', function(Blueprint $table)
        {
            $table->integer('article_id')->unsigned()->change();
        });
    }
}