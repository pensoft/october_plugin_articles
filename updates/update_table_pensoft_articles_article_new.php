<?php

namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateTablePensoftArticlesArticleNew extends Migration
{
	public function up(): void
	{
		if (!Schema::hasColumn('pensoft_articles_article', 'url')) {
			Schema::table('pensoft_articles_article', function (Blueprint $table) {
				$table->boolean('external')->default(true);
			});
		}
	}

	public function down(): void
	{
		if (Schema::hasColumn('pensoft_articles_article', 'url')) {
			Schema::table('pensoft_articles_article', function (Blueprint $table) {
				$table->dropColumn('external');
			});
		}
	}
}