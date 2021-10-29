<?php


namespace App\E;

use App\Models\Comercial\CabecalhoNota;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class NFE

{

    static private $LOG_TAG = 'SYNC_NOTAS_FICAIS:';

    public static $unidade_federacao = [
        '11' => 'RO',
        '12' => 'AC',
        '13' => 'AM',
        '14' => 'RR',
        '15' => 'PA',
        '16' => 'AP',
        '17' => 'TO',
        '21' => 'MA',
        '22' => 'PI',
        '23' => 'CE',
        '24' => 'RN',
        '25' => 'PB',
        '26' => 'PE',
        '27' => 'AL',
        '28' => 'SE',
        '29' => 'BA',
        '31' => 'MG',
        '32' => 'ES',
        '33' => 'RJ',
        '35' => 'SP',
        '41' => 'PR',
        '42' => 'SC',
        '43' => 'RS',
        '50' => 'MS',
        '51' => 'MT',
        '52' => 'GO',
        '53' => 'DF'];

    /**
     * Evento nota fsical
     * Cancelamento 110110
     */
    const TP_EVENTO_CANCELAMENTO = '110111';


    /**
     * Monta array com lista todos xml econtrados recursivante no
     * repositorio do conexao nfe
     * @return array Caminhos dos arquivos xml econtrados
     */
    private static function procurarXmlNotas($xmlRepositorio)
    {


        $files = File::allFiles($xmlRepositorio);

        $xmlPaths = [$xmlRepositorio . '/52191237227550000158550010000153251019152770-procNfe.xml'];


        foreach ($files as $f) {

            if (is_object($f))
                $xmlPaths[] = $f->getRealPath();
            else dump($f);
        }

        return $xmlPaths;
    }

    /**
     * Faz parser do arquivo xml e extrai dados da nota
     * @param $xmlConent
     * @return bool|array
     */
    public static function parserXml($xmlConent)
    {


        $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'xmlns="http://www.portalfiscal.inf.br/nfe"'], '', $xmlConent);

        $auxXml = simplexml_load_string($clean_xml);


        if ($auxXml) {

            $xml = json_decode(json_encode($auxXml), true);
            // $xml = json_decode(json_encode(simplexml_load_string($clean_xml)), true);

            //nota fiscal
            $data = (array_get((array)$xml, 'NFe.infNFe'));


            if ($data) {

                return $data;

            }

            //eventos
            $data = (array_get((array)$xml, 'evento.infEvento'));
            if ($data) {
                return $data;
            }
        }

        return false;
    }

    /**
     * Registra nota processada na tabela repositorio de notas processadas
     * @param array $data Dados da nota
     * @param $xmlContent xml da nota
     * @param $caminho Caminho do arquivo
     */
    public static function inserirRegistroNota(array $data, $xmlContent, $caminho)
    {
        $aux = $data;
        unset($aux['det']);
        unset($aux['@attributes']);

        $id_nota = str_replace('NFe', '', $data['@attributes']['Id']);
        $versao = ($data['@attributes']['versao']);

        if (!$id_nota) return;

        $campoValor = Arr::dot($aux);

//        Schema::dropIfExists('comercial_cabecalho_nota');
//
//        Schema::create('comercial_cabecalho_nota', function (Blueprint $table) use ($campoValor) {
//            $table->bigIncrements('id');
//            $table->text('id_nota');
//            $table->text('versao');
//
//            foreach ($campoValor as $key => $null) {
//                $key = strtolower(str_replace('.', '_', trim($key)));
//                $table
//                    ->text($key)
//                    ->nullable();
//            }
//        });
//        exit;

        $cabecalho = CabecalhoNota::query()
            ->where('id_nota', $id_nota)
            ->firstOrNew([]);

        $campos = array_keys($cabecalho->toArray());

        $cabecalho->id_nota = $id_nota;
        $cabecalho->versao = $versao;

        $temp = [];
        foreach ($campoValor as $key => $value) {
            $campo = strtolower(str_replace('.', '_', trim($key)));
//            $temp[$campo] = $value;

            if ($value !== null) {
                $cabecalho->$campo = is_array($value) ? json_encode($value) : $value;

            }

            if (!in_array($campo, $campos)) {
                $campos[] = $campo;

                try {
                    Schema::table('comercial_cabecalho_nota', function (Blueprint $table) use ($campo) {
//                    $table->bigIncrements('id');
                        $table->text($campo)->nullable(true);
                    });
                } catch (\Exception $e) {
                }
            }
        }

        try {

            $cabecalho->save();

        } catch (\Exception $e) {
            dump($e->getMessage());
        }


    }

    /**
     * Verifica se nota ja existe no repositorio
     * @param $chaveNota
     * @return mixed
     */
    public static function ignorarNotaJaRegistrada($chaveNota)
    {
        return self::getRegistroNotasTable()->whereChave($chaveNota)
            ->count();
    }

    /**
     * Verifica se arquivo ja foi processado
     * @param $urlArquivoNota
     * @return mixed
     */
    private static function ignorarArquivoJaProcessado($urlArquivoNota)
    {
        return self::getRegistroNotasTable()->whereCaminhoArquivo($urlArquivoNota)
            ->count();
    }

    /**
     * Localiza e processa notas e armazona no repositorio notas do everyday
     */
    public static function sincronizarXml($xmlRepositorio)
    {
        \Log::info(self::$LOG_TAG . '  Localizando arquivos xmls');

        $camimhoNotas = self::procurarXmlNotas($xmlRepositorio);

        \Log::info((self::$LOG_TAG) . ' ' . count($camimhoNotas) . ' arquivos encontrados');


        foreach ($camimhoNotas as $count => $urlXmlFile) {

            $xmlData = null;

            \Log::info((self::$LOG_TAG) . ' -> ' . $count);
            try {

                //    \Log::info("Carregando {$urlXmlFile}");

                $xmlContent = file_get_contents($urlXmlFile);

                if ($xmlContent) {

                    $xmlData = self::parserXml($xmlContent);

                } else {
                    \Log::error(self::$LOG_TAG . " Erro ao processar arquivo {$urlXmlFile} com msg: Arquivo vazio.");
                    continue;
                }

            } catch (\Exception $e) {
                \Log::error(self::$LOG_TAG . " Erro ao processar arquivo {$urlXmlFile} com msg: {$e->getMessage()}");
                continue;
            }


            if ($xmlData) {

                //evento nota fiscal
                if (isset($xmlData['tpEvento'])) {

                    //  @self::atualizarNota($xmlData);

                } else {

                    //nota fiscal

//                    if (!self::ignorarNotaJaRegistrada($xmlData['@attributes']['Id'])) {
                    self::inserirRegistroNota($xmlData, $xmlContent, $urlXmlFile);
//                    }

                }
            }

        }

        \Log::info(self::$LOG_TAG . '  Finalizado');

    }

    public static function atualizarNota($xmlData)
    {


        if (@$xmlData['tpEvento'] == '110111' && @$xmlData['detEvento']['descEvento'] == 'Cancelamento') {

            self::getRegistroNotasTable()
                ->whereChave("NFe{$xmlData['chNFe']}")
                ->update([
                    'cancelada' => 'SIM',
                    'cancelada_em' => $xmlData['dhEvento'],
                    'motivo_cancela' => $xmlData["detEvento"]['xJust']
                ]);
        }

    }

    /**
     * Verifica se a nota já está importada no protheus
     */
    public static function verificarNotasRegistroProtheus($grupoEmpresa)
    {

        \Log::info(self::$LOG_TAG . '  Iniciando rotina SETA NOTAS COMO IMPORTADAS');

        $database = Carbon::now()->subYear(5);

        $notasImportadas = self::getRegistroNotasTable()
            //   ->where('id',49004)
            ->where('importada_erp', '<>', '1')
            ->where('emitido_em', '>', $database)
            ->orderBy('id', 'desc')
            ->orderBy('cnpj_emissor')
            ->get(['id', 'chave', 'cnpj_emissor', 'data']);

        $total = count($notasImportadas);

        /**
         * Conexao protheus
         */
        $conexaoProtheus = \DB::connection('sqlsrv');


        foreach ($notasImportadas as $i => $not) {

            \Log::info(self::$LOG_TAG . ($i + 1) . " de {$total}");

            $cnpjCpfImissor = $not->cnpj_emissor;
            $chaveNota = substr($not->chave, 3);
            $dataXml = unserialize($not->data);
            $serie = $dataXml['ide']['serie'];
            $numeroNota = $dataXml['ide']['nNF'];

            try {

                if (isset(self::$CACHE_FORNECEDORES[$grupoEmpresa . $cnpjCpfImissor])) {
                    $fornecedor = self::$CACHE_FORNECEDORES[$grupoEmpresa . $cnpjCpfImissor];
                    # echo "from cache";
                } else {


                    $fornecedor = MapTabelas::getModel(MapTabelas::CADASTRO_DE_FORNECEDORES, $grupoEmpresa)
                        ->where('A2_CGC', $cnpjCpfImissor)->first();
                }


                self::$CACHE_FORNECEDORES[$grupoEmpresa . $cnpjCpfImissor] = $fornecedor;

                if ($fornecedor) {

                    $codigoFornecedor = $fornecedor->A2_COD;

                    //var_dump(MapTabelas::CABECALHO_DAS_NF_S_DE_ENTRADA. $grupoEmpresa);exit;

                    //busca nota pela chave
                    $notaRegistrada = MapTabelas::getModel(MapTabelas::CABECALHO_DAS_NF_S_DE_ENTRADA, $grupoEmpresa)
                        ->where('F1_CHVNFE', $chaveNota)->first();

                    //se nao achar tenta busca por fornecedor + numero + serie
                    if (!$notaRegistrada) {

                        $notaRegistrada = MapTabelas::getModel(MapTabelas::CABECALHO_DAS_NF_S_DE_ENTRADA, $grupoEmpresa)
                            ->where('F1_FORNECE', str_pad($codigoFornecedor, 6, '0', STR_PAD_LEFT))
                            ->where('F1_DOC', str_pad($numeroNota, 9, '0', STR_PAD_LEFT))
                            //  ->where('F1_SERIE', str_pad($serie, 3, '0', STR_PAD_LEFT))
                            ->first();

                    }

                    if ($notaRegistrada) {
                        self::getRegistroNotasTable()
                            ->whereId($not->id)
                            ->update(['importada_erp' => '1']);

                    }

                }

            } catch (\Exception $e) {
                \Log::error(self::$LOG_TAG . " Erro consultar protheus chave nota {$chaveNota} erro {$e->getMessage()}");
            }

        }


        \Log::info(self::$LOG_TAG . ' finaliado rotina SETA NOTAS COMO IMPORTADAS');

//

    }

}
