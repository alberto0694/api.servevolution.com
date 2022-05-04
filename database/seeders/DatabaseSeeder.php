<?php

namespace Database\Seeders;

use App\Models\TipoCusto;
use App\Models\TipoServico;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Funcionario;
use App\Models\Colaborador;
use App\Models\Pessoa;
use App\Models\Cliente;
use App\Models\Permissao\Permissao;
use App\Models\Permissao\Papel;
use App\Models\Permissao\Menu;
use App\Models\Permissao\PapelUsuario;
use Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->fakeDataSeed();
        $this->permissaoSeed();

    }

    public function fakeDataSeed(){

        $faker = \Faker\Factory::create();

        $colaboradores = Pessoa::factory()
                        ->count(30)
                        ->create()
                        ->each(function($pessoa) use($faker) {
                            $pessoa->colaboradores()->save(new Colaborador());
                            $pessoa->usuarios()->save(new Usuario([
                                'name' => $pessoa->razao ?? $pessoa->apelido,
                                'email' => $faker->email(),
                                'password' => bcrypt('123456')
                            ]));
                        });

        $clientes = Pessoa::factory()
                        ->count(30)
                        ->create()
                        ->each(function($pessoa) use($faker) {
                            $pessoa->clientes()->save(new Cliente());
                            $pessoa->usuarios()->save(new Usuario([
                                'name' => $pessoa->razao ?? $pessoa->apelido,
                                'email' => $faker->email(),
                                'password' => bcrypt('123456')
                            ]));
                        });

        $funcionarios = Pessoa::factory()
                        ->count(30)
                        ->create()
                        ->each(function($pessoa){
                            $pessoa->funcionarios()->save(new Funcionario());
                        });


        TipoServico::insert([
            [ 'descricao' => 'Limpeza'],
            [ 'descricao' => 'Carregamento'],
            [ 'descricao' => 'Serviços Gerais'],
            [ 'descricao' => 'Lavação'],
            [ 'descricao' => 'Cuidadora']
        ]);

        TipoCusto::insert([
            [ 'descricao' => 'Marmita' ],
            [ 'descricao' => 'Uber' ],
            [ 'descricao' => 'Extras' ]
        ]);

    }

    public function permissaoSeed(){

        Permissao::insert([
            [ 'programa' => "PROG_CADASTRO", 'titulo' => "Programa de Cadastros", 'descricao' => "Cadastros" ],
            [ 'programa' => "PROG_ORDEM_SERVICO", 'titulo' => "Programa de Serviços", 'descricao' => "Manipulação dos serviços prestados." ],
            [ 'programa' => "PROG_CLIENTE", 'titulo' => "Programa Operacional/Transporte", 'descricao' => "Operacional/Transporte" ],
        ]);

        Papel::insert([
            [ 'permissao_id' => 1, 'acao' => "LISTAR_FUNCIONARIOS", 'descricao' => "Acesso a tela de listagem de funcionários" ],
            [ 'permissao_id' => 1, 'acao' => "INSERIR_FUNCIONARIO", 'descricao' => "Insere uma nova pessoa ao sistema" ],
            [ 'permissao_id' => 1, 'acao' => "VISUALIZAR_FUNCIONARIO", 'descricao' => "Visualização de dados específicos de uma pessoa do sistema." ],

            [ 'permissao_id' => 1, 'acao' => "LISTAR_TIPO_SERVICOS", 'descricao' => "Acesso a tela de listagem de funcionários" ],
            [ 'permissao_id' => 1, 'acao' => "INSERIR_TIPO_SERVICO", 'descricao' => "Insere uma nova pessoa ao sistema" ],
            [ 'permissao_id' => 1, 'acao' => "VISUALIZAR_TIPO_SERVICO", 'descricao' => "Visualização de dados específicos de uma pessoa do sistema." ],

            [ 'permissao_id' => 2, 'acao' => "LISTAR_ORDEM_SERVICOS", 'descricao' => "Inativa uma pessoa existente no sistema." ],
            [ 'permissao_id' => 2, 'acao' => "INSERIR_ORDEM_SERVICO", 'descricao' => "Relatórios de gestao de pessoas." ],
            [ 'permissao_id' => 2, 'acao' => "INSERIR_ORDEM_SERVICO", 'descricao' => "Relatórios de gestao de pessoas." ],

            [ 'permissao_id' => 3, 'acao' => "LISTAR_ORDEM_SERVICOS_CLIENTE", 'descricao' => "Inativa uma pessoa existente no sistema." ],
            [ 'permissao_id' => 3, 'acao' => "VISUALIZAR_ORDEM_SERVICOS_CLIENTE", 'descricao' => "Inativa uma pessoa existente no sistema." ],

        ]);

        Menu::insert([
            ['titulo' => "Cadastros", 'nivel' => 1, 'papel_id' => 1, 'menu_pai_id' => null, 'rota' => null, 'icone' => "pessoa-edit-white28x28", 'icone_aux' => "pessoa-edit28x28" ],
            ['titulo' => "Funcionários", 'nivel' => 2, 'papel_id' => 1, 'menu_pai_id' => 1, 'rota' => "/app/funcionarios", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28" ],
            ['titulo' => "Tipos de Serviços", 'nivel' => 2, 'papel_id' => 2, 'menu_pai_id' => 1, 'rota' => "/app/tipo-servicos", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28" ],
            ['titulo' => "Clientes", 'nivel' => 2, 'papel_id' => 2, 'menu_pai_id' => 1, 'rota' => "/app/clientes", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28" ],
            ['titulo' => "Tabelas de Preço", 'nivel' => 2, 'papel_id' => 2, 'menu_pai_id' => 1, 'rota' => "/app/tabelas-preco", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28" ],

            ['titulo' => "Ordem de Serviço", 'nivel' => 1, 'papel_id' => 7, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28" ],
            ['titulo' => "Agendamentos", 'nivel' => 2, 'papel_id' => 7, 'menu_pai_id' => 6, 'rota' => "/app/ordem-servicos/agendamentos", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],

            ['titulo' => "Área do Cliente", 'nivel' => 1, 'papel_id' => 8, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28" ],
            ['titulo' => "Agendamentos", 'nivel' => 2, 'papel_id' => 8, 'menu_pai_id' => 8, 'rota' => "/app/cliente/agendamentos", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],
        ]);

        PapelUsuario::insert([
            ['papel_id' => 1, 'usuario_id' => 1 ],
            ['papel_id' => 2, 'usuario_id' => 1 ],
            ['papel_id' => 3, 'usuario_id' => 1 ],
            ['papel_id' => 4, 'usuario_id' => 1 ],
            ['papel_id' => 5, 'usuario_id' => 1 ],
            ['papel_id' => 6, 'usuario_id' => 1 ],
            ['papel_id' => 7, 'usuario_id' => 1 ],

            ['papel_id' => 8, 'usuario_id' => 31 ], //cliente
        ]);

    }


}
