<?php

namespace App\E;


class Mensagens
{

    const OK_DB_DELETE = 'Registro excluído com sucesso.';
    const OK_DB_MASS_INSERT = 'Inclusão de  registro em massa concluído.';

    const ERRO_DB_DELETE = 'Exclusão não permitida! Há registros dependentes.';
    const ERRO_DB_FIND = 'Registro não encontado.';
    const ERRO_DB_NOT_UPDATE = 'Nada de novo para salvar.';
    const ERRO_DB_DUPLICADO = 'Registro duplicado. Inclusão/alteração não permitida.';

    const ERRO_SEM_DADOS_PERIODO = 'Sem dados de telemetria no periodo informado';
    const ERRO_EQP_NAO_RASTREADO = 'Equipamento não rastreado';
    const ERRO_EQP_NAO_ENCONTRADO = 'Equipamento não encontrado';


    /**
     * Modulo frotas
     */

    const FROTA_ERRO_VEICULO_NAO_ENCONTRADO = 'Veículo não encontrado';
    const FROTA_ERRO_SEM_DADOS_GERAR_RELATORIO = 'Não ha dados disponíveis para gerar relatório';

}
