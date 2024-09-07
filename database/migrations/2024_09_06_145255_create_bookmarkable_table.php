<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarkable', function (Blueprint $table) {
            $table->foreignId("bookmark_id")->references('id')->on('bookmarks')->onDelete('cascade');

            $table->unsignedBigInteger("bookmarkable_id");
            $table->string("bookmarkable_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmarkable');
    }
};
