<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AnimalCollection extends ResourceCollection
{
    /**
     * 客製化輸出內容，放入陣列內再輸出(處理多筆命名後面+collection)
     */
    public function toArray($request)
    {
        return [
            //使用AnimalResource類別轉換，使用靜態方法collection轉換集合內每一筆資料
            'data' => AnimalResource::collection($this->collection)
        ];
    }
}
