<?php

namespace App\Services;

use App\Models\Animal;



class AnimalService
{
    //篩選程式邏輯設定filters參數
    public  function filterAnimals($query, $filters)
    {
        if (isset($filters)) {
            $filters = explode(',', $filters); //字串切割成陣列
            foreach ($filters as $key => $filter) {
                list($key, $value) = explode(':', $filter); //第一元素變成key,第二元素變成value
                $query->where($key, 'like', "%$value%"); //sql的where語法篩選部分字串
            }
        }
    }

    //專門負責排序動物列表
    public function sortAnimals($query, $sorts)
    {
        if (isset($sorts)) {
            $sortArray = explode(',', $sorts);
            foreach ($sortArray as $key => $sort) {
                list($key, $value) = explode(':', $sort);
                if ($value == 'asc' || $value == 'desc') {
                    $query->orderBy($key, $value);
                }
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        return $query;
    }


    public function getListData($request)
    {
        $limit = $request->limit ?? 10;
        $query = Animal::query()->with('type');
        $query = $this->animalService->filterAnimals($query, $request->filters);
        $query = $this->animalService->sortAnimals($query, $request->sorts);
        $animals = $query
            ->paginate($limit)
            ->appends($request->query());

        return $animals;
    }
}
