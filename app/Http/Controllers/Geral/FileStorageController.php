<?php

namespace App\Http\Controllers\Geral;


use App\Http\Controllers\Controller;

use App\Models\Geral\FileStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class FileStorageController extends Controller
{
    public function __construct()
    {
//        phpinfo();exit;
    }

    private function getTempToken()
    {

        $tempToken = Str::random(16);

        $expireTime = Carbon::now()->addMinutes(30);

        Cache::put($tempToken, $tempToken, $expireTime);

        return $tempToken;
    }

    private function checkFileAccess()
    {

        /**
         * @todo Melhor permissoes do gerenciador de arquivos
         *
         */
        return true;
//        $tempToken = Request::capture()->input('file-token');
//
//        if (!$tempToken) {
//            return false;
//        }
//        if (!Cache::has($tempToken)) {
//            return false;
//        }
//
//        return true;
    }

    public function getFile($uid)
    {
        $request = Request::capture();

        if (!$this->checkFileAccess()) {
            return response('Acesso negado', 403);
        }

        ob_clean();

        $file = FileStorage::whereUid($uid)->first();

        if ($file) {

            $headers = [
                'Access-Control-Allow-Origin' => '*',
                'Content-Type' => $file->metadata['mimeType'],
//                "Content-disposition" => "attachment; filename={$file->metadata['originalName']}",
            ];

            if ($request->input('download')) {
                $headers['Content-disposition'] = "attachment; filename={$file->metadata['originalName']}";
            }


            $path = $file->path;

            return new BinaryFileResponse($path, 200, $headers);
        }

        return response('Arquivo nao encontrado', 404);

    }

    public function getThumb($uid)
    {

        if (!$this->checkFileAccess()) {
            return response('Acesso negado', 403);
        }

        ob_clean();

        $file = FileStorage::whereUid($uid)->first();
        $im = new \Imagick();

        if ($file) {


            $pathCache = storage_path("thumb/{$file->uid}");
            $headers = ['Content-Type' => 'image/png'];

            if (file_exists($pathCache)) {
                return response(file_get_contents($pathCache), 200, $headers);
            }

            try {
                $im->readImage("{$file->path}[0]");

                $im->setImageColorspace(255);
                $im->setCompression(\Imagick::COMPRESSION_JPEG);
                $im->setCompressionQuality(60);
                $im->setImageFormat('png');
                //resize
                $im->resizeImage(100, 100, \Imagick::FILTER_LANCZOS, 1);

                file_put_contents($pathCache, $im);

                return response($im, 200, $headers);
            } catch (\Exception $e) {
                return response(
                    file_get_contents(resource_path('img/Icon-doc.svg'))
                )->header('content-type', 'image/svg+xml');
            }


        }

        abort(404);

    }


    /**
     * @api {POST} /beta/api/everyday/file-upload Envio de Arquivos
     * @apiVersion 0.1.0
     * @apiName FileUpload
     * @apiGroup Everyday
     * @apiParam Binary File form mult-part data
     *
     * @apiSuccess {String} created_at  Data Criação do Arquivo
     * @apiSuccess {String} metadata  Informações do arquivo
     * @apiSuccess {String} original_name  Nome original do arquivo
     * @apiSuccess {String} path  Caminho do arquivo no sistema de arquivos
     * @apiSuccess {String} inscr_estadual  Codigo Insc Estadual.
     * @apiSuccess {String} uid  Identificador unico do arquivo
     * @apiSuccess {String} url  URL do arquivo
     * @apiSuccess {String} status se "temporary"  Aquivo temporario (sera apagado automaticamente apos alguns minutos)
     *                          se "prodution" Arquivo permanente
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *       {"path":"/var/www/everyday/storage/uploads/5a8f47d013d507.91564427",
     *          "uid":"5a8f47d013d507.91564427",
     *          "created_at":{
     *          "date":"2018-02-22 19:44:32.000000",
     *          "timezone_type":3,
     *          "timezone":"America/Sao_Paulo"},
     *          "metadata":{
     *              "originalName":"pp.jpeg",
     *              "mimeType":"image/jpeg",
     *              "size":33290
     *           },
     *          "status":"temporary",
     *          "id":60,
     *          "url":"http://localhost:9091/beta/api/everyday/files/5a8f47d013d507.91564427",
     *          "original_name":"pp.jpeg"}
     *      ]
     *
     * @apiErrorExample {json} Error-Response
     *     HTTP/1.1 400 Bad Request
     *    {
     *      "error": { "message":"Tamanho do arquivo ultrapassou limite permitido."},
     *      "success":false
     *
     *     }
     *
     */
    public function fileTempUpload(Request $req)
    {

        //$maxFileUpload = (ini_get('max_file_uploads'));

        $warings = error_get_last();
        if ($warings !== null) {
            return response($this->getErro() ? $this->getErro() : 'Erro ao enviar arquivo', 400);
        }

        $files = $req->allFiles();

        $response = [];

        \DB::beginTransaction();

        if (@$files['files']) {

            foreach ($files['files'] as $f) {

                if ($f->isValid()) {

                    $uid = uniqid('', true);

                    // \Storage::makeDirectory('uploads');

                    $temp_path = storage_path('/app/uploads/'
                        . substr($uid, 0, 2) . '/'
                        . substr($uid, 2, 2));

//                    if (!file_exists($temp_path)) {
//                        mkdir($temp_path);
//                    }

                    $name = $f->getClientOriginalName();
                    $mime = $f->getMimeType();
                    $size = $f->getClientSize();

                    $fileMoved = $f->move($temp_path, $uid);

                    if ($fileMoved) {

                        $newFile = new FileStorage();

                        $newFile->path = $temp_path . '/' . $uid;
                        $newFile->uid = $uid;
                        $newFile->created_at = Carbon::now();
                        $newFile->created_by = $this->getUsuario()->id;
                        $newFile->metadata = [
                            'originalName' => $name,
                            'mimeType' => $mime,
                            'size' => $size];


                        $newFile->save();

                        $response[] = $newFile->toArray();

                        continue;

                    }

                } else {

                    return response($this->getErro() ? $this->getErro() : 'Erro ao enviar arquivo', 400);

                }

            }

        }

        \DB::commit();

        return $response;
    }

    private function getErro()
    {
        $aux = error_get_last();
        if (@$aux['message']) {
            return $aux['message'];
        }
        return false;
    }


    public function buscar(Request $req)
    {

        $query = FileStorage::query();

        if ($req->input('filtro.uid')) {
            $query->where('uid', $req->input('filtro.uid'));
        }

        $query->with('createdBy');

        $query->where('created_by', $this->getUsuario()->id);

        $pages = $query->paginate(20);

        $tempToken = $this->getTempToken();

        $pages->getCollection()->transform(function ($item) use ($tempToken) {

            $aux = $item->toArray();

            $aux['url'] = "{$item->url}?file-token={$tempToken}";
            $aux['thumb_url'] = "{$item->url}/thumb?file-token={$tempToken}";

            return $aux;
        });

        return $pages;
    }

}
