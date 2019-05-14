<div class="container">
    <div class="row">
        <div class="col">
            <h1 class='mb-5'>Recherche pour : <?= $_POST['query'] . ", resultats : " . sizeof($results)?></h1>

            <?php foreach ($results as $result) : ?>
                <div class="row mt-2">
                    <div class="col-12">
                        <a href="<?= $site_url . '/back/tv_show/detail?idserie='.$result->id?>">
                            <h4><?= $result->seriesName ?></h4>
                        </a>
                    </div>
                </div>
                <?php if (!empty($result->banner)) :?>
                    <div class="row">
                        <div class="col-12">
                            <a href="<?= $site_url . '/back/tv_show/detail?idserie='.$result->id?>">
                                <img src="<?= $this->imurl . $result->banner ?>">
                            </a>
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
</div>
