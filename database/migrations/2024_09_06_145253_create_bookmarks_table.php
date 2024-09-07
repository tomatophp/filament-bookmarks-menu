<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();

            $table->string('type')->default('folder')->nullable();

            $table->string('name')->index()->unique();

            $table->string('icon')->nullable()->default('heroicon-s-folder');
            $table->string('color')->nullable();

            $table->boolean('is_private')->default(false)->nullable();
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreignId('parent_id')->nullable()->constrained('bookmarks');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
