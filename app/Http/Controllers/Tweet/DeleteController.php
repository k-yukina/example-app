<?php

namespace App\Http\Controllers\Tweet;

use App\Http\Controllers\Controller;
use App\Models\Tweet;
use App\Services\TweetService;
use Illuminate\Http\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, TweetService $tweetService)
    {
        //つぶやきを取得
        $tweetId = (int) $request->route('tweetId');
        //ログインユーザのIDを取得し、checkOwnTweetを実行
        if(!$tweetService->checkOwnTweet($request->user()->id, $tweetId)){
            //つぶやきを削除できる権限がなければ、Exceptionを返す
            throw new AccessDeniedHttpException();
        }
        //つぶやきを削除
        $tweetService->deleteTweet($tweetId);
        //tweet.indexへリダイレクト
        return redirect()
            ->route('tweet.index')
            ->with('feedback.success', "つぶやきを削除しました");
    }
}
