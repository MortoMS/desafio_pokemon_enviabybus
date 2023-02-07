<?php

namespace App\Controllers;

use App\Response;
use App\Services\OpenWeather;
use App\Services\Poke;
use App\Services\AutoComplete;

class Search
{
    /**
     * Regras do tipos de pokemon
     *
     * @var array $types
     */
    private $types = [
        "ice"      => ['min' => -99, 'max' => 4, "lang" => "Gelo"],
        "water"    => ['min' => 5, 'max' => 9, "lang" => "Água"],
        "grass"    => ['min' => 12, 'max' => 14,  "lang" => "Grama"],
        "ground"   => ['min' => 15, 'max' => 20, "lang" => "Terra"],
        "bug"      => ['min' => 23, 'max' => 26, "lang" => "Inseto"],
        "rock"     => ['min' => 27, 'max' => 32, "lang" => "Pedra"],
        "fire"     => ['min' => 33, 'max' => 99, "lang" => "Fogo"],
        "normal"   => ['min' => -99, 'max' => 99, "lang" => "Normal"],
        "electric" => ['min' => -99, 'max' => 99, "lang" => "Elétrico"],
        "psychic"  => ['min' => -99, 'max' => 99, "lang" => "Psiquico"]
    ];

    /**
     * @Router("/search?q={city name},{state code},{country code}")
     */
    public function search(Response $response)
    {
        $data = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $q = trim(filter_input(INPUT_POST, "q", FILTER_SANITIZE_STRING));
        } else {
            $q = trim(filter_input(INPUT_GET, "q", FILTER_SANITIZE_STRING));
        }

        if (strlen($q) < 2 and strlen($q) < 60) {
            return $response->view("home", [
                "error" => "O valor inserido não atingiu ou ultrapassou " .
                    "o limite de caracteres para se efetuar uma pesquisa"
            ])->run();
        }

        $data["search"] = $q;
        $result         = $this->findTypePokemon($q);

        if (is_array($result)) {
            if ($pokemon = $this->findPokemonType($result["type"])) {
                $data["type"] = $this->types[$result["type"]]["lang"];
                $data["selectP"] = $this->randPokemon($pokemon["pokemon"]);
                $data = array_merge($data, $result, ["pokemon" => $pokemon["pokemon"]]);
            } else {
                $data["error"] = "A pesquisa \"" . $q . "\" não retornou nenhum pokemon nessa cidade.";
            }
        } elseif ($result === false) {
            $data["error"] = "A pesquisa \"" . $q . "\" não retornou nenhum resultado valido.";
        } else {
            $data["error"] = "Ocorreu um erro no servidor, tente novamente mais tarde.";
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            return $response->json($data)->run();
        }

        if (!isset($data["error"])) {
            return $response->view("result", $data)->run();
        }

        return $response->view("home", $data)->run();
    }

    /**
     * @Router("/autocomplete")
     */
    public function autocomplete(Response $response)
    {
        $data = AutoComplete::getValue();

        $response->json($data)->run();
    }

    private function findPokemonType(string $type): array
    {
        $pk     = new Poke();
        $result = $pk->getListByType($type);

        if ($result["status"] == 200) {
            return $result["data"];
        }

        return [];
    }

    private function findTypePokemon(string $q)
    {
        $ow     = new OpenWeather();
        $result = $ow->getCurrentWeather($q);

        if ($result["status"] == 200) {
            $temp = (int) $result["data"]["main"]["temp"];
            $sky = $result["data"]["weather"][0]["main"];
            $select = null;

            if ($sky == "Rain") {
                $select = "electric";
            } elseif ($sky == "Clouds") {
                $select = "psychic";
            } else {
                foreach ($this->types as $type => $values) {
                    if ($temp >= $values["min"] and $temp <= $values["max"]) {
                        $select = $type;
                        break;
                    }
                }
            }

            if ($select != null) {
                return [
                    "description" => $result["data"]["weather"][0]["description"],
                    "temp"        => $temp,
                    "city"        => $result["data"]["name"],
                    "type"        => $select
                ];
            }
        } elseif ($result["status"] == 404) {
            return false;
        }

        return null;
    }

    private function randPokemon(array $pokemons): string
    {
        $pokemon = null;

        if (isset($_SESSION["pokemon"])) {
            $pokemon = $_SESSION["pokemon"];
        }

        do {
            $rand = $pokemons[rand(0, count($pokemons) - 1)]["pokemon"]["name"];
        } while ($pokemon == $rand);

        $_SESSION["pokemon"] = $rand;

        return $rand;
    }
}
