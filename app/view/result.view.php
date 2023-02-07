<?php $renderComponent("Layout/header"); ?>
<section class="container section-home">
    <div>
        <a href="<?= $base ?>" class="waves-effect waves-light btn mt-2">Voltar</a>
        <?php if (isset($pokemon)) : ?>
            <p>Parece que o clima em <?= $city ?> está favoravel para a captura de pokemon do tipo <?= $type ?></p>
            <p>A temperatura é de <?= $temp ?>°C, <?= $description ?>.</p>
            <p>
                O Pokemon Selecionado foi o
                <a href="#<?= $selectP ?>" style="text-transform: capitalize;"><?= $selectP ?></a>
            </p>
            <hr>
            <p>Lista de Pokemons que você pode encontrar nessa área: </p>
            <div class="row">
                <?php foreach ($pokemon as $item) : ?>
                    <?php
                        $link    = explode("/", $item["pokemon"]["url"]);
                        $num     = $link[count($link) - 2];
                        $linkImg = (
                            "https://raw.githubusercontent.com/PokeAPI/sprites/" .
                            "master/sprites/pokemon/other/official-artwork/" .
                            $num .
                            ".png"
                        );
                    ?>
                    <div class="col l3 m4 s12">
                        <div id="<?= $item["pokemon"]["name"] ?>" class="card">
                            <div class="card-image">
                                <img 
                                    alt="<?= $item["pokemon"]["name"] ?>"
                                    onerror="imgNotFound(this)"
                                    src="<?= $linkImg ?>"
                                >
                                <div class="card-content mt-2">
                                    <span class="card-title pokemon-name">
                                        <?= $item["pokemon"]["name"] ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    function imgNotFound(event) {
        event.src = "<?= $base ?>assets/img/not-found.png";
    }
</script>

<?php $renderComponent("Layout/footer"); ?>