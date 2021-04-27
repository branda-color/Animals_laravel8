<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * 客製化輸出內容，放入陣列內再輸出(處理單筆命名後面+resource)
     * 
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_id' => isset($this->type) ? $this->type_id : null,
            'type_name' => isset($this->type) ? $this->type->name : null, //使用type資料表關聯，取得分類資料再找分類名稱，若找不到就顯示空值
            'name' => $this->name,
            'birthday' => $this->birthday,
            'age' => $this->age, //資料表沒有這個欄位，利用model再創立
            'area' => $this->area,
            'fix' => $this->fix,
            'description' => $this->description,
            'personality' => $this->personality,
            'created_at' => (string)$this->created_at, //強制轉換成文字
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,

        ];
    }
}
