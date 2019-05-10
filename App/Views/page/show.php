<?php
/**
 * Created by PhpStorm.
 * User: MaleWhere
 * Date: 03/05/2019
 * Time: 14:45
 */
require_once BASEPATH . '/Core/functions.php';
$idShow = 121361;
$show = getTVShow($idShow);
?>

<div class="col-md-9 col-lg-10">
    <div class="row mt-20 ml-20">
        <div class="col-md-3 align-self-baseline">
            <h1 class="h2"><?= $show['name_show'] ?></h1>
        </div>
        <div class="col-md-9 align-self-baseline h3">
            Rang : ## Score : <?= getShowScore($idShow) ?> Suivi par : <?= getTVShowFollowersNumber($idShow) ?>
            utilisateurs
        </div>
    </div>
    <hr>
    <div class="row mt-20 ml-20">
        <p class="align-self-baseline h3">
            Date de parution : <?= $show['first_aired_show'] ?> -
            Producteurs : <?php foreach (getTVShowNetworks($idShow) as $network) echo $network['name_network'] . ' '; ?>
            -
            <?= getTVNumberSeasons($idShow) ?> saisons
            <br><br>
            Statut : <?= ($show['production_status'] == 'Ended') ? 'Terminé' : 'En cours' ?>
        </p>
    </div>
    <hr>
    <div class="row mt-20">
        <div class="col-lg-3">
            <img alt="image show" class="img-fluid mx-auto d-block" src=<?= '"' . $show['image_show'] . '"' ?>>
            <div class="row pt-10">
                <div class="col-12 text-center">
                    <span class="fa fa-star " style="color: orange"></span>
                    <span class="fa fa-star " style="color: orange"></span>
                    <span class="fa fa-star " style="color: orange"></span>
                    <span class="fa fa-star " style="color: orange"></span>
                    <span class="fa fa-star " style="color: orange"></span>
                    <span class="fa fa-star " style="color: orange"></span>
                    <span class="fa fa-star " style="color: orange"></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                </div>
                <?php if ($this->member_model->isConnected()) { ?>
                    <div class="col-12 mt-10 text-center">
                        <button type="button" class="btn btn-warning">Noter cette série</button>
                    </div>
                    <div class="col-12 mt-10 text-center">
                        <button type="button" class="btn btn-success pt-10 pb-10">Ajouter à ma liste</button>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-3 col-xl-2 pt-10 pb-10 border">
            <div class="row pl-10 pb-10">
                Nombre d'épisodes : <?= getTVNumberEpisodes($idShow) ?>
            </div>
            <div class="row pl-10 pb-10">
                Nombre de saisons : <?= getTVNumberSeasons($idShow) ?>
            </div>
            <div class="row pl-10 pb-10">
                Durée moyenne : <?= $show['runtime_show'] . ' min' ?>
            </div>
            <div class="row pl-10 pb-10">
                Status : <?= $show['production_status'] ?>
            </div>
            <div class="row pl-10 pb-10">
                Date de diffusion : <?php
                if ($show['production_status'] == 'Continuing')
                    echo $show['first_aired_show'];
                else
                    echo 'Du ' . $show['first_aired_show'] . ' au ' . getShowLastAiringDate($idShow);
                ?>
            </div>
            <div class="row pl-10 pb-10">
                Producteurs
                : <?php foreach (getTVShowNetworks($idShow) as $network) echo $network['name_network'] . ' '; ?>
            </div>
            <div class="row pl-10 pb-10">
                Genres
                : <?php foreach (getTVShowCategories($idShow) as $categorie) echo $categorie['name_category'] . ' ' ?>
            </div>
        </div>

        <div class="col-lg-6">
            <ul class="nav nav-tabs" id="showTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#details" role="tab"
                       aria-controls="home" aria-selected="true">Détails</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="episodes-tab" data-toggle="tab" href="#episodes" role="tab"
                       aria-controls="episodes" aria-selected="false">Episodes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="casting-tab" data-toggle="tab" href="#casting" role="tab"
                       aria-controls="casting" aria-selected="false">
                        Casting
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="recommendations-tab" data-toggle="tab" href="#recommendations" role="tab"
                       aria-controls="recommendations" aria-selected="false">Recommendations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab"
                       aria-controls="comments" aria-selected="false">Commentaires</a>
                </li>

            </ul>
            <div class="tab-content" id="tabShowContent">
                <div class="tab-pane fade show active mt-10" id="details" role="tabpanel" aria-labelledby="home-tab">
                    <h1 class="h3">Synopsis</h1>
                    <hr>
                    <p class="text-justify">
                        <?= $show['summary_show'] ?>
                    </p>
                </div>
                <div class="tab-pane mt-10" id="episodes" role="tabpanel" aria-labelledby="episodes-tab">
                    <h1 class="h3">Episodes</h1>
                    <hr>
                    <div class="accordion">
                        <?php
                        $episodes = getTVEpisodes($idShow);
                        foreach ($episodes as $episode) { ?>
                            <div class="card">
                                <div class="card-header"
                                     id=<?php echo '"heading' . $episode['nb_season'] . $episode['nb_episode'] . '"'; ?>>
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                                aria-expanded="false"
                                                aria-controls=<?php echo '"collapse' . $episode['nb_season'] . $episode['nb_episode'] . '"'; ?>
                                                data-target=<?php echo '"#collapse' . $episode['nb_season'] . $episode['nb_episode'] . '"'; ?>>
                                            <?php echo $episode['nb_season'] . 'x' . $episode['nb_episode'] . ' - ' . $episode['name_episode']; ?>
                                        </button>
                                    </h5>
                                </div>
                                <div id=<?php echo '"collapse' . $episode['nb_season'] . $episode['nb_episode'] . '"'; ?>
                                     class="collapse" data-parrent="#accordion"
                                     aria-labelledby=<?php echo '"heading' . $episode['nb_season'] . $episode['nb_episode'] . '"'; ?>>
                                    <div class="card-body text-justify">
                                        <?php echo $episode['summary_episode']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane mt-10" id="casting" role="tabpanel" aria-labelledby="casting-tab">
                    <h1 class="h3">Casting</h1>
                    <hr>
                    <div class="row">
                        <?php foreach (getTVShowActors($idShow) as $actor) {
                            ?>
                            <div class="card col-xl-4 col-sm-6 mt-10">
                                <img class="card-img-top" alt="Actor photo"
                                     src=<?= '"' . $actor['photo_actor'] . '"'; ?>>
                                <div class="card-body">
                                    <h5 class="card-title"><?= $actor['name_actor']; ?></h5>
                                    <p>Role : <?= $actor['role_actor']; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane mt-10" id="recommendations" role="tabpanel" aria-labelledby="recommendations-tab">
                    <h1 class="h3">Recommendations</h1>
                    <hr>
                </div>
                <div class="tab-pane mt-10" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                    <h1 class="h3">Commentaires</h1>
                    <hr>
                </div>
            </div>

        </div>
    </div>

</div>
