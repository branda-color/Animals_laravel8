<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //取得分類相關動物資料
    use HasFactory;
    protected $fillable = [
        'name',
        'sort'
    ];

    //跟animals資料表做關聯
    public function animals()
    {
        //hasmany(類別名稱,參照欄位,主鍵)
        return $this->hasMany('App\Models\Animal', 'type_id', 'id');
    }
}
