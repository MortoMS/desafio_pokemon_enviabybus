<?php

namespace App\Services;

class AutoComplete
{   
    /**
     * Local e arquivo do cache de autocomplete
     * 
     * @var string $localCache
     */
    private static $localCache = RAIZ . "/cache/autocomplete.txt";

    /**
     * Carrega uma valor no arquivo de cache do auto complete
     * 
     * @return void
     */
    public static function setValue(string $value)
    {
        $data = self::getValue();

        if (array_search($value, $data) === false)
        {
            file_put_contents(self::$localCache, $value . ",", FILE_APPEND);
        }
    }

    /**
     * Pegar os valores do arquivo de cache do auto complete
     * 
     * @return array
     */
    public static function getValue(): array
    {
        if (file_exists(self::$localCache))
        {
            $data = file_get_contents(self::$localCache);
            $data = explode(",", $data);
            array_pop($data);

            return $data;
        }

        return [];
    }

    /**
     * Função para limpar o cache para o auto complete
     * 
     * @return void
     */
    public static function clearCache()
    {
        file_put_contents(self::$localCache, "");
    }

    /**
     * Função para carregar uma lista para o cache do auto complete
     * 
     * @return void
     */
    public static function setValueAll(array $values)
    {
        file_put_contents(self::$localCache, $values);
    }
}
