<?php

namespace App\Policies;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimalPolicy
{
    use HandlesAuthorization;

    /**
     * 查看所有資源資料
     * 不需要登入就可以請求API
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * 查看單一詳細資料
     * 不需要登入就可以請求API
     *
     */
    public function view(User $user, Animal $animal)
    {
        return true;
    }

    /**
     * 定義一個brfore的方法，會在其他檢查原則前執行
     */
    public function before($user, $ability)
    {
        //利用User Model定義的isAdmin判斷
        if ($user->isAdmin()) {
            return true;
        }
    }





    /**
     * Determine whether the user can create models.
     * 建立資源資料
     * 全部會員都可以建立動物資料，回傳true即可
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * 刊登的動物資料只能讓相同使用者更新
     */
    public function update(User $user, Animal $animal)
    {
        //只有刊登動物的會員可以更新自己的動物資料
        return $user->id === $animal->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * 刪除動物資料，刊登的動物資料只能讓相同使用者刪除
     * 
     */
    public function delete(User $user, Animal $animal)
    {
        return $user->id === $animal->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     * 復原軟體刪除，類似資料丟到垃圾桶後。要再把資料救回來的邏輯
     * 刊登的動物資料只能讓相同使用者復原
     */
    public function restore(User $user, Animal $animal)
    {
        return $user->id === $animal->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 軟體刪除後，強制刪除資料表的動物資料，類似資料丟到垃圾桶後，要永久刪除資料時的邏輯判斷
     * 刊登的動物資料只能讓相同使用者刪除
     */
    public function forceDelete(User $user, Animal $animal)
    {
        return $user->id === $animal->user_id;
    }
}
