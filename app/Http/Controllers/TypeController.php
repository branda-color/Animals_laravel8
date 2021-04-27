<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\TypeCollection;
use App\Http\Resources\TypeResource;

class TypeController extends Controller
{
    /**
     *查詢資源列表，資源篩選/排序/分頁(進)
     */
    public function index()
    {
        /*分類較少直接全部輸出
        $types = Type::get();
        return response([
            'data' => $types //輸出使用data包住
        ], Response::HTTP_OK);
        200請求成功。成功的意義依照 HTTP 方法而定：
        GET：資源成功獲取並於訊息主體中發送。
        HEAD：entity 標頭已於訊息主體中。
        POST：已傳送訊息主體中的 resource describing the result of the action。
        TRACE：伺服器已接收到訊息主體內含的請求訊息。
        */
        $types = Type::select('id', 'name', 'sort')->get(); //只查詢需要的欄位
        return new TypeCollection($types);
    }

    /**
     * 回傳網頁畫面功能(出)
     * 新增動物網頁畫面
     * (新增一個controller來另外處理網頁畫面)
     */
    public function create()
    {
        //
    }

    /**
     * 新建分類功能(進)
     */
    public function store(Request $request, Type $type)
    {
        $this->validate($request, [
            //使用陣列傳入驗證關鍵字
            'name' => [
                'required',
                'max:50',
                //type資料表中name欄位資料是唯一值
                Rule::unique('types', 'name')
            ],
            'sort' => 'nullable|integer',
        ]);
        //若沒有傳入sort欄位內容
        if (!isset($request->sort)) {
            //找到目前資料表的欄位排序最大值
            $max = Type::max('sort');
            $request['sort'] = $max + 1; //最大值自動加1寫入資料表中

        }

        $type = Type::create($request->all()); //寫入資料庫

        /*
        return response([
            'data' => $type
        ], Response::HTTP_CREATED); //201請求成功且新的資源成功被創建，通常用於 POST 或一些 PUT 請求後的回應。*/

        return new TypeResource($type);
    }

    /**
     *查看單一分類詳細資料
     */
    public function show(Type $type)
    {
        /*return response([
            'data' => $type
        ], Response::HTTP_OK);*/
        return new TypeResource($type);
    }

    /**
     * 回傳網頁畫面功能(出)
     * 編輯動物網頁畫面
     * (新增一個controller來另外處理網頁畫面)
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     *更新已存在分類 
     *
     */
    public function update(Request $request, Type $type)
    {
        $this->validate($request, [ //validate驗證資料正確性
            'name' => [
                'max:50',
                //更新時排除自己的名稱後，檢查是否為唯一值
                Rule::unique('types', 'name')->ignore($type->name, 'name')
            ],
            'sort' => 'nullable|integer',
        ]);
        $type->update($request->all());

        /*return response([
            'data' => $type
        ], Response::HTTP_OK);*/
        return new TypeResource($type);
    }

    /**
     * 刪除單一分類
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return response(null, Response::HTTP_NO_CONTENT); //204沒有要發送的內容用於此請求，但是標頭可能有用。用戶代理可以使用新的代理更新該資源的其緩存的標頭。
    }
}
