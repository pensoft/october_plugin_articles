<?php

namespace Pensoft\Articles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateTablePensoftArticlesArticleNew extends Migration
{
	public function up()
	{
		if (!Schema::hasColumn('pensoft_articles_article', 'url')) {
			Schema::table('pensoft_articles_article', function ($table) {
				$table->boolean('external')->default(true);
			});
		}
	}

	public function down()
	{
		if (Schema::hasColumn('pensoft_articles_article', 'url')) {
			Schema::table('pensoft_articles_article', function ($table) {
				$table->dropColumn('external');
			});
		}
	}
}
