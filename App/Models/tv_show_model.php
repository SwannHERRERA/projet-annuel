<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
require_once BASEPATH . '/conf.inc.php';

class Tv_show_model extends My_model
{
    protected $_table = 'TV_SHOW';
    protected $table_primary_key = "id_show";


    /**
     * Récupère un tableau 1 dimension contenant les colonnes de la sÃ©rie demandÃ©e
     * @param $idShow
     * @return array [id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated]
     */
    public function getTVShow($idShow)
    {
        $query = "SELECT id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated FROM flixadvisor.TV_SHOW WHERE id_show = :id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation de la serie.");
        }
        return $queryPrepared->fetch();
    }

    /**
     * Inserte tvShow with actor,network,episodes,genre or update if it already exists according to TVDB
     * @param INTEGER $id = id of TVDB
     */
    public function insertTV($id, $serie, $api, $imurl, $hard)
    {
        /* On vÃ©rifie que l'API nous renvoie bien une sÃ©rie avec l'id donnÃ© */
        if (!empty($serie)) {
            /***************************************************************************************************************
             * On vÃ©rifie si la sÃ©rie est prÃ©sente en base et sa date de derniÃ¨re mise Ã  jour
             */
            if ($hard) {
                $new = true;
            } else {
                $query = "SELECT last_updated FROM flixadvisor.TV_SHOW WHERE id_show = :id";
                $queryPrepared = $this->pdo->prepare($query);
                $queryPrepared->execute([":id" => $serie->id]);
                $result = $queryPrepared->fetch();
                if (!empty($result)) {
                    $lastUpdated = new DateTime($result["last_updated"]);
                    $APIUpdated = new DateTime();
                    $APIUpdated->setTimestamp($serie->lastUpdated);
                    $new = false;
                } else {
                    $new = true;
                }
            }
            if ($new || ($lastUpdated->diff($APIUpdated)->days < 30 && $lastUpdated->diff($APIUpdated)->days != 0)) {
                //print_r($lastUpdated->diff($APIUpdated)->days);
                //var_dump("Ajout update");
                /***************************************************************************************************************
                 * PrÃ©paration des informations sur la sÃ©rie car qu'elle existe ou pas, elle sera crÃ©Ã©e / mis Ã  jour
                 */
                $posters = $api->series_images($id, array("keyType" => "poster", "resolution" => "680x1000"));
                if (empty($posters)) {
                    $api->language("en");
                    $posters = $api->series_images($id, array("keyType" => "poster", "resolution" => "680x1000"));
                    $api->language("fr");
                }
                $image = array("filename" => $posters[0]->thumbnail, "score" => $posters[0]->ratingsInfo->average);
                foreach ($posters as $poster) {
                    if ($poster->ratingsInfo->average > $image["score"]) {
                        $image["filename"] = $poster->thumbnail;
                        $image["score"] = $poster->ratingsInfo->average;
                    }
                }

                $query = "INSERT INTO flixadvisor.TV_SHOW(id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated) " .
                    "VALUES" .
                    "(:id, :name, :status, :run_time, :first_aired, :picture, :summary, :updated)" .
                    "ON DUPLICATE KEY UPDATE " .
                    "name_show = :name, production_status = :status, runtime_show = :run_time, image_show = :picture, summary_show = :summary, last_updated = :updated";

                /**
                 * On execute la requete d'ajout / de mise Ã  jour
                 */
                $queryPrepared = $this->pdo->prepare($query);
                $queryPrepared->execute([
                    ":id" => $id,
                    ":name" => $serie->seriesName,
                    ":status" => $serie->status,
                    ":run_time" => $serie->runtime,
                    ":first_aired" => $serie->firstAired,
                    ":picture" => $imurl . $image["filename"],
                    ":summary" => $serie->overview,
                    ":updated" => date("Y-m-d H:i:s", $serie->lastUpdated)
                ]);

                /**
                 * On vÃ©rifie que la sÃ©rie est bien ajoutÃ©, prÃ©sente avant de continuer
                 */
                $queryPrepared = $this->pdo->prepare("SELECT id_show FROM flixadvisor.TV_SHOW WHERE id_show = :id");
                $queryPrepared->execute([":id" => $id]);
                if (sizeof($queryPrepared->fetchAll()) != 1)
                    die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION DE LA SERIE");

                /***************************************************************************************************************
                 * On passe Ã  l'ajout / mise Ã  jour des Ã©pisodes
                 *  $page et $i servent Ã  changer de pages de resultat sur l'API (nÃ©cÃ©ssaire quand +100 Ã©pisodes)
                 */
                $page = 1;
                do {
                    $episodes = $api->series_episodes($serie->id, $page);
                    $page++;
                    $i = 0;
                    foreach ($episodes as $episode) {
                        $i++;
                        /**
                         * On vÃ©rifie que l'episode n'appartient pas Ã  la saison 0 (episodes bonus, sert Ã  rien ...)
                         */
                        if ($episode->airedSeason == 0)
                            continue;
                        /**
                         * On ajoute la saison si elle n'existe pas encore en base
                         */
                        if ($new || ($lastUpdated->diff($APIUpdated->setTimestamp($episode->lastUpdated))->days < 30 && $lastUpdated->diff($APIUpdated->setTimestamp($episode->lastUpdated))->days != 0)) {
                            //var_dump("ajout ep");
                            $isExist = "SELECT id_season FROM flixadvisor.SEASON WHERE tv_show = :id AND nb_season = :number";
                            $queryPrepared = $this->pdo->prepare($isExist);
                            $queryPrepared->execute([
                                ":id" => $id,
                                ":number" => $episode->airedSeason]);
                            if (sizeof($queryPrepared->fetchAll()) == 0) {
                                echo $episode->airedSeason;
                                $query = "INSERT INTO flixadvisor.SEASON (nb_season, tv_show) VALUES (:number, :serie_id)";
                                $queryPrepared = $this->pdo->prepare($query);
                                $queryPrepared->execute([
                                    ":number" => $episode->airedSeason,
                                    ":serie_id" => $id
                                ]);
                                if ($queryPrepared->errorCode() != '00000')
                                    die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UNE SAISON " . $queryPrepared->errorCode());
                            }
                        }
                        /**
                         * On ajoute / met Ã  jour l'episode en base
                         */
                        if ($new) {
                            $query = "insert into flixadvisor.EPISODE (id_episode, nb_episode, name_episode, first_aired_episode, director_episode, author_episode, summary_episode, season) " .
                                "VALUES " .
                                "(:id, :number, :name, :first_aired, :director, :author, :summary,(SELECT id_season FROM flixadvisor.SEASON " .
                                "WHERE tv_show = :serie_id AND nb_season = :season_number)) " .
                                "ON DUPLICATE KEY UPDATE name_episode = :name, director_episode = :director, author_episode = :author, summary_episode = :summary ";
                            $queryPrepared = $this->pdo->prepare($query);
                            $queryPrepared->execute([
                                ":id" => $episode->id,
                                ":number" => $episode->airedEpisodeNumber,
                                ":name" => $episode->episodeName,
                                ":first_aired" => $episode->firstAired,
                                ":director" => $episode->director,
                                ":author" => ((sizeof($episode->writers) == 0) ? '' : $episode->writers[0]),
                                ":serie_id" => $id,
                                ":season_number" => $episode->airedSeason,
                                ":summary" => $episode->overview
                            ]);
                            if ($queryPrepared->errorCode() != '00000') {
                                //var_dump($queryPrepared->errorInfo());
                                die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN EPISODE " . $queryPrepared->errorCode());
                            }
                        } elseif ($lastUpdated->diff($APIUpdated->setTimestamp($episode->lastUpdated))->days < 30 && $lastUpdated->diff($APIUpdated->setTimestamp($episode->lastUpdated))->days != 0) {
                            //var_dump("up ep");
                            $query = "UPDATE flixadvisor.EPISODE SET name_episode = :name, director_episode = :director, author_episode = :author, summary_episode = :summary " .
                                "WHERE id_episode = :id";
                            $queryPrepared = $this->pdo->prepare($query);
                            $queryPrepared->execute([
                                ":id" => $episode->id,
                                ":name" => $episode->episodeName,
                                ":director" => $episode->director,
                                ":author" => ((sizeof($episode->writers) == 0) ? '' : $episode->writers[0]),
                                ":summary" => $episode->overview
                            ]);
                            if ($queryPrepared->errorCode() != '00000') {
                                //var_dump($queryPrepared->errorInfo());
                                die("UNE ERREUR EST SURVENUE PENDANT LA MISE A JOUR D'UN EPISODE " . $queryPrepared->errorCode());
                            } else {
                                //var_dump("rien");
                                continue;
                            }
                        }
                    }
                } while ($i >= 99);

                /***************************************************************************************************************
                 * On passe aux acteurs !
                 * On rÃ©cupÃ¨re dans un premier temps la liste des acteurs
                 */
                $actors = $api->series_actors($id);

                foreach ($actors as $actor) {
                    /**
                     * On insÃ¨re l'acteur s'il n'existe pas dÃ©jÃ 
                     */
                    $query = "INSERT IGNORE INTO flixadvisor.ACTOR (id_actor, name_actor) VALUES (:id, :name)";
                    $queryPrepared = $this->pdo->prepare($query);
                    $queryPrepared->execute([
                        ":id" => $actor->id,
                        ":name" => $actor->name
                    ]);
                    if ($queryPrepared->errorCode() != '00000')
                        die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN ACTEUR " . $queryPrepared->errorCode());

                    /**
                     * On ajoute / met Ã  jour le casting de l'acteur
                     */
                    $query = "INSERT INTO flixadvisor.CASTING (tv_show, actor, role_actor, photo_actor) " .
                        "VALUES (:id, :actor_id, :role, :picture) " .
                        "ON DUPLICATE KEY UPDATE " .
                        "role_actor = :role, photo_actor = :picture";

                    $queryPrepared = $this->pdo->prepare($query);
                    $queryPrepared->execute([
                        ":id" => $id,
                        ":actor_id" => $actor->id,
                        ":role" => $actor->role,
                        ":picture" => ($actor->image == '') ? '' : $imurl . $actor->image
                    ]);
                    if ($queryPrepared->errorCode() != '00000')
                        die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN CASTING");
                }

                /***************************************************************************************************************
                 * On passe au rÃ©seau de diffusion !
                 * On vÃ©rifie si le reseau existe dÃ©jÃ , auquel cas on l'ajoute
                 */
                $isExist = "SELECT id_network FROM flixadvisor.NETWORK WHERE name_network = :name";
                $queryPrepared = $this->pdo->prepare($isExist);
                $queryPrepared->execute([":name" => $serie->network]);
                if (sizeof($queryPrepared->fetchAll()) == 0) {
                    $query = "INSERT IGNORE INTO flixadvisor.NETWORK (name_network) value (:name)";
                    $queryPrepared = $this->pdo->prepare($query);
                    $queryPrepared->execute([":name" => $serie->network]);
                    if ($queryPrepared->errorCode() != '00000')
                        die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN RESEAU");
                }
                /**
                 * On vÃ©rifie si la liaison reseau/serie existe, et on ajoute si no existante
                 */

                if ($serie->network != "") {
                    $query = "INSERT IGNORE INTO flixadvisor.BROADCAST (tv_show, network) VALUES (:id, (SELECT id_network FROM flixadvisor.NETWORK WHERE name_network = :name))";
                    $queryPrepared = $this->pdo->prepare($query);
                    $queryPrepared->execute([
                        ":id" => $id,
                        ":name" => $serie->network
                    ]);
                    if ($queryPrepared->errorCode() != '00000')
                        die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UNE DIFFUSION");

                }


                /***************************************************************************************************************
                 * On passe Ã  l'ajout des genres
                 * On vÃ©rifie si le genre existe dÃ©jÃ , sinon on l'ajoute
                 * Pareil pour le hodor
                 */

                foreach ($serie->genre as $genre) {
                    $isExist = "SELECT id_category FROM flixadvisor.CATEGORY where name_category = :name";
                    $queryPrepared = $this->pdo->prepare($isExist);
                    $queryPrepared->execute([":name" => $genre]);
                    if (sizeof($queryPrepared->fetchAll()) == 0) {
                        $query = "INSERT INTO flixadvisor.CATEGORY (name_category) value (:name)";
                        $queryPrepared = $this->pdo->prepare($query);
                        $queryPrepared->execute([":name" => $genre]);
                        if ($queryPrepared->errorCode() != '00000')
                            die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN GENRE" . $queryPrepared->errorCode());
                    }

                    $query = "INSERT IGNORE INTO flixadvisor.CATEGORIZED_SHOW (tv_show, category) VALUES (:id, (SELECT id_category FROM flixadvisor.CATEGORY WHERE name_category = :name))";
                    $queryPrepared = $this->pdo->prepare($query);
                    $queryPrepared->execute([
                        ":id" => $id,
                        ":name" => $genre
                    ]);
                    if ($queryPrepared->errorCode() != '00000')
                        die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN HODOR");
                }
            }
            header("Location: " . $site_url . "/back/tv_show/add");
        } else
            die('L\'id est incorrect');
    }

    /**
     * @param INTEGER id = id of the TV_show
     * @return ARRAY with image of the TV_show
     */
    public function getImage($id)
    {
        $query = "SELECT image_show FROM flixadvisor.TV_SHOW WHERE id_show = :id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $id]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recherche d'image.");
        }
        return $queryPrepared->fetch()[0];
    }

    public function getName($id)
    {
        $query = "SELECT name_show FROM flixadvisor.TV_SHOW WHERE id_show = :id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $id]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recherche de nom.");
        }
        return $queryPrepared->fetch()[0];
    }

    /**
     * DEELTE TV_SHOW and foreignKey
     * @param INTEGER id of TV_SHOW
     */
    public function removeTVShow($id)
    {
        $query = "";
        $query .= "DELETE FROM flixadvisor.WATCHED_EPISODES WHERE episode IN (SELECT id_episode from flixadvisor.EPISODE WHERE season IN (SELECT id_season from flixadvisor.SEASON where tv_show = :id)); ";
        $query .= "delete from flixadvisor.EPISODE WHERE season IN (SELECT id_season from flixadvisor.SEASON where tv_show = :id); ";
        $query .= "delete from flixadvisor.SEASON where tv_show = :id; ";
        $query .= "delete from flixadvisor.CATEGORIZED_SHOW where tv_show = :id; ";
        $query .= "delete from flixadvisor.BROADCAST where tv_show = :id; ";
        $query .= "delete from flixadvisor.CASTING where tv_show = :id; ";
        $query .= "delete from flixadvisor.IN_LIST where tv_show = :id; ";
        $query .= "delete from flixadvisor.FOLLOWED_SHOW where tv_show = :id; ";
        $query .= "delete from flixadvisor.VOTED_RECO where recommendation IN (select id_reco from flixadvisor.RECOMMENDATION where hosting_show = :id OR recommended_show = :id); ";
        $query .= "delete from flixadvisor.RECOMMENDATION where hosting_show = :id OR recommended_show = :id; ";
        $query .= "delete from flixadvisor.REPORTED_COMMENT where comment IN (select id_comment from flixadvisor.COMMENT where tv_show = :id); ";
        $query .= "delete from flixadvisor.LIKED_COMMENT where comment IN (select id_comment from flixadvisor.COMMENT where tv_show = :id); ";
        $query .= "delete from flixadvisor.REPORTED_REPLY where reply IN (select id_reply from flixadvisor.REPLY where comment IN (select id_comment from flixadvisor.COMMENT where tv_show = :id)); ";
        $query .= "delete from flixadvisor.REPLY where comment IN (select id_comment from flixadvisor.COMMENT where tv_show = :id); ";
        $query .= "delete from flixadvisor.COMMENT where tv_show = :id; ";
        $query .= "delete from flixadvisor.TV_SHOW where id_show = :id;";

        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $id]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de la suppression de la sÃ©rie");
        }
    }

    /**
     * A tester quand on aura du contenu !
     */
    public function getTVShowRecommendations($idShow)
    {
        $query = "SELECT id_reco , text_reco, date_reco, recommended_show, hosting_show, member, (SELECT COUNT(*) FROM flixadvisor.VOTED_RECO WHERE recommendation = id_reco) AS nbVotes  FROM flixadvisor.RECOMMENDATION WHERE hosting_show = :id ORDER BY nbVotes DESC ";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation des recommendations de la serie.");
        }

        return $queryPrepared->fetchAll();
    }

    /**
     * Retourne la moyenne de la sÃ©rie (score)
     * @param $idShow
     * @return integer
     */
    public function getShowLastAiringDate($idShow)
    {
        $query = "SELECT max(first_aired_episode) FROM EPISODE WHERE season IN (SELECT id_season FROM SEASON WHERE tv_show = :id)";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation de la derniere date de diffusion de la sÃ©rie.");
        }
        return $queryPrepared->fetch()[0];
    }

    public function getShowScore($idShow)
    {
        $query = "SELECT CAST(AVG(mark_followed_show) AS DECIMAL(10,2)) FROM flixadvisor.FOLLOWED_SHOW WHERE tv_show = :id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation du score de la sÃ©rie.");
        }

        return $queryPrepared->fetch()[0];
    }

    /**
     * RÃ©cupÃ¨re la liste des sÃ©ries existantes en BDD par ordre alphabÃ©tique dans un tableau Ã  2 dimensions
     * @return array [0 => [id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated], 1 => ...]
     */
    public function getTVShowList()
    {
        $query = "SELECT id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated FROM flixadvisor.TV_SHOW ORDER BY name_show";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recupÃ©ration des series");
        }
        return $queryPrepared->fetchAll();
    }

    public function getTVYearStatusStat()
    {
        $query = "SELECT count(*) as nb_show, YEAR(first_aired_show) as year, (SELECT count(*) from TV_SHOW where YEAR(first_aired_show) = year AND production_status = 'Ended') as ended, (SELECT count(*) from TV_SHOW where YEAR(first_aired_show) = year AND production_status = 'Continuing') as continuing from TV_SHOW group by YEAR(first_aired_show)";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la rÃ©cupÃ©ration des stats des stats d'annÃ©e de diffusion des sÃ©ries.");
        }
        return $queryPrepared->fetchAll();
    }

    public function get_last_updated($id)
    {
        $query = "SELECT last_updated FROM flixadvisor.TV_SHOW WHERE id_show = :id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $id]);
        $result = $queryPrepared->fetch()[0];
        return $result;
    }
}
