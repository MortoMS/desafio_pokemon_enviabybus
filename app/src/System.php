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
    public static function getConfig(string $file, string $directory = 'config/'): array
    {
        $file = System::mountAddress($directory . $file . ".php");

        if (file_exists($file)) {
            $data = require $file;

            if (is_array($data)) {
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
    public static function loadingFileEnv(string $directory = '/', string $file = '.env')
    {
        $file = System::mountAddress($directory . $file);

        if (file_exists($file)) {
            $rf = fopen($file, "r");

            while (!feof($rf)) {
                $value = trim(fgets($rf));

                if ((bool) preg_match("/([A-Z_]+=+(.*))/", $value)) {
                    putenv($value);
                }
            }

            fclose($rf);

            return;
        }

        throw new \Exception(
            "O arquivo com ás variavel de ambiente não foi encontrado '" . $file . "'."
        );
    }

    /**
     * Monta o endereço do diretorio ou arquivo para o sistema em que o projeto está rodando
     *
     * @param string $address Endereço do arquivo ou diretorio
     * @param string $index Chave usada como referencia, default '/'
     *
     * @return string
     */
    public static function mountAddress(string $address, string $index = "/"): string
    {
        $address = str_replace($index, DIRECTORY_SEPARATOR, $address);

        return RAIZ . DIRECTORY_SEPARATOR . $address;
    }

    /**
     * Retorna uma variavel de ambiente ja carregada no sistema
     *
     * @param string $name Nome da variavel de ambiente
     *
     * @return string
     */
    public static function getEnv(string $name)
    {
        return getenv($name);
    }
}
