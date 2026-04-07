<?php

namespace Pensoft\Articles\Updates;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;
use Schema;

class AddIsHighlightToArticles extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pensoft_articles_article', 'is_highlight')) {
            Schema::table('pensoft_articles_article', function (Blueprint $table): void {
                $table->boolean('is_highlight')->default(true);
            });
        }

        // Set all existing records to true
        DB::table('pensoft_articles_article')->update(['is_highlight' => true]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('pensoft_articles_article', 'is_highlight')) {
            Schema::table('pensoft_articles_article', function (Blueprint $table): void {
                $table->dropColumn('is_highlight');
            });
        }
    }
}
