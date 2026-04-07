<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftArticlesArticleCategoryPivot extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pensoft_articles_article_category_pivot'))
        {
            Schema::create('pensoft_articles_article_category_pivot', function(Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->integer('category_id')->unsigned();
                $table->integer('article_id')->unsigned();

                $table->foreign('category_id')
                    ->references('id')->on('pensoft_articles_category')
                    ->onDelete('cascade');

                $table->foreign('article_id')
                    ->references('id')->on('pensoft_articles_article')
                    ->onDelete('cascade');

                $table->primary(['category_id', 'article_id'], 'category_article_pivot_pk');

            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pensoft_articles_article_category_pivot')) {
            Schema::dropIfExists('pensoft_articles_article_category_pivot');
        }
    }
}