<?php

namespace App\Services;

use App\Models\Tweet;
use Carbon\Carbon;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Modules\ImageUpload\ImageManagerInterface;

class TweetService
{
public function __construct(private ImageManagerInterface $imageManager)
{
}

    //一覧で全てのつぶやきを取得するメソッド
    public function getTweets()
    {
        //DBから、作成日時の降順でデータ(Imageも)を取ってくる。
        return Tweet::with('images')->orderBy('created_at', 'DESC')->get();
    }

    //自分のtweetかどうかをチェックするメソッド(編集削除ができるできないのチェック)
    public function checkOwnTweet(int $userId, int $tweetId): bool
    {
        //userIdとtweetIdが一致すれば、$tweetにつぶやき情報を取得
        $tweet = Tweet::where('id', $tweetId)->first();
        //IDと一致するつぶやきがなければfalseを返す
        if (!$tweet) {
            return false;
        }
        //データがある場合はつぶやきのuser_idとuserIdが一致すれば、trueを返す
        //違えばfalseを返す
        return $tweet->user_id === $userId;
    }

    //昨日追加されたつぶやきを取ってくる
    public function countYesterdayTweets(): int
    {
        return Tweet::whereDate('created_at', '>=', Carbon::yesterday()->toDateTimeString())
            ->whereDate('created_at', '>', Carbon::today()->toDateTimeString())
            ->count();
    }

    //つぶやきを保存するメソッド
    public function saveTweet(int $userId, string $content, array $images)
    {
        // DBファサードを利用
        DB::transaction(function () use ($userId, $content, $images) {
            $tweet = new Tweet;
            $tweet->user_id = $userId;
            $tweet->content = $content;
            $tweet->save();
            foreach ($images as $image) {
                //Storage::putFile('public/images', $image);
                //imageManager経由で画僧の保存削除をする
                $name = $this->imageManager->save($image);
                $imageModel = new Image();
                //$imageModel->name = $image->hashName();
                $imageModel->name = $name;
                $imageModel->save();
                $tweet->images()->attach($imageModel->id);
            }
        });
    }

    public function deleteTweet(int $tweetId)
    {
        DB::transaction(function () use ($tweetId) {
            //対象のつぶやきを取得
            $tweet = Tweet::where('id', $tweetId)->firstOrFail();
            //対象のつぶやきで使用している画像を１件ずつ参照
            $tweet->images()->each(function ($image) use ($tweet) {
                //画像の格納先を参照
                //$filePath = 'public/images/' . $image->name;
                //画像が存在すれば、削除
                //if(Storage::exists($filePath)){
                //    Storage::delete($filePath);
                //}
                $this->imageManager->delete($image->name);
                //つぶやきと画像の紐づけを削除
                $tweet->images()->detach($image->id);
                //画像を削除
                $image->delete();
            });

            //つぶやきを削除
            $tweet->delete();
        });
    }
}
