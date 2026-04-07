<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftArticlesCategory extends Migration
{
    public function up(): void
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

    public function down(): void
    {
        Schema::dropIfExists('pensoft_articles_category');
    }
}