<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_book', function (Blueprint $table) {
            // Dodaj kolumnę id jako klucz główny
            if (!Schema::hasColumn('user_book', 'id')) {
                $table->id()->first();
            }
        });
    }

    public function down()
    {
        Schema::table('user_book', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};