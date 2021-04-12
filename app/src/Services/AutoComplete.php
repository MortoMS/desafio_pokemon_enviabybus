<?php

namespace App\Services;

use App\System;

class AutoComplete
{   
    /**
     * Local e arquivo do cache de autocomplete
     * 
     * @var string $fileCache
     */
    private static $fileCache =  "/cache/autocomplete.txt";

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
            $file = System::mountAddress(self::$fileCache);

            file_put_contents($file, $value . ",", FILE_APPEND);
        }
    }

    /**
     * Pegar os valores do arquivo de cache do auto complete
     * 
     * @return array
     */
    public static function getValue(): array
    {

        $file = System::mountAddress(self::$fileCache);

        if (file_exists($file))
        {
            $data = file_get_contents($file);
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
        $file = System::mountAddress(self::$fileCache);

        file_put_contents($file, "");
    }

    /**
     * Função para carregar uma lista para o cache do auto complete
     * 
     * @param array $values Valores do autocomplete
     * 
     * @return void
     */
    public static function setValueAll(array $values)
    {
        $file = System::mountAddress(self::$fileCache);

        file_put_contents($file, explode(",", $values));
    }
}
