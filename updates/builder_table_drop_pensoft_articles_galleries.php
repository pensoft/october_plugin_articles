<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDropPensoftArticlesGalleries extends Migration
{
    public function up()
    {
        if (Schema::hasTable('pensoft_articles_galleries')) {
            Schema::dropIfExists('pensoft_articles_galleries');
        }
    }

    public function down()
    {
        Schema::create('pensoft_articles_galleries', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 255);
            $table->integer('article_id')->unsigned()->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('article_id')
                ->references('id')
                ->on('pensoft_articles_article')
                ->onDelete('cascade');
        });
    }
}
