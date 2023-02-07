<?php

namespace App\Services;

use App\System;

class Poke
{
    /**
     * Dados de configuração da API
     *
     * @var array $config, default []
     */
    private $config = [];

    /**
     * Local para armazenamento de arquivo para cache
     *
     * @var string $localCache, default "/cache/poke/"
     */
    private $localCache = "/cache/poke/";

    /**
     * Tempo de duração do cache
     *
     * @var int $tempCache O tempo é marcado em segundos, default 86400 = Um Dia
     */
    private $tempCache = 86400;

    public function __construct()
    {
        $this->config = System::getConfig("poke");

        return $this;
    }

    /**
     * Função para pegar uma lista de pokemons com base no type requerido
     *
     * @param string $type Tipo Requerido
     *
     * @throws Exception
     *
     * @return array
     */
    public function getListByType(string $type): array
    {
        if (($cache = $this->cacheRequest($type)) !== false) {
            return [
                "status" => 200,
                "data"   => $cache
            ];
        }

        $url = $this->mountURL("type/" . $type);
        $ch  = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $url
        ]);

        $output = curl_exec($ch);

        if (!$output) {
            throw new \Exception(curl_errno($ch) . ': ' . curl_error($ch));
        } else {
            $json   = json_decode($output, true);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($status == 200) {
                $this->cacheSave($type, $json);
            }

            return [
                "status" => $status,
                "data"   => $json
            ];
        }

        curl_close($ch);

        return [
            "status" => 500,
            "data"   => ["error" => ""]
        ];
    }

    /**
     * Função para verificar e carregar o cache de uma requisição ainda utilizável
     *
     * @param string $index Valor chave do cache
     *
     * @return array|bool
     */
    private function cacheRequest(string $index)
    {
        $cache = sha1($index);
        $file  = System::mountAddress($this->localCache . $cache . ".json");

        if (file_exists($file) and filemtime($file) > time() - $this->tempCache) {
            $data = file_get_contents($file);
            $data = json_decode($data, true);

            return $data;
        }

        return false;
    }

    /**
     * Função para salvar um cache para ser utilizado posteriomente em outro requisição
     *
     * @param string $index Valor chave do cache
     * @param array  $data  Valor do cache a ser armazenado
     *
     * @return void
     */
    private function cacheSave(string $index, array $data)
    {
        $cache = sha1($index);
        $file  = System::mountAddress($this->localCache . $cache . ".json");

        file_put_contents($file, json_encode($data));
    }

    /**
     * Função para montar a URL para efetuar a requisição para a API
     *
     * @param $endpoint Rota da API
     *
     * @return string Final
     */
    private function mountURL(string $endpoint)
    {
        return $this->config["HOST"] . $this->config["VERSION"] . "/" . $endpoint;
    }
}
