<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PessoaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'razao' => $this->getRazao(),
            'apelido' => $this->faker->name(),
            'nome' => $this->faker->name(),
            'foto' => null,
            'contatoImediato' => $this->faker->phoneNumber(),
            'telefone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'ativo' => true
        ];
    }

    public function getRazao()
    {
        return random_int(0, 10) % 2 == 0 ? $this->faker->word() : null;
    }
}
