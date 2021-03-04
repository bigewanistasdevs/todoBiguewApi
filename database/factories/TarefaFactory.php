<?php

namespace Database\Factories;

use App\Models\Tarefa;
use App\Models\Lista;

use Illuminate\Database\Eloquent\Factories\Factory;

class TarefaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tarefa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'titulo' => $this->faker->catchPhrase(),
            'descricao' => $this->faker->paragraph(),
            'concluida' => $this->faker->boolean(),
            'lista' => Lista::inRandomOrder()->first()->id,
        ];
    }
}
