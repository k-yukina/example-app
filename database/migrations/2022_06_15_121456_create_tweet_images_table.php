<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweet_images', function (Blueprint $table) {
            // tweetsテーブルに存在するidでなければ、tweet_idに格納できない。
            // また、->cascadeOnDelete宣言で、紐づくidのレコード削除が行われた際、tweet_imagesテーブルの紐づきを解除する。
            $table->foreignId('tweet_id')->constrained('tweets')->cascadeOnDelete();
            $table->foreignId('image_id')->constrained('images')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweet_images');
    }
};
