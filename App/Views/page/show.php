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
<script src="<?= $site_url . '/js/show.js' ?>"></script>
<div class="col-md-9 col-lg-10">
    <h1 class="h2 text-center mb-30"><?= $show['name_show'] ?></h1>

    <div class="row banner-gold">
        <div class="col-md-4 align-self-baseline h3">
            <b>Rang : #<?= getShowRank($idShow) ?></b>
        </div>
        <div class="col-md-4 align-self-baseline h3">
            <b>Score : <?= getShowScore($idShow) ?></b>
        </div>
        <div class="col-md-4 align-self-baseline h3">
            <?php $nbFollowers = getTVShowFollowersNumber($idShow);
            $word = $nbFollowers > 1 ? 'membres' : 'membre'?>
            <b>Suivie par <?= $nbFollowers." ".$word ?></b>
        </div>
    </div>
    <br>
    <div class="row mt-20">
        <div class="col-lg-3">
            <img alt="image show" class="img-fluid mx-auto d-block" style="padding:1px; border:1px solid rgba(255,215,0,0.9);" src=<?= '"' . $show['image_show'] . '"' ?>>
            <div class="row pt-10">
                <?php if ($this->member_model->isConnected()) { ?>
                    <?php if (isFollowing($_SESSION['email'], $idShow)) {
                        $mark = getShowMarkMember($idShow, $_SESSION['email']);
                        $status = getShowStatusMember($idShow, $_SESSION['email']) ?>
                        <div id="userRating" class="col-12 text-center"
                             onmouseout="rating(<?= empty($mark) ? 0 : $mark ?>,<?= $idShow ?>)">
                            <script type="text/javascript">rating(<?= empty($mark) ? 0 : $mark ?>,<?=$idShow?>);</script>
                        </div>
                        <div class="col-3"></div>
                        <div id="userStatus" class="col-6">
                            <script type="text/javascript">statusShow(<?='"' . $status . '",' . $idShow?>)</script>
                        </div>
                        <div class="col-12 mt-10 mb-10 text-center">
                            <a href="/show/unfollow?show=<?= $idShow ?>" class="btn btn-gold pt-10 pb-10">Ne plus
                                suivre</a>
                            <?php if (isNotified($_SESSION['email'], $idShow) == "o") { ?>
                                <button onclick="checkNotification(<?= $idShow ?>)" class="btn btn-info"><i
                                            id="notificationCheck" class="fas fa-bell-slash"></i>
                                </button>
                            <?php } else { ?>
                                <button onclick="checkNotification(<?= $idShow ?>)" class="btn btn-info"><i
                                            id="notificationCheck" class="far fa-bell"></i>
                                </button>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="col-12 mt-10 mb-10 text-center">
                            <button type="button" class="btn btn-gold pt-10 pb-10" data-toggle="modal"
                                    data-target="#addShowList" aria-hidden="true">Suivre cette série
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
                                                    <?= date('d-m-Y', strtotime($show['first_aired_show'])) ?>
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
                    <?php } ?>
                    <div class="col-12 mt-10 mb-10 text-center">
                        <button type="button" class="btn btn-gold pt-10 pb-10" data-toggle="modal"
                                data-target="#addUserShowList" aria-hidden="true">Ajouter à une liste
                        </button>
                        <div class="modal fade" id="addUserShowList" tabindex="-1" role="dialog"
                             aria-labelledby="addUserShowList" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="h3 modal-title">Ajouter à une liste</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-3 text-center">
                                                <img alt="image show" class="img-fluid mx-auto d-block"
                                                     src=<?= '"' . $show['image_show'] . '"' ?>>
                                                <hr>
                                                <?= $show['name_show'] ?><br>
                                                <?= date('d-m-Y', strtotime($show['first_aired_show'])) ?>
                                            </div>
                                            <div class="col-sm-9 text-left">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="h4">Vos listes :</h5>
                                                        <table class="table table-striped table-dark table-responsive-sm">
                                                            <thead>
                                                            <tr>
                                                                <th>Nom</th>
                                                                <th>Description</th>
                                                                <th>Visibilité</th>
                                                                <th>Options</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="lists">
                                                            <?php foreach (getMemberLists($_SESSION['email']) as $list) { ?>
                                                                <tr id="list<?= $list['id_list'] ?>">
                                                                    <th scope="row"><?= $list['name_list'] ?></th>
                                                                    <td><?= substr($list['description_list'], 0, 20) . (strlen($list['description_list']) > 20 ? '...' : '') ?></td>
                                                                    <td><?= $list['visibility_list'] ?></td>
                                                                    <td>
                                                                        <?php if (isInList($idShow, $list['id_list'])) { ?>
                                                                            <button onclick="checkList(<?= $idShow ?>,<?= $list['id_list'] ?>)"
                                                                                    class="btn btn-success m-5"><i
                                                                                        id="checkList<?= $list['id_list'] ?>"
                                                                                        class="fas fa-check"></i>
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <button onclick="checkList(<?= $idShow ?>,<?= $list['id_list'] ?>)"
                                                                                    class="btn btn-success"><i
                                                                                        id="checkList<?= $list['id_list'] ?>"
                                                                                        class="fas fa-plus"></i>
                                                                            </button>
                                                                        <?php } ?>
                                                                        <button onclick="removeList(<?= $list['id_list'] ?>)"
                                                                                class="btn btn-warning m-5"><i
                                                                                    class="fas fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div id="newList" class="col-12">
                                                        <h5 class="h4">Créer une liste</h5>

                                                        <div class="form-row form-inline align-items-end mb-20">
                                                            <div class="col-auto">
                                                                <label class="sr-only" for="nameListNew">Nom de la liste</label>
                                                                <input type="text" class="form-control" id="nameListNew" placeholder="Nom">
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="sr-only" for="descriptionListNew">Description de la liste</label>
                                                                <input type="text" class="form-control" id="descriptionListNew" placeholder="Description">
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="" for="visibilityNewList">Visibilité</label>
                                                                <select class="form-control" id="visibilityNewList">
                                                                    <option value="public">Publique</option>
                                                                    <option value="private">Privée</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                            <p class="text-center"><button onclick="addList(<?= $idShow ?>)"
                                                                class="btn btn-primary">
                                                                Ajouter
                                                            </button></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mb-20">
                <div class="card-header">
                    <h5 class="card-title">Informations</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Nombre d'épisodes : <?= getTVNumberEpisodes($idShow) ?></li>
                    <li class="list-group-item">Nombre de saisons : <?= getTVNumberSeasons($idShow) ?></li>
                    <li class="list-group-item">Durée moyenne : <?= $show['runtime_show'] . ' min' ?></li>
                    <li class="list-group-item">Statut : <?= ($show['production_status'] == 'Ended') ? 'Terminée' : 'En cours' ?></li>
                    <li class="list-group-item">Date de diffusion : <?php
                        if ($show['production_status'] == 'Continuing')
                            echo date('d-m-Y', strtotime($show['first_aired_show']));
                        else
                            echo 'du ' . date('d-m-Y', strtotime($show['first_aired_show'])) . ' au ' . date('d-m-Y', strtotime(getShowLastAiringDate($idShow)));
                    ?></li>
                    <li class="list-group-item">Diffusée sur : <?php foreach (getTVShowNetworks($idShow) as $network) echo $network['name_network'] . ' '; ?></li>
                    <li class="list-group-item">Genres : <?php foreach (getShowCategories($idShow) as $categorie) echo $categorie['name_category'] . ' '; ?></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <ul class="nav nav-tabs" id="showTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#details" role="tab"
                       aria-controls="home" aria-selected="true"><b>Synopsis</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="episodes-tab" data-toggle="tab" href="#episodes" role="tab"
                       aria-controls="episodes" aria-selected="false"><b>Episodes</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="casting-tab" data-toggle="tab" href="#casting" role="tab"
                       aria-controls="casting" aria-selected="false">
                        <b>Casting</b>
                    </a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" id="recommendations-tab" data-toggle="tab" href="#recommendations" role="tab"
                       aria-controls="recommendations" aria-selected="false"><b>Recommendations</b></a>
                </li>-->
                <li class="nav-item">
                    <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab"
                       aria-controls="comments" aria-selected="false"><b>Commentaires</b></a>
                </li>

            </ul>
            <div class="tab-content" id="tabShowContent">
                <div class="tab-pane fade show active mt-10" id="details" role="tabpanel" aria-labelledby="home-tab">
                    <p class="text-justify">
                        <?= $show['summary_show'] ?>
                    </p>
                </div>
                <div class="tab-pane mt-10" id="episodes" role="tabpanel" aria-labelledby="episodes-tab">
                    <div class="row">
                        <div class="col-md-12 text-right align-baseline">
                            <?php if ($this->member_model->isConnected() && isFollowing($_SESSION['email'], $idShow)) { ?>
                                <div class="row">
                                    <div class="col-md-6 text-md-right">
                                        <a class="btn btn-success" onclick="watchAll(<?= $idShow ?>)"> Tout marquer comme vu</a>
                                    </div>
                                    <div class="col-md-6 text-md-left">
                                        <a class="btn btn-danger" onclick="unwatchAll(<?= $idShow ?>)"> Tout marquer comme non vu</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
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
                                        <?php if ($this->member_model->isConnected() && isFollowing($_SESSION['email'], $idShow)) {
                                            if (isWatchedEpisode($_SESSION['email'], $episode['id_episode'])) { ?>
                                                <a class="btn btn-info" href="#"
                                                   onclick="checkEp(<?= $episode['id_episode'] ?>)">
                                                    <i id="<?= $episode['id_episode'] ?>"
                                                       class="fas fa-eye-slash"></i>
                                                </a>
                                            <?php } else { ?>
                                                <a class="btn btn-info" href="#"
                                                   onclick="checkEp(<?= $episode['id_episode'] ?>)">
                                                    <i id="<?= $episode['id_episode'] ?>"
                                                       class="fas fa-eye">
                                                    </i>
                                                </a>
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
                    <div class="row align-items-top">
                        <?php foreach (getTVShowActors($idShow) as $actor) {
                            ?>
                            <div class="col-xl-4 col-sm-6">
                                <div class="card  mb-20">
                                    <a href="<?= $site_url . '/recherche_avancee?actor%5B%5D=' . $actor['id_actor'] ?>"><img class="card-img-top" alt="Actor photo"
                                        src=<?= '"' . $actor['photo_actor'] . '"'; ?>></a>
                                    <div class="card-body">
                                        <a href="<?= $site_url . '/recherche_avancee?actor%5B%5D=' . $actor['id_actor'] ?>"><h5 class="card-title"><?= $actor['name_actor']; ?></h5></a>
                                        <div>Rôle : <?= $actor['role_actor']; ?></div>
                                    </div>
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
                    <?php $comments = getTVShowComments($idShow);
                    if ($this->member_model->isConnected()) {
                        $user = getMember($_SESSION['email']);
                        ?>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-8 form-group">
                                    <label for="commentWrite">Ecrire un commentaire</label>
                                    <textarea class="form-control" id="commentWrite" rows="2"></textarea>
                                </div>
                                <div class="col-2 align-self-center">
                                    <button type="button" class="btn btn-info mb-3" onclick="hideSpoiler('commentWrite')">Cacher un spoiler</button>
                                    <button class="btn btn-success"
                                            onclick="submitComment(<?= $idShow; ?>)">
                                        Envoyer
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12" id="userComments">
                        <?php foreach ($comments as $comment) { ?>
                            <div id="<?= $comment['id_comment'] ?>" class="row mb-10">
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <a target="_blank"
                                               href="<?= $site_url . '/profil?user=' . $comment['pseudo'] ?>"><img
                                                        src="<?= $comment['photo'] ?>"
                                                        class="img-thumbnail" alt="photo profile"></a>
                                        </div>
                                        <?php
                                        if ($this->member_model->isConnected() && ($comment['pseudo'] == $user['pseudo'] || $user['account_role'] == 'admin')) { ?>
                                            <div class="col-12 mt-5 text-center">
                                                <button class="btn btn-warning" onclick="deleteComment(<?= $comment['id_comment'] ?>)"><i class="fas fa-trash"></i></button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <a target="_blank"
                                               href="<?= $site_url . '/profil?user=' . $comment['pseudo'] ?>"><strong><?= $comment['pseudo'] ?></strong></a>
                                            <span class="text-muted">commenté le <?= date('d-m-Y', strtotime($comment['date_comment'])) ?></span>
                                        </div>
                                        <div class="card-body">
                                            <?= $comment['text_comment'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1 align-self-center">
                                    <div class="row">
                                        <?php if ($this->member_model->isConnected()) {
                                            if (isLikedComment($comment['id_comment'], $_SESSION['email'])) { ?>
                                                <div class="col-12 text-center">
                                                    <button onclick="checkLike(<?= $comment['id_comment'] ?>)"
                                                            class="btn btn-primary">
                                                        <i id="thumb<?= $comment['id_comment'] ?>"
                                                           class="fas fa-thumbs-up"></i>
                                                        <span id="nblikes<?= $comment['id_comment'] ?>"><?= $comment['nbLikes'] ?> </span>
                                                    </button>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-12 text-center">
                                                    <button onclick="checkLike(<?= $comment['id_comment'] ?>)"
                                                            class="btn btn-primary">
                                                        <i id="thumb<?= $comment['id_comment'] ?>"
                                                           class="far fa-thumbs-up"></i>
                                                        <span id="nblikes<?= $comment['id_comment'] ?>"><?= $comment['nbLikes'] ?> </span>
                                                    </button>
                                                </div>
                                                <?php
                                            }
                                        } else { ?>
                                            <div class="col-12 text-center">
                                                <button class="btn btn-primary">
                                                    <i id="thumb<?= $comment['id_comment'] ?>"
                                                       class="fas fa-thumbs-up"></i>
                                                    <span id="nblikes"><?= $comment['nbLikes'] ?> </span>
                                                </button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
