<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->increments('id');

            $table->text('text');
            $table->text('text_source');

            // Counters
            $table->integer('replies_count')->unsigned()->default(0);

            // Relations
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups');

            // Vote counts
            $table->integer('uv')->unsigned()->default(0);
            $table->integer('dv')->unsigned()->default(0);
            $table->integer('score')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('entries');
    }
}
