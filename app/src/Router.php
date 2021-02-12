<?php

namespace App;

use App\Response;

class Router
{
    /**
     * Variável para verificar se alguma requisição foi bem sucedida
     * 
     * @var bool $status, default false
     */
    private static $status = false;

    /**
     * Função para criar uma requisição(Endpoint) dá aplicação
     * 
     * @param array          $methods  Lista de Métodos aceitos pela requisição,
     *                                 Methodos validos, "POST" OU "GET"
     * @param string         $url      Url que vai ser comparada com a url requerida.
     * @param array|function $callback Lista com classes e métodos ou função a serem chamados
     *                                 caso a requisição seja valida. Ex..:
     *                                 [App\Controllers\Controller::class => "{Metodo}"] ou function(){}
     * 
     * @return void
     */
    public static function request(array $methods, string $url, $callback)
    {
        if (self::validationRequest($methods, $url))
        {
            if(is_array($callback))
            {
                foreach($callback as $class => $action)
                {
                    $ch = new $class;
                    
                    if ($ch->{$action}(new Response) === false)
                    {
                        break;
                    }
                }
            }
            elseif (is_callable($callback))
            {
                $callback(new Response);
            }

            self::$status = true;
        }
    }

    /**
     * Função de validação da requisição
     * 
     * @param array  $methods Lista de Métodos aceitos pela requisição,
     *                        Methodos validos, "POST" OU "GET"
     * @param string $url     Url que vai ser comparada com a url requerida.
     * 
     * @return bool
     */
    private static function validationRequest(array $methods, string $url): bool
    {
        if (self::validationMethods($methods) and self::validationURL($url))
        {
            return true;
        }

        return false;
    }

    /**
     * Função de validação de methodo requerido
     * 
     * @param array $methods Lista de Métodos aceitos pela requisição,
     *                       Methodos validos, "POST" OU "GET"
     * 
     * @return bool 
     */
    private static function validationMethods(array $methods): bool
    {
        $method = strtoupper($_SERVER["REQUEST_METHOD"]);

        foreach($methods as $method_e)
        {
            if (strtoupper($method_e) === $method)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Função de validação de url
     * 
     * @param string $url Url que vai ser comparada com a url requerida.
     * 
     * @return bool
     */
    private static function validationURL(string $url): bool
    {
        $url_i = explode("?", $_SERVER['REQUEST_URI'])[0];

        if ($url_i === $url)
        {
            return true;
        }

        return false;
    }

    /**
     * Função de resposta para casos de página não encontrada.
     * 
     * @return void
     */
    public static function erro404($callback)
    {
        if (self::$status === false)
        {   
            $callback(new Response);
        }
    }

}