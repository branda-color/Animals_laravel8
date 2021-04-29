<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


//管理新建動物資源請求的表單驗證

class StoreAnimalRequest extends FormRequest
{
    /**
     * 是否有權限可以操作
     * 可以在方法中撰寫是否能操作新建動物請求的邏輯判斷
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 請求資料欄位規則
     */
    public function rules()
    {
        return [
            'type_id' => 'nullable|exists:types,id',
            'name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'area' => 'nullable|string|max:255',
            'fix' => 'required|boolean',
            'description' => 'nullable',
            'personality' => 'nullable'
        ];
    }
}
