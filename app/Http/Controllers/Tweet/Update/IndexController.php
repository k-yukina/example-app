<?php

namespace App\Http\Controllers\Tweet\Update;

use App\Http\Controllers\Controller;
use App\Models\Tweet;
use App\Services\TweetService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, TweetService $tweetService)
    {
        //getメソッドで受け取ったtweetIdをint型として変数tweetIdで受け取る
        $tweetId = (int)$request->route('tweetId');
        //自分の投稿したつぶやき以外のつぶやきを編集しようとすると、403エラー
        if (!$tweetService->checkOwnTweet($request->user()->id, $tweetId)) {
            throw new AccessDeniedHttpException();
        }
        //カラムidと指定したtweetIdのレコードをDBから探し出し、tweet配列に入れる
        $tweet = Tweet::where('id', $tweetId)->firstOrFail();
        //tweet配列を引数tweetとしてviewのtweet.updateへ
        return view('tweet.update')->with('tweet', $tweet);
    }
}
