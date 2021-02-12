# O Projeto

Esse projeto tem como finalidade concluir o desafio Pokémon para desenvolvedores dá Enviabybus.
[O desafio.](https://gitlab.com/enviabybus/weather-pokemon-test/-/tree/master/developer)

## Foco do Projeto

Esse projeto foi construido sem a necessidade de nenhum recurso de terceisos, 
isso é claro sem incluir às API e a Biblioteca gráfica que ele utiliza.


## Dependências

[PHP ^7.2](https://www.php.net) 

[Materialize](https://materializecss.com)

## Configuração do projeto


### Baixando o Projeto

Para baixa esse projeto basta executar o comando clone do [GIT](https://git-scm.com).

```
git clone https://github.com/MortoMS/desafio_pokemon_enviabybus.git
cd desafio_pokemon_enviabybus
```

Agora siga os passos dessejados para configura esse projeto em sua maquina ou em um servidor de sua preferencia.

#### Docker

Esse projeto já vem configurado com o [Docker](https://www.docker.com) [Compose](https://docs.docker.com/compose/), suponhando que você já tenha um [Docker](https://www.docker.com) em sua maquina, basta executar o comando: 

```
docker compose up
```

#### Apache

Para Configurar esse projeto para rotar em um servido Apache basta apontar o DocumentRoot do projeto para a pasta public, detro de app.

### Nginx

Para servidores Nginx basta utilizar esses arquivo de configuração.

> Essa confIguração e um modelo padrão e será necessário ajuste para cada maquina.

```
server {
    listen       80;
    server_name  localhost;

	root /var/www/app/public; 
    // local de uma aplicação padrão em servidores linux, necessário ajuste para cara maquina

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /assets/img/favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    // Essa configuração de php depende de como você configurou o seu sistema
    location ~ \.php$ {
        fastcgi_pass   php:9000;
        fastcgi_param  SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### PHP

É possivel executar essa aplicação em um servidor de debug do php.

Para utilizar esse serviço basta executar o comando:

```
php -S localhost:80 -t ./app/public
```

Para executar esse comando é necessario configurar o PHP CLI e a variável de ambiente "php".

### Notas

> Ao utilizar o PHP CLI para executar o servidor de teste é necessario configurar o openssl e curl, para evitar o erro de "SSL certificate problem: unable to get local issuer certificate".

> O conteudo do projeto antes da pasta app só necessário para servidores que vão utilizar o Docker.

> O endereço dá aplicação deve sempre apontar para a pasta public


### Variável de Ambiente

Para esse projeto funcionar corretamente é necessario configura o arquivo de variável de ambiente.

O primeiro passo é criar um arquivo, sem extensão, chamado .env, esse arquivo deve conter alguns dados, como o token de API para comunicação.

Abaixo está o modelo do arquivo, existe também uma copia do mesmo na pasta app com o nome de .env.example

```
APP_NAME=Desafio Pokémon
APP_URL=/
API_OPEN_WEATHER_HOST=https://api.openweathermap.org/data/
API_OPEN_WEATHER_VERSION=2.5
API_OPEN_WEATHER_TOKEN=?
API_POKE_HOST=https://pokeapi.co/api/
API_POKE_VERSION=v2
```

Nesse modelo só é nesessário modificar o API_OPEN_WEATHER_TOKEN, trocar o "?" pelo token da API do [Open Weather](openweathermap.org/api). 

> Finalizando a configuração do servidor e dás variáveis de ambiente a aplicação está pronta para rodar.

### Rotas / Endpoint

- -X GET / -- Págian inicial
- -X GET /search?q={city name},{state code},{country code} -- Página de pesquisa
- -X POST -d "q={city name},{state code},{country code}" -H "Content-Type: application/x-www-form-urlencoded" -- Página de pesquisa
- -X POST "/autocomplete" -- Lista de cidades já pesquisadas

A página de pesquisa permite a sua utilização como API retornado o resultado como 
"application/json", para isso funcionar corretamente basta configura em seu servidor o acesso a essa rota.

### Notas

> Em alguns casos será necessário adicionar permição para o PHP criar e alterar arquivos na pasta cache, em app.

> Nesse projeto foi adicionado uma regra extra incluindo o tipo de Pokémon psíquico, que não foi incluido no desafio.

### Author / Dev
[Gabriel Senna - Abismo Studios](https://abismostudios.com/). 
