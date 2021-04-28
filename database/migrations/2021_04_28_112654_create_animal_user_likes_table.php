<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalUserLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_user_likes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('animal_id')->unsigned()->comment('動物ID');
            $table->bigInteger('user_id')->unsigned()->comment('使用者ID');
            $table->timestamps();

            //外鍵約束設定user_id關聯user資料表id
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade'); //若users刪除，資料表一起刪除(移除關聯紀錄)

            //外鍵約束設定animals_id關聯animals資料表id
            $table->foreign('animal_id')
                ->references('id')->on('animals')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animal_user_likes', function (Blueprint $table) {
            //刪除資料庫外鍵
            $table->dropForeign('animal_user_likes_user_id_foreign');
            $table->dropForeign('animal_user_likes_animal_id_foreign');
        });
        Schema::dropIfExists('animal_user_likes');
    }
}
