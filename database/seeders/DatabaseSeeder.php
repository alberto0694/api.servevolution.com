<?php

namespace Database\Seeders;

use App\Models\TipoCusto;
use App\Models\UnidadeMedida;
use App\Models\TipoServico;
use App\Models\ValoresServicos;
use App\Models\OrdemServicoCusto;
use App\Models\OrdemServicoStatus;
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
use App\Models\OrdemServico;
use App\Models\OrdemServicoFuncionario;
use Faker;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->preDataseed();
        $this->fakeDataSeed();
        $this->permissaoSeed();
    }

    private function preDataseed(){

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

        UnidadeMedida::insert([
            [ 'descricao' => 'Hora' ],
            [ 'descricao' => 'Diária' ],
            [ 'descricao' => 'Noturno' ]
        ]);

    }

    private function fakeDataSeed(){
    
        $faker = \Faker\Factory::create();
        $tp_servicos = collect(TipoServico::all())->toArray();
        $un_medidas = collect(UnidadeMedida::all())->toArray();

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
                        ->each(function($pessoa) use($faker, $tp_servicos, $un_medidas) {
                            
                            $pessoa->clientes()->save(new Cliente());
                            $pessoa->usuarios()->save(new Usuario([
                                'name' => $pessoa->razao ?? $pessoa->apelido,
                                'email' => $faker->email(),
                                'password' => bcrypt('123456')
                            ]));

                            for ($i=0; $i < 5; $i++) { 
                                                                
                                $servico = ValoresServicos::create([
                                    'tipo_servico_id' => Arr::random($tp_servicos, 1)[0]['id'],
                                    'cliente_id' => $pessoa->clientes[0]->id,
                                    'unidade_medida_id' => Arr::random($un_medidas, 1)[0]['id'],
                                    'valor' => 100
                                ]);

                            }
                        });

        $funcionarios = Pessoa::factory()
                        ->count(30)
                        ->create()
                        ->each(function($pessoa){
                            $pessoa->funcionarios()->save(new Funcionario());
                        });


        $ordens = OrdemServico::factory()
                    ->count(60)
                    ->create()
                    ->each(function($ordem) use ($funcionarios){

                        OrdemServicoStatus::create([
                            'ordem_servico_id' => $ordem->id,
                            'descricao' => 'agendado'
                        ]);

                        $keys = array_rand($funcionarios->toArray(), random_int(2, 5));
                        collect(Funcionario::whereIn('id', $keys)->get())
                            ->each(fn($f) => $ordem->funcionarios()->save($f));
                    });

        collect(OrdemServicoFuncionario::all())->each(function($osf) {
            $osf->custos()->save(OrdemServicoCusto::create([
                    'tipo_custo_id' => random_int(1, 3),
                    'valor' => random_int(10, 20)
                ]));
        });
    }

    private function permissaoSeed(){

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
            ['titulo' => "Tipos de Custos", 'nivel' => 2, 'papel_id' => 2, 'menu_pai_id' => 1, 'rota' => "/app/tipo-custos", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28" ],
            ['titulo' => "Clientes", 'nivel' => 2, 'papel_id' => 2, 'menu_pai_id' => 1, 'rota' => "/app/clientes", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28" ],
            ['titulo' => "Unidades de Medida", 'nivel' => 2, 'papel_id' => 2, 'menu_pai_id' => 1, 'rota' => "/app/unidade-medida", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28" ],

            ['titulo' => "Ordem de Serviço", 'nivel' => 1, 'papel_id' => 7, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28" ],
            ['titulo' => "Agendamentos", 'nivel' => 2, 'papel_id' => 7, 'menu_pai_id' => 7, 'rota' => "/app/ordem-servicos/agendamentos", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],

            ['titulo' => "Financeiro", 'nivel' => 1, 'papel_id' => 7, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28" ],
            ['titulo' => "Títulos", 'nivel' => 2, 'papel_id' => 7, 'menu_pai_id' => 9, 'rota' => "/app/financeiro/titulos", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],
            ['titulo' => "Pagamento Funcionário", 'nivel' => 2, 'papel_id' => 7, 'menu_pai_id' => 9, 'rota' => "/app/financeiro/pagamento-funcionario", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],
            ['titulo' => "Contas à Pagar", 'nivel' => 2, 'papel_id' => 7, 'menu_pai_id' => 9, 'rota' => "/app/financeiro/contas-pagar", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],
            ['titulo' => "Contas à Receber", 'nivel' => 2, 'papel_id' => 7, 'menu_pai_id' => 9, 'rota' => "/app/financeiro/contas-receber", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],

            ['titulo' => "Relatórios", 'nivel' => 1, 'papel_id' => 7, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28" ],
            ['titulo' => "Serviços Prestados", 'nivel' => 2, 'papel_id' => 7, 'menu_pai_id' => 14, 'rota' => "/app/relatorios/servicos-prestados", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],

            ['titulo' => "Área do Cliente", 'nivel' => 1, 'papel_id' => 8, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28" ],
            ['titulo' => "Agendamentos", 'nivel' => 2, 'papel_id' => 8, 'menu_pai_id' => 16, 'rota' => "/app/cliente/agendamentos", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28" ],
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
