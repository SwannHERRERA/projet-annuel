<div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-12">
            <h1><?= $serie->seriesName?></h1>
            <form method="post" action="insert.php">
                Ajouter -><input type="submit" name="insert" id="insert" value="'.$serie->id.'" />
            </form>';
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
                    <h3>Episodes :</h3>
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
                    <h3>Acteurs : </h3>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php
                        $actors = $this->api->series_actors($serie->id);
                        foreach ($actors as $actor) :?>
                        <div class="col-sm-4 mb-3">
                            <?php if (!empty($actor->image)): ?>
                                <img src="<?= $this->imurl . $actor->image ?>" width="200">
                            <?php else : ?>
                                <img src="https://incomarinspection.com/wp-content/uploads/2017/04/Unknown-Profile.png" width="150">
                            <?php endif ?>
                            <br>
                            <p>Nom : <?= $actor->name?></p>
                            <p>Rôle : <?= $actor->role?></p>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
