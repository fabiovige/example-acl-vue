<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('children', function (Blueprint $table) {
            $table->renameColumn('user_id', 'parent_id');
            $table->foreign('parent_id')->nullable()->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('children', function (Blueprint $table) {
            $table->renameColumn('parent_id', 'user_id');
        });
    }
};
