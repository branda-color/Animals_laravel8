<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalCollection;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;


class AnimalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*建立查詢清單格式(全部查詢)
        $animals = Animal::get();
        return response(['data' => $animals], Response::HTTP_OK);*/


        //建立快取

        //取得網址
        $url = $request->url();
        //取得參數
        $queryParams = $request->query();
        //使用參數第一個字母排序
        ksort($queryParams);
        //將參數轉為字串
        $queryString = http_build_query($queryParams);
        //組合網址
        $fullUrl = "{$url}?{$queryString}";
        //使用laravel的快取方法檢查是否有快取紀錄
        if (Cache::has($fullUrl)) {
            //直接回傳快取
            return Cache::get($fullUrl);
        }



        //設定預設值
        $limit = $request->limit ?? 10;

        //建立查詢建構器，分段寫sql
        $query = Animal::query()->with('type'); //跟Anima->model做關聯(使用model內的type方法)

        //篩選程式邏輯設定filters參數
        if (isset($request->filters)) {
            $filters = explode(',', $request->filters); //字串切割成陣列
            foreach ($filters as $key => $filter) {
                list($key, $value) = explode(':', $filter); //第一元素變成key,第二元素變成value
                $query->where($key, 'like', "%$value%"); //sql的where語法篩選部分字串
            }
        }

        /*使用sql語法排序
        $animals = $query->orderby('id', 'desc')
            ->paginate($limit)
            ->appends($request->query());
        return response($animals, Response::HTTP_OK);*/


        //排列查詢順序
        if (isset($request->sorts)) {
            $sorts = explode(',', $request->sorts);
            foreach ($sorts as $key => $sort) {
                list($key, $value) = explode(':', $sort);
                if ($value == 'asc' || $value == 'desc') {
                    $query->orderBy($key, $value);
                }
            }
        } else {
            $query->orderBy('id', 'desc');
        }


        $animals = $query
            ->paginate($limit)
            ->appends($request->query());

        return Cache::remember($fullUrl, 60, function () use ($animals) {
            //return response($animals, Response::HTTP_OK);
            return new AnimalCollection($animals);
        });
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        //建立驗證表單新增
        $this->validate($request, [
            'type_id' => 'nullable|exists:types,id',
            'name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'area' => 'nullable|string|max:255',
            'fix' => 'required|boolean',
            'description' => 'nullable',
            'personality' => 'nullable'
        ]);


        $request['user_id'] = 1;
        $animal = Animal::create($request->all());
        $animal = $animal->refresh();
        return response($animal, Response::HTTP_CREATED);
    }

    /**
     * 查看單一資料(跟resource結合客製化輸出內容)
     */
    public function show(Animal $animal)
    {
        /*get請求api查詢資料(以ID為key一筆)
        return response($animal, Response::HTTP_OK);*/

        return new AnimalResource($animal);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function edit(Animal $animal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        //更新資料的方法
        $this->validate($request, [
            'type_id' => 'nullable|exists:types,id',
        ]);
        $animal->update($request->all());
        return response($animal, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animal $animal)
    {
        //移除資料
        $animal->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
