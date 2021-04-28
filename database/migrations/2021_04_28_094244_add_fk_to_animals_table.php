<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //外鍵約束(外鍵在一個表指向另一個表的主鍵->兩個表間有利用id欄位產生關連)->設定刪除時才觸發
        Schema::table('animals', function (Blueprint $table) {
            $table->foreign('user_id') //animals資料表參照user_id欄位
                ->references('id')->on('users') //參照users資料表的id
                ->onDelete('cascade'); //若users刪除，資料表一起刪除

            $table->foreign('type_id') //animals資料表參照type_id欄位
                ->references('id')->on('types') //參照type資料表的id
                ->onDelete('set null'); //若types刪除，相關資料type_id設為null
        });
    }

    /**
     *恢復資料庫(up的動作復原->與up相反)
     *要恢復前都先建議刪除資料外鍵的設定然後再復原
     */
    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            //刪除資料外鍵dropForeign(資料表名稱_參照欄位名_foreign)
            $table->dropForeign('animals_user_id_foreign');
            $table->dropForeign('animals_type_id_foreign');
        });
    }
}
