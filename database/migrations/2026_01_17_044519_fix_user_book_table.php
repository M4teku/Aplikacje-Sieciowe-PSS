<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Sprawdź czy kolumna id już istnieje
        if (!Schema::hasColumn('user_book', 'id')) {
            Schema::table('user_book', function (Blueprint $table) {
                $table->id()->first();
            });
        }
    }

    public function down()
    {
        Schema::table('user_book', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};