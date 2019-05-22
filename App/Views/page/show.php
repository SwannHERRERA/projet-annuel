<?php
/**
 * Created by PhpStorm.
 * User: MaleWhere
 * Date: 03/05/2019
 * Time: 14:45
 */
require_once BASEPATH . '/Core/functions.php';
if (!getTVShow($idShow)) {
    header("Location:/");
}
$show = getTVShow($idShow);
?>

<div class="col-md-9 col-lg-10">
    <div class="row mt-20 ml-20">
        <div class="col-md-3 align-self-baseline">
            <h1 class="h2"><?= $show['name_show'] ?></h1>
        </div>
        <div class="col-md-9 align-self-baseline h3">
            Rang : <?= getShowRank($idShow) ?> Score : <?= getShowScore($idShow) ?> Suivi par
            : <?= getTVShowFollowersNumber($idShow) ?>
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
                <?php if ($this->member_model->isConnected()) { ?>
                    <div class="col-12 text-center">
                        <?php $mark = getShowMarkMember($idShow, $_SESSION['email']);
                        $i = 1;
                        if (empty($mark))
                            for (; $i <= 10; $i++)
                                echo '<span class="fa fa-star "></span>';
                        else {

                            for (; $i <= $mark; $i++)
                                echo '<span class="fa fa-star" style="color: orange"></span>';
                            for (; $i <= 10; $i++)
                                echo '<span class="fa fa-star "></span>';
                        }
                        ?>
                    </div>
                    <div class="col-12 mt-10 text-center">
                        <button type="button" class="btn btn-warning">Noter cette série</button>
                    </div>
                    <?php if (isFollowing($_SESSION['email'], $idShow)) { ?>
                        <div class="col-12 mt-10 mb-10 text-center">
                            <a href="/show/unfollow?show=<?= $idShow ?>" class="btn btn-success pt-10 pb-10">Retirer de
                                ma liste</a>
                        </div>
                    <?php } else { ?>
                        <div class="col-12 mt-10 mb-10 text-center">
                            <button type="button" class="btn btn-success pt-10 pb-10" data-toggle="modal"
                                    data-target="#addShowList" aria-hidden="true">Ajouter à ma liste
                            </button>
                            <div class="modal fade" id="addShowList" tabindex="-1" role="dialog"
                                 aria-labelledby="addShowList" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="h3 modal-title">Suivre cette série</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-3 text-center">
                                                    <img alt="image show" class="img-fluid mx-auto d-block"
                                                         src=<?= '"' . $show['image_show'] . '"' ?>>
                                                    <hr>
                                                    <?= $show['name_show'] ?><br>
                                                    <?= $show['first_aired_show'] ?>
                                                </div>
                                                <div class="col-9">
                                                    <form action="/show/follow" method="post">
                                                        <input type="hidden" name="show" value="<?= $idShow ?>">
                                                        <div class="form-group row">
                                                            <label class="col-12 text-left">Avancement :</label>
                                                            <div class="col-md-4 text-left">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="status" id="status1" value="termine"
                                                                           checked>
                                                                    <label class="form-check-label" for="status1">Completé</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 text-left">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="status" id="status2" value="en cours">
                                                                    <label class="form-check-label" for="status2">En
                                                                        cours</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 text-left">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="status" id="status3" value="a voir">
                                                                    <label class="form-check-label" for="status3">À
                                                                        voir</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-12 text-left" for="mark">Noter
                                                                        cette série :</label>
                                                                    <div class="col-md-8">
                                                                        <select class="form-control" id="mark"
                                                                                name="mark">
                                                                            <option></option>
                                                                            <option>0</option>
                                                                            <option>1</option>
                                                                            <option>2</option>
                                                                            <option>3</option>
                                                                            <option>4</option>
                                                                            <option>5</option>
                                                                            <option>6</option>
                                                                            <option>7</option>
                                                                            <option>8</option>
                                                                            <option>9</option>
                                                                            <option>10</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check row">
                                                                    <div class="col-12 text-left">
                                                                        <input class="form-check-input" type="checkbox"
                                                                               value="o" name="notification"
                                                                               id="notification">
                                                                        <label class="form-check-label"
                                                                               for="notification">S'abonner aux
                                                                            notifications</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-10">
                                                            <div class="col-12 text-left">
                                                                <button type="submit" class="btn btn-primary">
                                                                    Confirmer
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>
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
                : <?php foreach (getShowCategories($idShow) as $categorie) echo $categorie['name_category'] . ' '; ?>
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
                    <div class="row">
                        <div class="col-md-3 text-left align-baseline">
                            <h1 class="h3">Episodes</h1>
                        </div>
                        <div class="col-md-8 text-right align-baseline">
                            <div class="row">
                                <div class="col-md-6">
                                    <a onclick="watchAll(<?=$idShow?>)"> Tout marquer comme vu</a>
                                </div>
                                <div class="col-md-6">
                                    <a onclick="unwatchAll(<?=$idShow?>)"> Tout marquer comme non vu</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="accordion">
                        <?php
                        $episodes = getShowEpisodes($idShow);
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
                                        <?php if (isFollowing($_SESSION['email'], $idShow)) {
                                            if (isWatchedEpisode($_SESSION['email'], $episode['id_episode'])) { ?>
                                                <i id="<?= $episode['id_episode'] ?>"
                                                   class="fas fa-eye-slash"
                                                   onclick="checkEp(<?= $episode['id_episode'] ?>)"></i>
                                            <?php } else { ?>
                                                <i id="<?= $episode['id_episode'] ?>"
                                                   class="fas fa-eye"
                                                   onclick="checkEp(<?= $episode['id_episode'] ?>)"></i>
                                                <?php
                                            }
                                        }
                                        ?>
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
<script src="<?= $site_url . '/js/show.js' ?>"></script>
