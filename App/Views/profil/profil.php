<script src="<?= $site_url . '/js/profil.js' ?>"></script>
<div class="col-md-9 col-lg-10 align-self">
    <div class="row">
        <div class="col-sm-3 my-20">
            <!--<img class="img-fluid" src="<?= $memberProfil['photo'] ?>" alt="Photo de profil">-->
            <img class="img-fluid"
                 src="<?= $memberProfil['photo'] ?>"
                 alt="Photo de profil">
        </div>
        <div class="col-9">
            <h1 class="h2"><?= $memberProfil['pseudo'] ?></h1>
            <p>Membre depuis le <?= date('d-m-Y', strtotime($memberProfil['date_inscription'])) ?>
                <br><br>
                Rôle : <?= $memberProfil['account_role'] == 'admin' ? 'Administrateur' : 'Utilisateur' ?>
            <?php
            if (isset($_SESSION['email']) && $memberProfil['email'] != $_SESSION['email']) {
                ?>
                <br><br>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendMessage">
                    <i
                            class="fas fa-envelope"></i>
                </button>
                <div class="modal fade" id="sendMessage" tabindex="-1" role="dialog"
                     aria-labelledby="sendMessage" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Envoyer un message</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= '/profil/sendMessage' ?>" method="post">
                                    <input type="hidden" name="pseudo" value="<?=$memberProfil['pseudo']?>">
                                    <label for="message">Message :</label>
                                    <textarea class="form-control" name="message" id="message"></textarea>
                                    <button type="submit" class="btn btn-primary mt-20">Envoyer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
                ?>
            </p>
        </div>
        <div class="col-12">
            <hr>
            <ul class="nav nav-tabs mb-30" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="show-tab" data-toggle="tab" href="#show" role="tab"
                       aria-controls="show" aria-selected="true">Series suivies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list"
                       aria-selected="false">Listes</a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" id="list-tab" data-toggle="tab" href="#activity" role="tab"
                       aria-controls="activity" aria-selected="false">Activités</a>
                </li>-->
                <li class="nav-item">
                    <a class="nav-link" id="stats-tab" data-toggle="tab" href="#stats" role="tab" aria-controls="list"
                       aria-selected="false">Statistiques</a>
                </li>

            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="selectFollowedShows">Afficher : </label>
                                <select onchange="filterFollowing()" class="form-control" id="selectFollowedShows">
                                    <option value="all">Tout</option>
                                    <option value="watching">En cours</option>
                                    <option value="completed">Terminées</option>
                                    <option value="plan">A voir</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="searchFollowing">Rechercher : </label>
                                <input type="text" class="form-control" id="searchFollowing" placeholder="Recherche ..."
                                       onkeyup="searchFollowing()">
                            </div>
                        </div>
                        <div class="col-12">
                            <div id="followedShows" class="row">
                                <?php
                                $shows = getMemberFollowedShow($memberProfil['pseudo']);
                                foreach ($shows as $show) { ?>
                                    <div id="<?= $show['name_show'] ?>"
                                         class="col-6 col-sm-3 col-md-4 col-lg-3 mb-20 followedShow">
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
                                                        $nbepisodes = getMemberNumberWatchedEpisodesShow($memberProfil['email'], $show['id_show']);
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
                    <div class="row mb-20">
                        <div class="col-12">
                            <h5 class="h4 mb-20">Listes de <?= ucfirst($memberProfil['pseudo']) ?> :</h5>
                            <table class="table table-striped table-dark align-self-center mb-20">
                                <thead>
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Visibilité</th>
                                    <th scope="col">Supprimer</th>
                                </tr>
                                </thead>
                                <tbody id="lists">

                                <?php
                                if (isset($_SESSION['email'])) {
                                    $user = getMember($_SESSION['email']);

                                }
                                foreach (getMemberLists($memberProfil['email']) as $list) { ?>
                                <tr id="list<?= $list['id_list'] ?>">
                                    <th scope="row"><a
                                                href="<?= $site_url . '/profil/profilList?list=' . $list['id_list'] ?>"><?= $list['name_list'] ?></a>
                                    </th>
                                    <td><?= substr($list['description_list'], 0, 20) . (strlen($list['description_list']) > 20 ? '...' : '') ?></td>
                                    <td><?= $list['visibility_list'] == 'public' ? 'publique' : 'privée' ?></td>
                                    <td>
                                    <?php
                                    if (isset($_SESSION['email'])) {
                                        if ($user['email'] === $memberProfil['email'] || $user['account_status'] == 'admin') {
                                            ?>
                                            <button onclick="removeList(<?= $list['id_list'] ?>)"
                                                    class="btn btn-warning"><i
                                                        class="fas fa-trash"></i>
                                            </button>
                                            </td>
                                            </tr>
                                        <?php }
                                    }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (isset($_SESSION['email'])) {
                            if ($user['email'] === $memberProfil['email'] || $user['account_status'] == 'admin') {
                                ?>
                                <div id="newList" class="col-12 align-self-center mb-20">
                                    <h5 class="h4">Créer une liste</h5>
                                    <div class="row align-items-end">
                                        <div class="col-sm-3">
                                            <label for="nameListNew">Nom de la liste</label>
                                            <input type="text" class="form-control" id="nameListNew"
                                                   placeholder="Nom">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="descriptionListNew">Description de la
                                                liste</label>
                                            <input type="text" class="form-control"
                                                   id="descriptionListNew"
                                                   placeholder="Description">

                                        </div>
                                        <div class="col-sm-3">
                                            <label for="visibilityNewList">Visibilité</label>
                                            <select class="form-control" id="visibilityNewList">
                                                <option value="public">Publique</option>
                                                <option value="private">Privée</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <button onclick="addList(<?= $list['id_list'] ?>)"
                                                    class="btn btn-primary">
                                                Ajouter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                    activités
                </div>
                <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="h4 mb-20">Séries suivies :</h3>
                            <table class="table table-striped table-bordered text-center mb-20">
                                <thead>
                                <tr>
                                    <th scope="col">A voir</th>
                                    <th scope="col">En cours</th>
                                    <th scope="col">Terminés</th>
                                    <th scope="col">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= isset(array_count_values(array_column($shows, 'status_followed_show'))['a voir']) ? array_count_values(array_column($shows, 'status_followed_show'))['a voir'] : "0" ?></td>
                                    <td><?= isset(array_count_values(array_column($shows, 'status_followed_show'))['en cours']) ? array_count_values(array_column($shows, 'status_followed_show'))['en cours'] : "0" ?></td>
                                    <td><?= isset(array_count_values(array_column($shows, 'status_followed_show'))['termine']) ? array_count_values(array_column($shows, 'status_followed_show'))['termine'] : "0" ?></td>
                                    <td><?= sizeof($shows) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h4 mb-20">Statistiques épisodes regardés</h3>
                            <table class="table table-striped table-bordered text-center mb-20">
                                <thead>
                                <tr>
                                    <th scope="col">Episodes vus</th>
                                    <th scope="col">Temps passé</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= getMemberNumberWatchedEpisodes($memberProfil['email']) ?></td>
                                    <td><?php
                                        $runtime = getMemberTimeWatchedEpisodesShow($memberProfil['email']);
                                        $time = 0;
                                        foreach ($runtime as $run) {
                                            $time += $run['nb'] * $run['runtime_show'];
                                        }
                                        function convert_seconds($seconds)
                                        {
                                            $dt1 = new DateTime("@0");
                                            $dt2 = new DateTime("@$seconds");
                                            return $dt1->diff($dt2)->format('%a jours, %h heures et %i minutes');
                                        }

                                        echo convert_seconds($time * 60);
                                        ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h4 mb-20">Catégories préférées :</h3>
                            <table class="table table-striped table-bordered text-center mb-20">
                                <thead>
                                <tr>
                                    <th scope="col">Catégorie</th>
                                    <th scope="col">Occurence</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php
                                    $categories = getMemberCategoryRate($memberProfil['email']);
                                    foreach ($categories as $category) {
                                        echo '<tr>';
                                        echo '<td>' . $category['name_category'] . '</td>';
                                        echo '<td>' . $category['nb'] . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
