<?php namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftArticlesCategory2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_articles_category', function(Blueprint $table)
        {
            $table->boolean('is_visible')->default(true);
        });
    }

    public function down()
    {
        Schema::table('pensoft_articles_category', function(Blueprint $table)
        {
            $table->dropColumn('is_visible');
        });
    }
}
