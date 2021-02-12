<?php

namespace App\Services;

use App\System;
use App\Services\AutoComplete;

class OpenWeather
{
    /**
     * Dados de configuração da API
     * 
     * @var array $config 
     */
    private $config = [];

    /**
     * Local para armazenamento de arquivo para cache
     * 
     * @var string $localCache, default "/cache/open_weather/"
     */
    private $localCache = "/cache/open_weather/";

    /**
     * Tempo de duração do cache
     * 
     * @var int $tempCache O tempo é marcado em segundos, default 1800 = Meia hora
     */
    private $tempCache = 1800;

    public function __construct()
    {
        $this->config = System::getConfig("open_weather");
        
        return $this;
    }

    /**
     * Requisição para API do Open Weather para o endpoint de dados do clima atual
     * 
     * @param string $q     Nome da cidade, código do estado e país referente ao clima desejado,
     *                      O formato requerido é ISO 3166. 
     * @param string $units Unidade de retorna dos dados da API, default "metric".
     *                      Valores suportados https://openweathermap.org/current#data
     * @param string $lang  linguagem resposta de APi, default "pt_BR"(Português Brasileiro).
     *                      Valores suportados https://openweathermap.org/current#multi
     * @param string $mode  Formato de resposta da API, default "json".
     *                      Valores Suportados https://openweathermap.org/current#format
     * 
     * @throws Exception
     * 
     * @return array
     */
    public function getCurrentWeather(
        string $q, 
        string $units = "metric", 
        string $lang = "pt_BR", 
        string $mode = "json"
    ): array
    {   
        if (($cache = $this->cacheRequest(strtoupper($q))) !== false)
        {
            return [
                "status" => 200,
                "data" => $cache
            ];
        }

        $params = compact("q", "units", "lang", "mode");
        $params["appid"] = $this->config["TOKEN"];
        $params = http_build_query($params);

        $request = $this->mountURL("weather") . "?" . $params;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $request
        ]);

        $output = curl_exec($ch);
        

        if (!$output)
        {
            throw new \Exception(curl_errno($ch) . ': '. curl_error($ch));
        }
        else
        {
            $res = json_decode($output, true);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($status == 200)
            {
                $this->cacheSave(strtoupper($q), $res);
                $this->cacheSave(strtoupper($res["name"]), $res);

                AutoComplete::setValue($res["name"]);
            }

            return [
                "status" => $status,
                "data" => $res
            ];
        }

        curl_close($ch);

        return [
            "status" => 500,
            "data" => ["error" => ""]
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

        $file = RAIZ . $this->localCache . $cache . ".json";

        if (file_exists($file) and filemtime($file) > time() - $this->tempCache)
        {
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

        $file = RAIZ . $this->localCache . $cache . ".json";

        file_put_contents($file, json_encode($data));
    }

    /**
     * Função para montar a URL para efetuar a requisição para a API
     * 
     * @param $endpoint Rota da API
     * 
     * @return string Final 
     */
    private function mountURL(string $endpoint = null): string
    {
        return $this->config["HOST"] . $this->config["VERSION"] . "/" . $endpoint;
    }
}