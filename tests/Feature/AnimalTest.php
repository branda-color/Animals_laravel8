<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Type;
use App\Models\Animal;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;



class AnimalTest extends TestCase
{
    //加入重置資料庫triat
    use RefreshDatabase;
    /**
     * 測試animla列表上的json結構
     */
    public function testViewAllAnimal()
    {
        //模擬客戶端憑證授權使用模型工廠建立1個客戶端
        Passport::actingAsClient(
            Client::factory()->create()
        );

        //使用模型工廠製作五個分類
        Type::factory(5)->create();

        //5個使用者
        User::factory(5)->create();

        //10比動物資料
        Animal::factory(10)->create();

        //使用get請求api/animals結果賦予$response
        $response = $this->json('GET', 'api/animals');
        //設定當錯誤發生時錯誤訊息顯示
        $this->withoutExceptionHandling();

        $resultStructure = [
            "data" => [
                //多筆資料使用*檢查每一筆是否有以下欄位
                '*' => [
                    "id", "type_id", "type_name", "name", "birthday", "age",
                    "area", "fix", "description", "created_at", "updated_at"
                ]
            ],
            "links" => [
                "first", "last", "prev", "next"
            ],
            "meta" => [
                "current_page", "from", "last_page", "path",
                "per_page", "to", "total"
            ]
        ];
        //assertJsonStruct判斷Json 結構是否與我們想像中相同
        $response->assertStatus(200)
            ->assertJsonStructure($resultStructure);
    }
    /**
     * 測試有效token創建資源
     * 測試建立animal
     */
    public function testCanCreateAnimal()
    {
        //創建會員
        $user = User::factory()->create();
        //模擬會員權限
        Passport::actingAs(
            $user,
            ['create-animals'] //設定必須有create-animals的scope權限
        );
        //如果有例外顯示測試output介面上
        $this->withoutExceptionHandling();

        //建一個分類資料夾
        $type = Type::factory()->create();

        //請求資料

        $formData = [
            "type_id" => $type->id,
            "name" => "yuyu",
            "birthday" => "2017-01-01",
            "area" => "USA",
            "fix" => 1,
        ];


        //請求傳入資料
        $response = $this->json(
            'POST',
            'api/animals',
            $formData
        );

        //檢查返回資料
        $response->assertStatus(201)
            ->assertJsonFragment($formData);
    }
    public function testCanNotCreateAnimal()
    {

        //建一個分類資料夾
        $type = Type::factory()->create();


        //請求傳入資料
        $response = $this->json(
            'POST',
            'api/animals',
            [
                'type_id' => $type->id,
                'name' => '大黑',
                'birthday' => '2017-01-01',
                'area' => '台北市',
                'fix' => '1'

            ]
        );

        //檢查返回資料
        $response->assertStatus(401)
            ->assertJsonFragment([
                'message' => "Unauthenticated."
            ]);
    }
}
