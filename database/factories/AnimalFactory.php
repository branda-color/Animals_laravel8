<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Type;

class AnimalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Animal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        // $collection =  DB::table('types')->select('id')->get();
        return [

            'type_id' => Type::all()->random()->id,
            'name' => $this->faker->name,
            'birthday' => $this->faker->date(),
            'area' => $this->faker->city,
            'fix' => $this->faker->boolean,
            'description' => $this->faker->text(50),
            'personality' => $this->faker->text(20),
            'user_id' => $this->faker->numberBetween(1, 5)
        ];
    }
}
