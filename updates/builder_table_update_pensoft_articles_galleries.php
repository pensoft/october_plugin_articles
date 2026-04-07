<?php namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesGalleries extends Migration
{
    public function up(): void
    {
        Schema::table('pensoft_articles_galleries', function(Blueprint $table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pensoft_articles_galleries', function(Blueprint $table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}