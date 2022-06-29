<?php

namespace App\Http\Controllers\Tweet\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Tweet\UpdateRequest;
use App\Models\Tweet;
use App\Services\TweetService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateRequest $request, TweetService $tweetService)
    {
        if(!$tweetService->checkOwnTweet($request->user()->id, $request->id())){
            throw new AccessDniedHttpException();
        }
        //カラムidと指定したidのレコードをDBから探し出し、tweet配列に入れる
        $tweet = Tweet::where('id', $request->id())->firstOrFail();
        //tweetとして入力されたつぶやきをcontentカラムへ入れる
        $tweet->content = $request->tweet();
        //DBへ保存
        $tweet->save();
        //tweet.update.indexへ(routeの第2引数はtweet.update.putのバスがtweetIdを欲しているため)
        return redirect()
            ->route('tweet.update.index', ['tweetId' => $tweet->id])
            //フラッシュセッションの設定(リダイレクトと一緒に送り、viewで表示すれば、処理を完了してリダイレクトとして戻ってきたことがわかる)
            ->with('feedback.success', "つぶやきを編集しました");
    }
}
