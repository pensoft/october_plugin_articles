<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesArticle6 extends Migration
{
    public function up(): void
    {
        Schema::table('pensoft_articles_article', function (Blueprint $table) {
            if (!Schema::hasColumn('pensoft_articles_article', 'label')) {
                $table->text('label')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pensoft_articles_article', function (Blueprint $table) {
            if (Schema::hasColumn('pensoft_articles_article', 'label')) {
                $table->dropColumn('label');
            }
        });
    }
}