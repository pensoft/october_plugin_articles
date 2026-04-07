<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesArticle4 extends Migration
{
    public function up(): void
    {
        Schema::table('pensoft_articles_article', function(Blueprint $table)
        {
            $table->text('meta_keywords')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pensoft_articles_article', function(Blueprint $table)
        {
            $table->dropColumn('meta_keywords');
        });
    }
}