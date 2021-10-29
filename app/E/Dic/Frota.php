<?php

namespace App\E\Dic;

use App\Models\Frota\Rastreadores;
use Illuminate\Validation\Rule;

abstract class Frota
{

    /**
     * Veículos
     */
    public static function veiculos($request = null)
    {
        return [
            [
                'label' => 'Código',
                'campo' => 'id',
                'desc' => 'Código do Cliente',
                'bclass' => 'col-md-1',
                'html' => 'texto',
                'html_attribs' => 'readonly',
                'filtros' => 'number_int'
            ],
            [
                'label' => 'Nº Frota',
                'campo' => 'numero_frota',
                'desc' => 'Identificação do veiculo na frota ',
                'bclass' => 'col-md-2',
                'html' => 'texto',
                'acoes' => [
                    'salvar' => 'required'
                ],
                'filtros' => 'trim|strtoupper',
            ],


            [
                'label' => 'Placa/Série',
                'campo' => 'placa',
                'desc' => 'Placa do Veículo ou Número de série',
                'bclass' => 'col-md-2',
                'html' => 'texto',
                'acoes' => [
                    'salvar' => ['required',
                        Rule::unique('frota_veiculos', 'placa')
                            ->ignore(@$request->id, 'id')]
                ],
                'filtros' => 'trim|strtoupper',
            ],

            [
                'label' => 'Tipo',
                'campo' => 'tipo_id',
                'desc' => 'Tipo de veículo',
                'bclass' => 'col-md-2',
                'html' => 'tipo-veiculos'
            ],

            [
                'label' => 'Marca',
                'campo' => 'marca_id',
                'desc' => 'Marca',
                'bclass' => 'col-md-2',
                'html' => 'marcas'
            ],

            [
                'label' => 'Modelo',
                'campo' => 'modelo_id',
                'desc' => 'Modelo',
                'bclass' => 'col-md-3',
                'html' => 'modelos',
            ],


            [
                'label' => 'Ano Modelo',
                'campo' => 'ano_modelo',
                'desc' => 'Ano Modelo',
                'bclass' => 'col-md-2',
                'html' => 'texto'
            ],


            [
                'label' => 'Renavan',
                'campo' => 'renavan',
                'desc' => 'Renavan',
                'bclass' => 'col-md-5',
                'html' => 'texto'
            ],
            [
                'label' => 'Combustivel',
                'campo' => 'combustivel',
                'desc' => 'Combustivel',
                'bclass' => 'col-md-2',
                'html' => 'texto'
            ],

            [
                'label' => 'Cor',
                'campo' => 'cor',
                'desc' => 'Cor',
                'bclass' => 'col-md-2',
                'html' => 'texto'
            ],

            [
                'label' => 'Rastreador',
                'campo' => 'rastreador_esn',
                'desc' => 'Rastreador',
                'bclass' => 'col-md-4',
                'html' => 'select-rastreador'
            ],


            [
                'label' => 'Status',
                'campo' => 'status',
                'desc' => 'Status do Veículo',
                'bclass' => 'col-md-3',
                'html' => 'select',
                'dominio' => ['ATIVO', 'INATIVO'],
                'acoes' => [
                    'salvar' => 'required|in:ATIVO,INATIVO'
                ]
            ],


            [
                'label' => 'Outras Informações',
                'campo' => 'info',
                'desc' => 'Outras Informações',
                'bclass' => 'col-md-12',
                'html' => 'textarea'
            ],

        ];

    }

    public static function veiculosGrid()
    {
        return [
            [
                'label' => 'Código',
                'campo' => 'id'
            ],

            [
                'label' => 'Cliente',
                'campo' => 'clientes',
            ],

            [
                'label' => 'Nº Frota',
                'campo' => 'numero_frota',
            ],
            [
                'label' => 'Placa/Série',
                'campo' => 'placa',
            ],

            [
                'label' => 'Tipo',
                'campo' => 'tipo.descricao',
            ],
            [
                'label' => 'Marca',
                'campo' => 'marca.nome',
            ],
            [
                'label' => 'Modelo',
                'campo' => 'modelo.nome',
            ],

            [
                'label' => 'Ano Modelo',
                'campo' => 'ano_modelo',
            ],
            [
                'label' => 'Rastreador',
                'campo' => 'rastreador_esn',
            ],

            [
                'label' => 'Status',
                'campo' => 'status',
            ]

        ];
    }


    /**
     * Rastreadores
     */
    public static function rastreadores($request = null)
    {
        return [
            [
                'label' => 'Código',
                'campo' => 'id',
                'desc' => 'Código do Rastreador',
                'bclass' => 'col-md-1',
                'html' => 'texto',
                'html_attribs' => 'readonly',
                'filtros' => 'digitos'
            ],

            [
                'label' => 'Modelo',
                'campo' => 'modelo',
                'desc' => 'Modelo de Rastreador',
                'bclass' => 'col-md-3',
                'html' => 'select',
                'dominio' => ['M1 MINI', 'F1 MINI', 'SUNTECH ST310U', 'CONCOX - CRX1', 'CONTINENTAL - QB LIGTH', 'ACCURATE H06', 'TK200', 'MAX TRACK MT1XX', 'EASY TRACK E3'],
                'acoes' => [
                    'salvar' => 'required'
                ],
            ],

            [
                'label' => 'ID EQUIPAMENTO',
                'campo' => 'esn',
                'desc' => 'ID EQUIPAMENTO',
                'bclass' => 'col-md-3',
                'html' => 'texto',
                'html_attribs' => 'v-mask="###############"',
                'filtros' => 'digitos',
                'acoes' => [
                    'salvar' => ['required',
                        Rule::unique('frota_rastreadores', 'esn')
                            ->ignore(@$request->id, 'id')]
                ],
            ],

            [
                'label' => 'IMEI',
                'campo' => 'imei',
                'desc' => 'Código IMEI',
                'bclass' => 'col-md-3',
                'html' => 'texto',
                'html_attribs' => 'v-mask="######-##-######-#"',
                'filtros' => 'digitos'
            ],


            [
                'label' => 'Nº CHIP',
                'campo' => 'fone_chip_gsm',
                'desc' => 'Número fone do chip',
                'bclass' => 'col-md-2',
                'html' => 'input-fone',
                'filtros' => 'digitos',
                'acao' => [
                    'salvar' => [Rule::unique('frota_rastreadores', 'fone_chip_gsm')
                        ->ignore(@$request->id, 'id')]
                ]
            ],

            [
                'label' => 'ICCID',
                'campo' => 'iccid',
                'desc' => 'ICCID',
                'bclass' => 'col-md-3',
                'html' => 'texto',
                'html_attribs' => 'v-mask="#####################"',
                'filtros' => 'digitos',
                'acao' => [
                    'salvar' => [Rule::unique('frota_rastreadores', 'iccid')
                        ->ignore(@$request->id, 'id')]
                ]
            ],

            [
                'label' => 'Operadora Gsm',
                'campo' => 'operadora_gsm',
                'desc' => 'Operadora Gsm',
                'bclass' => 'col-md-2',
                'html' => 'select',
                'dominio' => ['CLARO', 'VIVO', 'OI', 'TIM', 'NEXTEL', 'ALGAR'],
                'acoes' => [
                    'salvar' => 'in:CLARO,VIVO,OI,TIM,NEXTEL,ALGAR'
                ],
                'filtros' => 'strtoupper',
            ],


            [
                'label' => 'Estoque',
                'campo' => 'estoque',
                'desc' => 'Estoque',
                'bclass' => 'col-md-2',
                'html' => 'texto',
                'html_attribs' => 'v-mask="#########"',
                'filtros' => 'digitos',
            ],

            [
                'label' => 'Status',
                'campo' => 'status',
                'desc' => 'Status do Rastreador',
                'bclass' => 'col-md-3',
                'html' => 'select',
                'dominio' => ['ATIVO', 'BLOQUEADO', 'INATIVO'],
                'acoes' => [
                    'salvar' => 'required|in:ATIVO,BLOQUEADO,INATIVO'
                ],
                'filtros' => 'strtoupper',
            ],


        ];
    }


    public static function rastreadoresGrid()
    {
        return [
            [
                'label' => 'Código',
                'campo' => 'id'
            ],
            [
                'label' => 'Esn',
                'campo' => 'esn'
            ],

            [
                'label' => 'Equipamento',
                'campo' => 'modelo',
            ],
            [
                'label' => 'Veículo',
                'campo' => 'veiculo.placa',
            ],

            [
                'label' => 'Operadora',
                'campo' => 'operadora_gsm',
            ],

            [
                'label' => 'Número Chip',
                'campo' => 'fone_chip_gsm',
            ],

            [
                'label' => 'Status',
                'campo' => 'status',
            ]

        ];
    }


}
