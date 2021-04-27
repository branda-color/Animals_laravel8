<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($type) { //使用transform方法傳入type參數
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'sort' => $type->sort,
                ];
            })
        ];
    }
}
