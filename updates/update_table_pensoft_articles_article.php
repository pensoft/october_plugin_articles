<?php

namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateTablePensoftArticlesArticle extends Migration
{
	public function up()
	{
		if (!Schema::hasColumn('pensoft_articles_article', 'url')) {
			Schema::table('pensoft_articles_article', function ($table) {
				$table->string('url')->nullable();
			});
		}
	}

	public function down()
	{
		if (Schema::hasColumn('pensoft_articles_article', 'url')) {
			Schema::table('pensoft_articles_article', function ($table) {
				$table->dropColumn('url');
			});
		}
	}
}
