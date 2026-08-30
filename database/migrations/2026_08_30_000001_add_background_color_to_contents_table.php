<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            // Stores a palette key (e.g. "biru"), not a raw hex value, so every
            // selectable colour stays contrast-checked against white text.
            // Null falls back to the default defined on the Content model.
            $table->string('background_color')->nullable()->after('text_body');
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('background_color');
        });
    }
};
