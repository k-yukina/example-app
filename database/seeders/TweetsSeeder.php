<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tweet;
use App\Models\Image;

class TweetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TweetFactoryからデータ10件作成
        Tweet::factory()->count(10)->create()->each(fn($tweet) =>
            // ImageFactoryからデータ4件作成
            Image::factory()->count(4)->create()->each(fn($image) =>
                // attachでImageIdを紐づけて交差テーブルに保存
                $tweet->images()->attach($image->id)
            )
        );
    }
}
