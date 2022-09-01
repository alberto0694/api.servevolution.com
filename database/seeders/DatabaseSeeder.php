<?php

namespace Database\Seeders;

use App\Models\TipoCusto;
use App\Models\UnidadeMedida;
use App\Models\TipoServico;
use App\Models\ValoresServicos;
use App\Models\Municipio;
use App\Models\Endereco;
use App\Models\Estado;
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
use Carbon\Carbon;
use Faker;
use Illuminate\Support\Facades\DB;
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
        $this->permissaoSeed();
        $this->bootstrapData();
        //$this->fakeDataSeed();
        
    }

    function removeAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }

    private function csvToArray($csvFile)
    {

        $file_to_read = fopen($csvFile, 'r');

        while (!feof($file_to_read)) {
            $lines[] = fgetcsv($file_to_read, 1000, ',');
        }

        fclose($file_to_read);
        return $lines;
    }

    private function seedUsers($nome, $email, $sexo)
    {
        $pessoa = Pessoa::create([
            'nome' => $nome,
            'apelido' => $nome,
            'contatoImediato' => $email,
            'email' => $email,
        ]);

        Colaborador::create([
            'pessoa_id' => $pessoa->id,
            'sexo' => $sexo
        ]);

        $usuario = Usuario::create([
            'name' => $nome,
            'email' => $email,
            'password' => bcrypt($email),
            'pessoa_id' => $pessoa->id
        ]);

        PapelUsuario::insert([
            ['papel_id' => 14, 'usuario_id' => $usuario->id],
            ['papel_id' => 15, 'usuario_id' => $usuario->id],
            ['papel_id' => 16, 'usuario_id' => $usuario->id],
            // ['papel_id' => 4, 'usuario_id' => $usuario->id],
            // ['papel_id' => 5, 'usuario_id' => $usuario->id],
            // ['papel_id' => 6, 'usuario_id' => $usuario->id],
            // ['papel_id' => 7, 'usuario_id' => $usuario->id],
            // ['papel_id' => 8, 'usuario_id' => 31 ], //cliente
        ]);        
    }

    private function bootstrapData()
    {

        try {

            $pessoa = Pessoa::create([
                'nome' => 'Usuario',
                'apelido' => 'Usuario',
                'contatoImediato' => '-',
                'email' => 'admin@servevolution.com.br',
            ]);

            Usuario::create([
                'name' => 'Usuario',
                'email' => 'admin@servevolution.com.br',
                'password' => bcrypt('123456'),
                'pessoa_id' => $pessoa->id
            ]);

            PapelUsuario::insert([
                ['papel_id' => 1, 'usuario_id' => 1],
                ['papel_id' => 2, 'usuario_id' => 1],
                ['papel_id' => 3, 'usuario_id' => 1],
                ['papel_id' => 4, 'usuario_id' => 1],
                ['papel_id' => 5, 'usuario_id' => 1],
                ['papel_id' => 6, 'usuario_id' => 1],
                ['papel_id' => 7, 'usuario_id' => 1],
                ['papel_id' => 8, 'usuario_id' => 1],
                ['papel_id' => 9, 'usuario_id' => 1],
                ['papel_id' => 10, 'usuario_id' => 1],
                ['papel_id' => 11, 'usuario_id' => 1],
                ['papel_id' => 12, 'usuario_id' => 1],
                ['papel_id' => 13, 'usuario_id' => 1],
                ['papel_id' => 14, 'usuario_id' => 1],
                ['papel_id' => 15, 'usuario_id' => 1],
                ['papel_id' => 16, 'usuario_id' => 1],
                ['papel_id' => 17, 'usuario_id' => 1],
                ['papel_id' => 18, 'usuario_id' => 1],
                ['papel_id' => 19, 'usuario_id' => 1],
                ['papel_id' => 20, 'usuario_id' => 1],
                ['papel_id' => 21, 'usuario_id' => 1],
                ['papel_id' => 22, 'usuario_id' => 1],
                ['papel_id' => 23, 'usuario_id' => 1],
                ['papel_id' => 24, 'usuario_id' => 1],
                ['papel_id' => 25, 'usuario_id' => 1],
                ['papel_id' => 26, 'usuario_id' => 1],
                ['papel_id' => 27, 'usuario_id' => 1],
                ['papel_id' => 28, 'usuario_id' => 1],
                ['papel_id' => 29, 'usuario_id' => 1],
                ['papel_id' => 30, 'usuario_id' => 1],
                ['papel_id' => 31, 'usuario_id' => 1],
                ['papel_id' => 32, 'usuario_id' => 1],
                // ['papel_id' => 8, 'usuario_id' => 31 ], //cliente
            ]);               

            $string = file_get_contents(public_path('data/cidades-estados.json'));
            $cidades_estados = json_decode($string);

            foreach ($cidades_estados->states as $key => $value) {

                Estado::create([
                    'codigo_ibge' => $key,
                    'nome' => $value
                ]);
            }

            foreach ($cidades_estados->cities as $cidade) {

                $estado = Estado::where('codigo_ibge', $cidade->state_id)->first();
                Municipio::create([
                    'codigo_ibge' => $cidade->id,
                    'estado_id' => $estado->id,
                    'nome' => $cidade->name ?? $cidade->nome
                ]);
            }

            $csvFile = public_path('data/clientes.csv');
            $csv = $this->csvToArray($csvFile);

            foreach ($csv as $item) {

                $pessoa = Pessoa::create([
                    'nome' => $item[1],
                    'apelido' => $item[2],
                    'contatoImediato' => '-'
                ]);

                $nome = $this->removeAcentos($item[4]);
                $municipios = DB::select("select * from municipio where unaccent(nome) ilike '%$nome%'");

                if(count($municipios) > 0) {

                    $endereco = Endereco::create([
                        'logradouro' => $item[5],
                        'municipio_id' => $municipios[0]->id,
                        'estado_id' => $municipios[0]->estado_id
                    ]);

                    $pessoa->enderecos()->save($endereco);
                }
                
                Cliente::create([
                    'id' => str_replace('.', '', $item[0]),
                    'cpf_cnpj' => $item[3],
                    'pessoa_id' => $pessoa->id
                ]);
            }

            $csvFile = public_path('data/funcionarios.csv');
            $csv = $this->csvToArray($csvFile);

            foreach ($csv as $item) {

                $pessoa = Pessoa::create([
                    'nome' => $item[1],
                    'apelido' => $item[2],
                    'contatoImediato' => '-'
                ]);

                // if(count($municipios) > 0) {

                    $endereco = Endereco::create([
                        'logradouro' => $item[4],
                        'cep' => $item[10],
                        'bairro' => $item[7],
                        'municipio_id' => 4439,
                        'estado_id' => 22,
                        'numero' => $item[6]
                    ]);

                    $pessoa->enderecos()->save($endereco);
                // }
                
                $dtAdmissaoArr = explode(".", $item[19]);
                Funcionario::create([
                    'referencia_id' => str_replace('.', '', $item[1]),
                    'cpf' => $item[15],
                    'pessoa_id' => $pessoa->id,
                    'rg' => $item[16],
                    'cpf' => $item[15],
                    'sexo' => strtolower($item[14]),
                    'orgao_emissor' => $item[17],
                    'uf_emissor' => $item[18],
                    'data_admissao' => Carbon::create($dtAdmissaoArr[2], $dtAdmissaoArr[1], $dtAdmissaoArr[0])
                ]);
            }

            
            $this->seedUsers('Guilherme Lourenço Borges', 'operacional2@servevolution.com.br', 'masculino');
            $this->seedUsers('Kassiane Rosa da Conceicao', 'operacional2@gmail.com', 'feminino');
            $this->seedUsers('Jefferson Batista Schneider', 'operacional01@gmail.com', 'masculino');
            $this->seedUsers('Ana paula Amaro', 'operacional04@servevolution.com', 'feminino');

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function preDataseed()
    {

        TipoServico::insert([
            ['descricao' => 'Limpeza'],
            ['descricao' => 'Carregamento'],
            ['descricao' => 'Serviços Gerais'],
            ['descricao' => 'Lavação'],
            ['descricao' => 'Cuidadora']
        ]);

        TipoCusto::insert([
            ['descricao' => 'Marmita'],
            ['descricao' => 'Uber'],
            ['descricao' => 'Extras']
        ]);

        UnidadeMedida::insert([
            ['descricao' => 'Hora'],
            ['descricao' => 'Diária'],
            ['descricao' => 'Noturno']
        ]);
    }

    private function fakeDataSeed()
    {

        $faker = \Faker\Factory::create();
        $tp_servicos = collect(TipoServico::all())->toArray();
        $un_medidas = collect(UnidadeMedida::all())->toArray();

        $colaboradores = Pessoa::factory()
            ->count(30)
            ->create()
            ->each(function ($pessoa) use ($faker) {
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
            ->each(function ($pessoa) use ($faker, $tp_servicos, $un_medidas) {

                $pessoa->clientes()->save(new Cliente(['cpf_cnpj' => '05544749990']));
                $pessoa->usuarios()->save(new Usuario([
                    'name' => $pessoa->razao ?? $pessoa->apelido,
                    'email' => $faker->email(),
                    'password' => bcrypt('123456')
                ]));

                for ($i = 0; $i < 5; $i++) {

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
            ->each(function ($pessoa) use ($faker) {
                $pessoa->funcionarios()->save(new Funcionario([
                    'sexo' => $faker->randomElement(['masculino', 'feminino'])
                ]));
            });


        $ordens = OrdemServico::factory()
            ->count(60)
            ->create()
            ->each(function ($ordem) use ($funcionarios) {

                OrdemServicoStatus::create([
                    'ordem_servico_id' => $ordem->id,
                    'descricao' => 'agendado'
                ]);

                $keys = array_rand($funcionarios->toArray(), random_int(2, 5));
                collect(Funcionario::whereIn('id', $keys)->get())
                    ->each(fn ($f) => $ordem->funcionarios()->save($f));
            });

        collect(OrdemServicoFuncionario::all())->each(function ($osf) {
            $osf->custos()->save(OrdemServicoCusto::create([
                'tipo_custo_id' => random_int(1, 3),
                'valor' => random_int(10, 20)
            ]));
        });
    }

    private function permissaoSeed()
    {

        Permissao::insert([
            /* 1 */ ['programa' => "PROG_CADASTRO", 'titulo' => "Programa de Cadastros", 'descricao' => "Cadastros"],
            /* 2 */ ['programa' => "PROG_ORDEM_SERVICO", 'titulo' => "Programa de Serviços", 'descricao' => "Manipulação dos serviços prestados."],
            /* 3 */ ['programa' => "PROG_FINANCEIRO", 'titulo' => "Programa de Finanças", 'descricao' => "Manipulação dos serviços prestados."],            
            /* 4 */ ['programa' => "PROG_CLIENTE", 'titulo' => "Programa Operacional/Transporte", 'descricao' => "Operacional/Transporte"],
            /* 5 */ ['programa' => "PROG_RELATORIOS", 'titulo' => "Programa de Relatórios", 'descricao' => "Manipulação dos serviços prestados."],
            /* 6 */ ['programa' => "PROG_CONFIGURACAO", 'titulo' => "Programa Configuração", 'descricao' => "Configurações"],
        ]);

        Papel::insert([
            
            /* 1 */ ['permissao_id' => 1, 'acao' => "MODULO_CADASTRO", 'descricao' => "Acesso a tela de listagem de funcionários"],
            
            /* 2 */ ['permissao_id' => 1, 'acao' => "LISTAR_FUNCIONARIOS", 'descricao' => "Acesso a tela de listagem de funcionários"],
            /* 3 */ ['permissao_id' => 1, 'acao' => "INSERIR_FUNCIONARIO", 'descricao' => "Insere uma nova pessoa ao sistema"],
            
            /* 4 */ ['permissao_id' => 1, 'acao' => "LISTAR_COLABORADORES", 'descricao' => "Visualização de dados específicos de uma pessoa do sistema."],
            /* 5 */ ['permissao_id' => 1, 'acao' => "INSERIR_COLABORADOR", 'descricao' => "Insere uma nova pessoa ao sistema"],

            /* 6 */ ['permissao_id' => 1, 'acao' => "LISTAR_TIPO_SERVICOS", 'descricao' => "Acesso a tela de listagem de funcionários"],
            /* 7 */ ['permissao_id' => 1, 'acao' => "INSERIR_TIPO_SERVICO", 'descricao' => "Insere uma nova pessoa ao sistema"],
            
            /* 8 */ ['permissao_id' => 1, 'acao' => "LISTAR_TIPO_CUSTOS", 'descricao' => "Visualização de dados específicos de uma pessoa do sistema."],
            /* 9 */ ['permissao_id' => 1, 'acao' => "INSERIR_TIPO_CUSTOS", 'descricao' => "Insere uma nova pessoa ao sistema"],

            /* 10 */ ['permissao_id' => 1, 'acao' => "LISTAR_CLIENTES", 'descricao' => "Visualização de dados específicos de uma pessoa do sistema."],
            /* 11 */ ['permissao_id' => 1, 'acao' => "INSERIR_CLIENTES", 'descricao' => "Insere uma nova pessoa ao sistema"],

            /* 12 */ ['permissao_id' => 1, 'acao' => "LISTAR_UNIDADE_MEDIDA", 'descricao' => "Inativa uma pessoa existente no sistema."],
            /* 13 */ ['permissao_id' => 1, 'acao' => "INSERIR_UNIDADE_MEDIDA", 'descricao' => "Relatórios de gestao de pessoas."],

            /* 14 */ ['permissao_id' => 2, 'acao' => "MODULO_ORDEM_SERVICO", 'descricao' => "Acesso a tela de listagem de funcionários"],
            
            /* 15 */ ['permissao_id' => 2, 'acao' => "LISTAR_ORDEM_SERVICO", 'descricao' => "Inativa uma pessoa existente no sistema."],
            /* 16 */ ['permissao_id' => 2, 'acao' => "INSERIR_ORDEM_SERVICO", 'descricao' => "Inativa uma pessoa existente no sistema."],

            /* 17 */ ['permissao_id' => 3, 'acao' => "MODULO_FINANCEIRO", 'descricao' => "Acesso a tela de listagem de funcionários"],
            
            /* 18 */ ['permissao_id' => 3, 'acao' => "LISTAR_COBRANCA_OS", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],
            /* 19 */ ['permissao_id' => 3, 'acao' => "GERAR_TITULO_COBRANCA", 'descricao' => "Possibilita a criação de um título financeiro."],
            /* 20 */ ['permissao_id' => 3, 'acao' => "EXCLUIR_TITULO_COBRANCA", 'descricao' => "Inativa uma pessoa existente no sistema."],

            /* 21 */ ['permissao_id' => 3, 'acao' => "LISTAR_CONTAS_RECEBER", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],
            /* 22 */ ['permissao_id' => 3, 'acao' => "BAIXAR_PARCELA_CONTAS_RECEBER", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],

            /* 23 */ ['permissao_id' => 3, 'acao' => "LISTAR_PAGAMENTO_FUNCIONARIO", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],
            /* 24 */ ['permissao_id' => 3, 'acao' => "GERAR_TITULO_PAGAMENTO_FUNCIONARIO", 'descricao' => "Possibilita a criação de um título financeiro."],
            /* 25 */ ['permissao_id' => 3, 'acao' => "EXCLUIR_TITULO_PAGAMENTO_FUNCIONARIO", 'descricao' => "Inativa uma pessoa existente no sistema."],

            /* 26 */ ['permissao_id' => 3, 'acao' => "LISTAR_CONTAS_PAGAR", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],
            /* 27 */ ['permissao_id' => 3, 'acao' => "BAIXAR_PARCELA_CONTAS_PAGAR", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],            

            /* 28 */ ['permissao_id' => 3, 'acao' => "LISTAR_CONTAS_PAGAR", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],
            /* 29 */ ['permissao_id' => 3, 'acao' => "BAIXAR_PARCELA_CONTAS_PAGAR", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],            


            /* 30 */ ['permissao_id' => 4, 'acao' => "MODULO_ACESSO_CLIENTE", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],
            /* 31 */ ['permissao_id' => 4, 'acao' => "LISTAR_AGENDAMENTOS_CLIENTE", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],            

            
            /* 32 */ ['permissao_id' => 5, 'acao' => "MODULO_RELATORIOS", 'descricao' => "Dá ao usuário a visibilidade das ordens de serviços e títulos de cobrança gerados."],

        ]);

        Menu::insert([

            ['titulo' => "Cadastros", 'nivel' => 1, 'papel_id' => 1, 'menu_pai_id' => null, 'rota' => null, 'icone' => "pessoa-edit-white28x28", 'icone_aux' => "pessoa-edit28x28"],
            ['titulo' => "Funcionários", 'nivel' => 2, 'papel_id' => 2, 'menu_pai_id' => 1, 'rota' => "/app/funcionarios", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28"],
            ['titulo' => "Colaboradores", 'nivel' => 2, 'papel_id' => 4, 'menu_pai_id' => 1, 'rota' => "/app/colaboradores", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28"],
            ['titulo' => "Tipos de Serviços", 'nivel' => 2, 'papel_id' => 6, 'menu_pai_id' => 1, 'rota' => "/app/tipo-servicos", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28"],
            ['titulo' => "Tipos de Custos", 'nivel' => 2, 'papel_id' => 8, 'menu_pai_id' => 1, 'rota' => "/app/tipo-custos", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28"],
            ['titulo' => "Clientes", 'nivel' => 2, 'papel_id' => 10, 'menu_pai_id' => 1, 'rota' => "/app/clientes", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28"],
            ['titulo' => "Unidades de Medida", 'nivel' => 2, 'papel_id' => 12, 'menu_pai_id' => 1, 'rota' => "/app/unidade-medida", 'icone' => "pessoa-white28x28", 'icone_aux' => "pessoa28x28"],

            ['titulo' => "Ordem de Serviço", 'nivel' => 1, 'papel_id' => 14, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28"],
            ['titulo' => "Agendamentos", 'nivel' => 2, 'papel_id' => 15, 'menu_pai_id' => 8, 'rota' => "/app/ordem-servicos/agendamentos", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28"],

            ['titulo' => "Financeiro", 'nivel' => 1, 'papel_id' => 17, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28"],
            ['titulo' => "Cobrança OS", 'nivel' => 2, 'papel_id' => 18, 'menu_pai_id' => 10, 'rota' => "/app/financeiro/titulos", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28"],
            ['titulo' => "Pagamento Funcionário", 'nivel' => 2, 'papel_id' => 23, 'menu_pai_id' => 10, 'rota' => "/app/financeiro/pagamento-funcionario", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28"],
            ['titulo' => "Contas à Pagar", 'nivel' => 2, 'papel_id' => 26, 'menu_pai_id' => 10, 'rota' => "/app/financeiro/contas-pagar", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28"],
            ['titulo' => "Contas à Receber", 'nivel' => 2, 'papel_id' => 21, 'menu_pai_id' => 10, 'rota' => "/app/financeiro/contas-receber", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28"],

            ['titulo' => "Área do Cliente", 'nivel' => 1, 'papel_id' => 30, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28"],
            ['titulo' => "Agendamentos", 'nivel' => 2, 'papel_id' => 31, 'menu_pai_id' => 17, 'rota' => "/app/cliente/agendamentos", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28"],

            ['titulo' => "Relatórios", 'nivel' => 1, 'papel_id' => 32, 'menu_pai_id' => null, 'rota' => null, 'icone' => "endereco-white28x28", 'icone_aux' => "endereco28x28"],
            ['titulo' => "Serviços Prestados", 'nivel' => 2, 'papel_id' => 32, 'menu_pai_id' => 15, 'rota' => "/app/relatorios/servicos-prestados", 'icone' => "regime-empresa-white28x28", 'icone_aux' => "regime-empresa28x28"],

        ]);

    }
}


