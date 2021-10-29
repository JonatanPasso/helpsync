<?php

namespace App\E\Dic;

use App\Models\Geral\Clientes;
use Illuminate\Validation\Rule;

abstract class Geral
{

    /**
     * Clientes
     */
    public static function clientes(Clientes $cli = null)
    {
        return [
            [
                'label' => 'Código',
                'campo' => 'id',
                'desc' => 'Código do Cliente',
                'bclass' => 'col-md-2',
                'html' => 'texto',
                'filtros' => 'number_int',
                'html_attribs' => 'readonly',
            ],
            [
                'label' => 'Nome',
                'campo' => 'nome',
                'desc' => 'NOME DO CLIENTE',
                'bclass' => 'col-md-5',
                'html' => 'texto',
                'acoes' => [
                    'salvar' => 'required'
                ],
                'filtros' => 'trim|strtoupper',
            ],
            [
                'label' => 'Pessoa',
                'campo' => 'tipo',
                'desc' => 'Tipo de Pessoa',
                'dominio' => ['FISICA', 'JURIDICA', 'NA'],
                'bclass' => 'col-md-2',
                'html' => 'select',
                'acoes' => [
                    'salvar' => 'required|in:FISICA,JURIDICA,NA'
                ]
            ],
//unique:table,column,except,idColumn
            [
                'label' => 'Cpf/Cnpj',
                'campo' => 'cpf_cnpj',
                'desc' => 'Cpf/Cnpj',
                'bclass' => 'col-md-3',
                'html' => 'cpf-cnpj',
                'acoes' => [
                    'salvar' => [
                        'required',
                        'cpfcnpj',
                        Rule::unique('geral_clientes', 'cpf_cnpj')->ignore($cli, 'id')
                    ]
                ]
            ],
            [
                'label' => 'Cep',
                'campo' => 'cep',
                'desc' => 'CEP',
                'bclass' => 'col-md-2',
                'html' => 'cep',

            ],
            [
                'label' => 'UF',
                'campo' => 'estado',
                'desc' => 'Estado',
                'bclass' => 'col-md-2',
                'html' => 'select-estados',
                'acoes' => [
                    'salvar' => 'required_with:cidade'
                ]
            ],
            [
                'label' => 'Cidade',
                'campo' => 'cidade',
                'desc' => 'Cidade',
                'bclass' => 'col-md-2',
                'html' => 'select-cidades'
            ],
            [
                'label' => 'Endereço',
                'campo' => 'endereco',
                'desc' => 'Endereço',
                'bclass' => 'col-md-4',
                'html' => 'texto'
            ],
            [
                'label' => 'Comp',
                'campo' => 'complemento',
                'desc' => 'Complemento',
                'bclass' => 'col-md-2',
                'html' => 'texto'
            ],
            [
                'label' => 'Dt Cadastro',
                'campo' => 'cadastrado_em',
                'desc' => 'Data Inclusão',
                'bclass' => 'col-md-2',
                'html' => 'input-data',
                'acoes' => [
                    'salvar' => 'required|dateFormat:d/m/Y'
                ]
            ],

            [
                'label' => 'Fone 1',
                'campo' => 'fone1',
                'desc' => 'Telefone opcao 1',
                'bclass' => 'col-md-2',
                'html' => 'input-fone'
            ],

            [
                'label' => 'Fone 2',
                'campo' => 'fone2',
                'desc' => 'Telefone opcao 2',
                'bclass' => 'col-md-2',
                'html' => 'input-fone'
            ],

            [
                'label' => 'Fone 3',
                'campo' => 'fone3',
                'desc' => 'Telefone opcao 3',
                'bclass' => 'col-md-2',
                'html' => 'input-fone'
            ],

            [
                'label' => 'Email',
                'campo' => 'email',
                'desc' => 'Endereço de Email',
                'bclass' => 'col-md-4',
                'html' => 'input-email',
                'acoes' => [
                    'salvar' => 'email'
                ]
            ]
        ];
    }

    public static function clientesGrid()
    {
        return [
            [
                'label' => 'Código',
                'campo' => 'id'
            ],

            [
                'label' => 'Nome',
                'campo' => 'nome',
            ],
            [
                'label' => 'Cpf/Cnpj',
                'campo' => 'cpf_cnpj | mask',
            ],

            [
                'label' => 'Estado',
                'campo' => 'estado',
            ],

            [
                'label' => 'Cidade',
                'campo' => 'cidade',
            ],

        ];
    }


    /**
     * Usuarios
     */
    public static function usuarios($request = null)
    {
        return [
            [
                'label' => 'Código',
                'campo' => 'id',
                'desc' => 'Código do Usuário',
                'bclass' => 'col-md-2',
                'html' => 'texto',
                'filtros' => 'number_int',
                'html_attribs' => 'readonly',
            ],

            [
                'label' => 'Nome',
                'campo' => 'nome',
                'desc' => 'Nome do usuário',
                'bclass' => 'col-md-5',
                'html' => 'texto',
                'acoes' => [
                    'salvar' => 'required'
                ],
                'filtros' => 'trim|strtoupper',
            ],

            [
                'label' => 'Login',
                'campo' => 'usuario',
                'desc' => 'Login de acesso',
                'bclass' => 'col-md-5',
                'html' => 'texto',
                'acoes' => [
                    'salvar' => [
                        'required',
                        'alpha_num',
                        Rule::unique('geral_usuarios', 'usuario')
                            ->ignore(@$request->id, 'id')]
                ],
                'filtros' => 'trim|strtolower',
            ],

            [
                'label' => 'Email',
                'campo' => 'email',
                'desc' => 'Endereço de Email',
                'bclass' => 'col-md-4',
                'html' => 'input-email',
                'acoes' => [
                    'salvar' => [
//                        'required',
                        'email',
                        Rule::unique('geral_usuarios', 'email')
                            ->ignore(@$request->id, 'id')]
                ],
                'filtros' => 'trim|strtolower',
            ],

            [
                'label' => 'CPF',
                'campo' => 'cpf',
                'desc' => 'Cpf do Usuário',
                'bclass' => 'col-md-3',
                'html' => 'input-cpf',
                'acoes' => [
                    'salvar' => [
                        'cpf',
                        Rule::unique('geral_usuarios', 'cpf')
                            ->ignore(@$request->id, 'id')]
                ],
                'filtros' => 'number_int',
            ],


            [
                'label' => 'Fone',
                'campo' => 'fone',
                'desc' => 'Celular',
                'bclass' => 'col-md-3',
                'html' => 'input-fone'
            ],

            [
                'label' => 'Senha',
                'campo' => 'senha',
                'desc' => 'Senha',
                'bclass' => 'col-md-3',
                'html' => 'input-senha',
                'acoes' => [
                    'salvar' => 'required_without:id|senha'
                ]
            ]
        ];
    }

    public static function usuariosGrid()
    {
        return [
            [
                'label' => 'Código',
                'campo' => 'id'
            ],

            [
                'label' => 'Nome',
                'campo' => 'nome',
            ],

            [
                'label' => 'Login',
                'campo' => 'usuario',
            ],

            [
                'label' => 'Email',
                'campo' => 'email',
            ],

            [
                'label' => 'Fone',
                'campo' => 'fone',
            ],

        ];
    }


}
