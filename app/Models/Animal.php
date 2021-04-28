<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Animal extends Model
{
    //取得動物分類
    use HasFactory;
    protected $fillable = [
        //限制欄位可以批量寫入
        'type_id',
        'name',
        'birthday',
        'area',
        'fix',
        'description',
        'personality',

    ];


    //跟type資料表做關聯
    public function type()
    {
        //belongsTo(類別名稱,參照欄位,主鍵)
        return $this->belongsTo('App\Models\Type');
    }

    //建立AnimalResource沒有的欄位
    public function getAgeAttribute() //命名須以getxxxAttribute,設定完成可以使用$animal->age會直接訪問此方法
    {
        $diff = Carbon::now()->diff($this->birthday); //diff方法拿該集合與其他集合或純 PHP 陣列進行比較
        return "{$diff->y}歲{$diff->m}月";
    }

    /**
     * 取得動物刊登會員，一對多的反向關聯
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 多對多關聯animal與user我的最愛關係
     */
    public function likes()
    {
        return $this->belongsToMany('App\Models\User', 'animal_user_likes')
            ->withTimestamps();
    }
}
