<?php

namespace App\Http\Controllers\Api\Animal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Animal;
use Symfony\Component\HttpFoundation\Response;

class AnimalLikeController extends Controller
{

    public function __construct()
    {
        /**
         * 一定要加上中介層auth，必須登入才能使用
         * 另外一方面，沒有加入中介層下方auth()無法讀取到會員登入資訊
         */
        $this->middleware('auth:api');
    }
    /**
     * 查詢多筆
     * 記得傳入網址{animal}參數，laravle會自動綁定
     */
    public function index(Animal $animal)
    {
        return $animal->likes()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 新增功能
     * 記得傳入網址{animal}參數，laravel會自動綁定
     * 
     */
    public function store(Request $request, Animal $animal)
    {
        $result = $animal->likes()->toggle(auth()->user()->id); //toggle 方法來「切換」給定 ID 的附加狀態(已有關連的就移除，沒關聯的加入)
        return response($result, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
