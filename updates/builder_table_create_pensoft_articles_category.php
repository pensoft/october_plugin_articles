<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftArticlesCategory extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pensoft_articles_category')) {
            Schema::create('pensoft_articles_category', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pensoft_articles_category');
    }
}
