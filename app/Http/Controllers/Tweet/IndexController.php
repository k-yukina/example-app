<?php

namespace App\Http\Controllers\Tweet;

use App\Http\Controllers\Controller;
use App\Services\TweetService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function __invoke(Request $request, TweetService $tweetService)
    {
        //tweetsServiceのgetTweetsメソッドから結果を持ってくる
        $tweets = $tweetService->getTweets();
        //dump($tweets);
        //app(\App\Exceptions\Handler::class)->render(request(), throw new \Error('dump report.'));
        //取ってきたtweetsの配列をtweetsの名前でviewのtweet.indexへ

        return view('tweet.index')
            ->with('tweets', $tweets);
    }
}
