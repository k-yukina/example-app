<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testSuccessfullLogin()
    {
        $this->browse(function (Browser $browser) {
            //テスト用のユーザ用意
            $user = User::factory()->create();
            //ログイン時に利用するパス指定
            $browser->visit('/login')
                    //メールアドレス入力
                    ->type('email', $user->email)
                    //パスワード入力(Userファクトリーではパスワードはデフォルトでpassword)
                    ->type('password', 'password')
                    //ログインボタン押下
                    ->press('LOG IN')
                    //リダイレクト
                    ->assertPathIs('/tweet')
                    //つぶやきアプリの文字の表示確認
                    ->assertSee('つぶやきアプリ');
        });
    }
}
