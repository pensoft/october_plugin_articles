<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableGalleriesArticlePivot extends Migration
{
    public function up(): void
    {
        // if (!Schema::hasTable('pensoft_media_galleries') || !Schema::hasTable('pensoft_articles_article')) {
        //     return;
        // }

        if (Schema::hasTable('pensoft_gallery_article_pivot')) {
            return;
        }

        Schema::create('pensoft_gallery_article_pivot', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('gallery_id')->unsigned();
            $table->integer('article_id')->unsigned();

            $table->foreign('gallery_id')
                  ->references('id')->on('pensoft_media_galleries')
                  ->onDelete('cascade');

            $table->foreign('article_id')
                  ->references('id')->on('pensoft_articles_article')
                  ->onDelete('cascade');

            $table->primary(['gallery_id', 'article_id'], 'gallery_article_pivot_pk');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('pensoft_gallery_article_pivot')) {
            Schema::dropIfExists('pensoft_gallery_article_pivot');
        }
    }
}