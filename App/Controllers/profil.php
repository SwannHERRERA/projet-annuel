<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';

class Profil extends Controller
{
    private $member_model;

    public function __construct()
    {
        require_once BASEPATH . '/Core/functions.php';
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        parent::__construct(__CLASS__);
    }

    public function index()
    {
        if (isset($_GET['user'])) {
            $user = getMemberByPseudo($_GET['user']);
            if ($user) {
                require self::VIEW_PATH . 'layout/header.php';
                require self::VIEW_PATH . 'page/profil.php';
                require self::VIEW_PATH . 'layout/footer.php';
            } else
                header('Location: /');
        } else
            header('Location: /');
    }

    public function getMemberFollowedShow()
    {
        if (isset($_GET['pseudo'])) {
            $shows = getMemberFollowedShow($_GET['pseudo']);
            foreach ($shows as $show) { ?>
                <div id="<?= $show['name_show'] ?>" class="col-6 col-sm-3 col-md-4 col-lg-2 mt-20">
                    <a href="<?= $site_url . '/show?show=' . $show['id_show'] ?>" target="_blank">
                        <div class="white-card">
                            <img class="card-img-top" src="<?= $show['image_show'] ?>" alt="<?= $show['name_show'] ?>">
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
            }
        } else
            echo 'Mauvaise requete';
    }

    public function getMemberWatchingShow()
    {
        if (isset($_GET['pseudo'])) {
            $shows = getMemberWatchingShow($_GET['pseudo']);
            foreach ($shows as $show) { ?>
                <div id="<?= $show['name_show'] ?>" class="col-6 col-sm-3 col-md-4 col-lg-2 mt-20">
                    <a href="<?= $site_url . '/show?show=' . $show['id_show'] ?>" target="_blank">
                        <div class="white-card">
                            <img class="card-img-top" src="<?= $show['image_show'] ?>" alt="<?= $show['name_show'] ?>">
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
            }
        } else
            echo 'Mauvaise requete';
    }

    public function getMemberCompletedShow()
    {
        if (isset($_GET['pseudo'])) {
            $shows = getMemberCompletedShow($_GET['pseudo']);
            foreach ($shows as $show) { ?>
                <div id="<?= $show['name_show'] ?>" class="col-6 col-sm-3 col-md-4 col-lg-2 mt-20">
                    <a href="<?= $site_url . '/show?show=' . $show['id_show'] ?>" target="_blank">
                        <div class="white-card">
                            <img class="card-img-top" src="<?= $show['image_show'] ?>" alt="<?= $show['name_show'] ?>">
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
            }
        } else
            echo 'Mauvaise requete';
    }

    public function getMemberPlanToWatchShow()
    {
        if (isset($_GET['pseudo'])) {
            $shows = getMemberPlanToWatchShow($_GET['pseudo']);
            foreach ($shows as $show) { ?>
                <div id="<?= $show['name_show'] ?>" class="col-6 col-sm-3 col-md-4 col-lg-2 mt-20">
                    <a href="<?= $site_url . '/show?show=' . $show['id_show'] ?>" target="_blank">
                        <div class="white-card">
                            <img class="card-img-top" src="<?= $show['image_show'] ?>" alt="<?= $show['name_show'] ?>">
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
            }
        } else
            echo 'Mauvaise requete';
    }
}
