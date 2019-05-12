<div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-12">
            <h1><?= $serie->seriesName?></h1>
            <form method="POST">
                <input type="submit" class="btn btn-secondary" name="insert" id="insert" value="Ajouter" />
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <?php $posters = $this->api->series_images($serie->id, ["keyType" => "poster", "resolution" => "680x1000"]);
            if (empty($posters)) {
                $this->api->language("en");
                $posters = $this->api->series_images($serie->id, ["keyType" => "poster", "resolution" => "680x1000"]);
                $this->api->language("fr");
            }
            if (!empty($posters)) {
                $image = array("filename" => $posters[0]->thumbnail, "score" => $posters[0]->ratingsInfo->average);
                foreach ($posters as $poster) {
                    if ($poster->ratingsInfo->average > $image["score"]) {
                        $image["filename"] = $poster->thumbnail;
                        $image["score"] = $poster->ratingsInfo->average;
                    }
                }
                echo '<img src="' . $this->imurl . $image["filename"] . '">';
            } else {
                echo '<img src="https://incomarinspection.com/wp-content/uploads/2017/04/Unknown-Profile.png" width="200">';
            }?>

        </div>
        <div class="col-8">
            <?php
                $lastUpdated = null;
                var_dump($result);
                if ($result != null) {
                    $lastUpdated = new DateTime($result);
                }
                if ($lastUpdated == null) {
                    ?>
                    <div class="text-danger">Série non présente en base</div> <?php
                } else {
                ?>
                <div class="text-success"> Série présente en base, date de dernière maj : <?php
                echo $lastUpdated->format("d/m/Y") . '</div>';
                }
            ?>
            <div> Date de diffusion :<?= $serie->firstAired ?></div>
            <div> Statut :<?= $serie->status ?></div>
            <div> Durée :<?= $serie->runtime ?> min</div>
            <div> Genres :
                <?php foreach ($serie->genre as $genre){
                    echo $genre . ", ";
                }?>
            </div>
            <div class="row mt-1"><h4>Résumé :</h4></div>
            <div class="row"><?=$serie->overview?></div>

        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="row mt-2">
                <div class="col-12">
                    <h3 class="mb-10">Episodes :</h3>
                </div>
            </div>
            <?php
            $page = 1;
            do {
                $episodes = $this->api->series_episodes($serie->id, $page);
                $i = 0;
                foreach ($episodes as $episode) {
                    if ($episode->airedSeason > 0) {
                        echo 'S' . $episode->airedSeason . 'E' . $episode->airedEpisodeNumber . ' ' . $episode->episodeName . '<br>';
                    }
                    $i++;
                }
                $page++;
            } while ($i > 99);
            ?>
        </div>
        <div class="col-8">
            <div class="row mt-2">
                <div class="col-12">
                    <h3 class="mb-10">Acteurs : </h3>
                    <?php
                    $actors = $this->api->series_actors($serie->id);?>
                    <div class="row">
                        <?php foreach ($actors as $actor) :?>
                            <div class="col-md-4 mb-3">
                                <?php if (!empty($actor->image)): ?>
                                    <img src="<?= $this->imurl . $actor->image ?>" class="img-fluid">
                                <?php else : ?>
                                    <img src="https://incomarinspection.com/wp-content/uploads/2017/04/Unknown-Profile.png" width="150">
                                <?php endif ?>
                                <br>
                                <p class="text-center mb-10">Nom : <?= $actor->name?></p>
                                <p class="text-center">Rôle : <?= $actor->role?></p>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
