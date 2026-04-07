<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesCategory extends Migration
{
    public function up(): void
    {
        Schema::table('pensoft_articles_category', function(Blueprint $table)
        {
            $table->integer('sort_order')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pensoft_articles_category', function(Blueprint $table)
        {
            $table->dropColumn('sort_order');
        });
    }
}