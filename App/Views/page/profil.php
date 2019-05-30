<script src="<?= $site_url . '/js/profil.js' ?>"></script>
<div class="col-md-9 col-lg-10 align-self">
    <div class="row mt-20 ml-20">
        <div class="col-sm-3">
            <!--<img class="img-fluid" src="<?= $user['photo'] ?>" alt="Photo de profil">-->
            <img class="img-fluid"
                 src="<?= $user['photo'] ?>"
                 alt="Photo de profil">
        </div>
        <div class="col-9">
            <h1 class="h2"><?= $user['pseudo'] ?></h1>
            Membre depuis le <?= date('d-m-Y', strtotime($user['date_inscription'])) ?>
            <br><br>
            Rôle : <?= $user['account_role'] == 'admin' ? 'Administrateur' : 'Utilisateur' ?>
        </div>
        <div class="col-12 mt-30">
            <hr>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="show-tab" data-toggle="tab" href="#show" role="tab"
                       aria-controls="show" aria-selected="true">Series suivies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list"
                       aria-selected="false">Listes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="list-tab" data-toggle="tab" href="#activity" role="tab"
                       aria-controls="activity" aria-selected="false">Activités</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="stats-tab" data-toggle="tab" href="#stats" role="tab" aria-controls="list"
                       aria-selected="false">Statistiques</a>
                </li>

            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
                    <div class="row mt-20">
                        <div class="col-sm-12 col-md-4 col-lg-2 col-xl-2">
                            <label for="selectFollowedShows">Afficher : </label>
                            <select onchange="filterFollowing()" class="form-control"
                                    id="selectFollowedShows">
                                <option value="all">Tout</option>
                                <option value="watching">En cours</option>
                                <option value="completed">Terminées</option>
                                <option value="plan">A voir</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-2 col-xl-2">
                            <label for="searchFollowing">Rechercher : </label>
                            <input type="text" class="form-control" id="searchFollowing" placeholder="Recherche ..."
                                   onkeyup="searchFollowing()">
                        </div>
                        <div class="col-12 mt-20">
                            <div id="followedShows" class="row">
                                <?php
                                $shows = getMemberFollowedShow($user['pseudo']);
                                foreach ($shows as $show) { ?>
                                    <div id="<?= $show['name_show'] ?>" class="col-6 col-sm-3 col-md-4 col-lg-2 mt-20">
                                        <a href="<?= '/show?show=' . $show['id_show'] ?>" target="_blank">
                                            <div class="white-card">
                                                <img class="card-img-top" src="<?= $show['image_show'] ?>"
                                                     alt="<?= $show['name_show'] ?>">
                                                <div class="card-body">
                                                    <h5 class="text-dark card-title text-center"><?= $show['name_show'] ?></h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">Avancement
                                                        : <?php switch ($show['status_followed_show']) {
                                                            case 'en cours':
                                                                echo "en cours";
                                                                break;
                                                            case 'termine':
                                                                echo 'terminée';
                                                                break;
                                                            case 'a voir':
                                                                echo 'à voir';
                                                                break;
                                                            default:
                                                                echo 'inconnu';
                                                                break;
                                                        }
                                                        $nbepisodes = getMemberNumberWatchedEpisodesShow(getMemberByPseudo($_GET['pseudo'])['email'], $show['id_show']);
                                                        if ($nbepisodes > 0) { ?>
                                                            <br>Épisodes regardés : <?= $nbepisodes ?>
                                                            <?php
                                                        }
                                                        if ($show['mark_followed_show'] != '') {
                                                            ?><br><i style="color: orange"
                                                                     class="fas fa-star"></i><?= $show['mark_followed_show'];
                                                        } ?></h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                } ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
                    listes
                </div>
                <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                    activités
                </div>
                <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                    stats
                </div>
            </div>
        </div>
    </div>
</div>