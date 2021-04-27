<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\Animal;
use App\Models\Type;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        Animal::truncate(); //清空資料表歸零
        User::truncate();
        Type::truncate();

        Type::factory(5)->create();
        User::factory(5)->create();
        Animal::factory(15)->create();

        Schema::enableForeignKeyConstraints();
    }
}
