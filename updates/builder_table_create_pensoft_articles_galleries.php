<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftArticlesGalleries extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pensoft_articles_galleries')) {
            Schema::create('pensoft_articles_galleries', function(Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id')->unsigned();
                $table->string('name', 255);
                $table->integer('article_id')->unsigned();

                $table->foreign('article_id')
                    ->references('id')
                    ->on('pensoft_articles_article')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pensoft_articles_galleries')) {
            Schema::dropIfExists('pensoft_articles_galleries');
        }
    }
}