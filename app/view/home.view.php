<?php $renderComponent("Layout/header"); ?>

<section class="container section-home">
    <div>
        <div class="row">
            <div class="col m8 s12 offset-m2 offset-s0">
                <div class="card">
                    <div class="form">
                        <img class="logo" src="<?= $base ?>assets/img/logo.png" >
                        <?php if (isset($error)) : ?>
                            <p><?= $error ?></p>
                        <?php else : ?>                           
                            <p>Insira uma cidade para visualizar qual pokémon você pode encontrar.<p>
                        <?php endif; ?>
                        <?php $renderComponent("Component/form", ["search" => $search ?? '']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $renderComponent("Layout/footer"); ?>
