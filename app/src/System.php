<?php

namespace App;

class System
{
    /**
     * Retorna às configurações requeridas
     * 
     * @param string $file      Nome do arquivo com a configuração requerida
     * @param string $directory Local aonde se encontra o arquivo de configuração
     * 
     * @throws Exception
     * 
     * @return array
     */
    public static function getConfig(string $file, string $directory = RAIZ . "/config/"): array
    {
        $file = $directory . $file . ".php";
 
        if (file_exists($file))
        {
            $data = require $file;

            if (is_array($data))
            {
                return $data;
            }
        }
 
        
        throw new \Exception("O arquivo de configuração não foi encontrado em '" . $file . "'.");
    }

    /**
     * Carrega às variáveis de ambiente no sistema
     * 
     * @param string $directory Local aonde se encontra o arquivo contendo a variável de ambiente
     * @param string $file      Nome do arquivo que contemm às variáveis de ambiente
     * 
     * @throws Exception
     * 
     * @return void
     */
    public static function loadingFileEnv(string $directory = RAIZ, string $file = '.env')
    {
        $file = $directory . '/'. $file;

        if (file_exists($file))
        {
            $rf = fopen($file, "r");

            while(!feof($rf))
            {
                $value = trim(fgets($rf));

                putenv($value);
            }   

            fclose($rf);

            return;
        }

        throw new \Exception(
            "O arquivo com ás variavel de ambiente não foi encontrado '" . $file . "'."
        );
    }
}

