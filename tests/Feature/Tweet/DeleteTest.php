<?php

namespace Tests\Feature\Tweet;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;

class DeleteTest extends TestCase
{
    //フィーチャーテスト実行前後にDB初期化
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_successed()
    {
        //ユーザ作成
        $user = User::factory()->create();

        //つぶやきを作成
        $tweet = Tweet::factory()->create(['user_id' => $user->id]);

        //指定したユーザでログインした状態にする
        $this->actingAs($user);

        //作成したつぶやきIDを指定
        $response = $this->delete('/tweet/delete/' . $tweet->id);

        $response->assertRedirect('/tweet');
    }
}
