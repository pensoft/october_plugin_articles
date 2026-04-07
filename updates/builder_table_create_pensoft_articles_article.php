<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftArticlesArticle extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pensoft_articles_article')) {
            Schema::create('pensoft_articles_article', function(Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id')->unsigned();
                $table->string('title', 255);
                $table->string('keywords', 255)->nullable();
                $table->string('slug', 255);
                $table->text('content');
                $table->timestamp('published_at')->default('now()');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->boolean('allow_share')->default(true);
                $table->smallInteger('type')->default(1);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pensoft_articles_article')) {
            Schema::dropIfExists('pensoft_articles_article');
        }
    }
}