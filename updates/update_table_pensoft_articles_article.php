<?php

namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateTablePensoftArticlesArticle extends Migration
{
	public function up(): void
	{
		if (!Schema::hasColumn('pensoft_articles_article', 'url')) {
			Schema::table('pensoft_articles_article', function (Blueprint $table) {
				$table->string('url')->nullable();
			});
		}
	}

	public function down(): void
	{
		if (Schema::hasColumn('pensoft_articles_article', 'url')) {
			Schema::table('pensoft_articles_article', function (Blueprint $table) {
				$table->dropColumn('url');
			});
		}
	}
}