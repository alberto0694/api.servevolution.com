<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class OrdemServicoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'data' => Carbon::now()->subDays(rand(1, 40)),
            'hora' => Carbon::now()->subDays(rand(1, 40)),
            'descricao' => $this->faker->text(),
            'tipo_servico_id' => $this->getId(5),
            'cliente_id' => $this->getId(30),
            'ativo' => true
        ];
    }

    public function getId($max)
    {
        return random_int(1, $max);
    }
}
