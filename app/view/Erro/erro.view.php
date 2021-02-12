<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erro - <?=$status?></title>
        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0px;
                padding: 0px;
                font-family: 'arial';
                color: grey;
                background: #272727;
            }

            body {
                display: flex;
            }

            body > div {
                padding: 1em;
                text-align: center;
                max-width: 300px;
                width: 100%;
                margin: auto;
            }
        </style>
    </head>
    <body>
        <div>
            <h1>Erro | <?=$status?></h1>
            <?php
                if ($status == 404):
                    echo "<p>A página solicitada não foi encotrada.</p>";
                elseif ($status == 500):
                    echo "<p>", $error, "</p>";
                endif;

                echo "<a href=\"/\">Voltar para o inicio</a>";
            ?>  
        </div>
    </body>
</html>