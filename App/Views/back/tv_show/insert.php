<?php
require BASEPATH . '/vendor/autoload.php';

use RestAPI\TheTvdbApi;

require_once BASEPATH . 'conf.inc.php';

if (array_key_exists('insert', $_POST)) {
    if (array_key_exists('hard', $_POST)) {
        insertTV($_POST['insert'], $_POST['hard']);
    } else
        insertTV($_POST['insert'], true);
} else {
    echo 'Pas d`\'id renseigné';
}
function insertTV($id, $hard)
{
    $pdo = connectDB();
    $apikey = "PU7E1HBXTFB2K1UL";
    $imurl = "https://www.thetvdb.com/banners/";
    $api = new TheTvdbApi ($apikey, "", "");
    $api->authenticate();
    $serie = $api->series($id);
    /**
     * On vérifie que l'API nous renvoie bien une série avec l'id donné
     */
    if (!empty($serie)) {
        /***************************************************************************************************************
         * On vérifie si la série est présente en base et sa date de dernière mise à jour
         */
        if ($hard) {
            $new = true;
        } else {
            $query = "SELECT last_updated FROM flixadvisor.TV_SHOW WHERE id_show = :id";
            $queryPrepared = $pdo->prepare($query);
            $queryPrepared->execute([":id" => $serie->id]);
            $result = $queryPrepared->fetch()[0];
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
            print_r($lastUpdated->diff($APIUpdated)->days);
            //var_dump("Ajout update");
            /***************************************************************************************************************
             * Préparation des informations sur la série car qu'elle existe ou pas, elle sera créée / mis à jour
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
             * On execute la requete d'ajout / de mise à jour
             */
            $queryPrepared = $pdo->prepare($query);
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
             * On vérifie que la série est bien ajouté, présente avant de continuer
             */
            $queryPrepared = $pdo->prepare("SELECT id_show FROM flixadvisor.TV_SHOW WHERE id_show = :id");
            $queryPrepared->execute([":id" => $id]);
            if (sizeof($queryPrepared->fetchAll()) != 1)
                die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION DE LA SERIE");

            /***************************************************************************************************************
             * On passe à l'ajout / mise à jour des épisodes
             *  $page et $i servent à changer de pages de resultat sur l'API (nécéssaire quand +100 épisodes)
             */
            $page = 1;
            do {
                $episodes = $api->series_episodes($serie->id, $page);
                $page++;
                $i = 0;
                foreach ($episodes as $episode) {
                    $i++;
                    /**
                     * On vérifie que l'episode n'appartient pas à la saison 0 (episodes bonus, sert à rien ...)
                     */
                    if ($episode->airedSeason == 0)
                        continue;
                    /**
                     * On ajoute la saison si elle n'existe pas encore en base
                     */
                    if ($new || ($lastUpdated->diff($APIUpdated->setTimestamp($episode->lastUpdated))->days < 30 && $lastUpdated->diff($APIUpdated->setTimestamp($episode->lastUpdated))->days != 0)) {
                        //var_dump("ajout ep");
                        $isExist = "SELECT id_season FROM flixadvisor.SEASON WHERE tv_show = :id AND nb_season = :number";
                        $queryPrepared = $pdo->prepare($isExist);
                        $queryPrepared->execute([
                            ":id" => $id,
                            ":number" => $episode->airedSeason]);
                        if (sizeof($queryPrepared->fetchAll()) == 0) {
                            echo $episode->airedSeason;
                            $query = "INSERT INTO flixadvisor.SEASON (nb_season, tv_show) VALUES (:number, :serie_id)";
                            $queryPrepared = $pdo->prepare($query);
                            $queryPrepared->execute([
                                ":number" => $episode->airedSeason,
                                ":serie_id" => $id
                            ]);
                            if ($queryPrepared->errorCode() != '00000')
                                die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UNE SAISON " . $queryPrepared->errorCode());
                        }
                    }
                    /**
                     * On ajoute / met à jour l'episode en base
                     */
                    if ($new) {
                        $query = "insert into flixadvisor.EPISODE (id_episode, nb_episode, name_episode, first_aired_episode, director_episode, author_episode, summary_episode, season) " .
                            "VALUES " .
                            "(:id, :number, :name, :first_aired, :director, :author, :summary,(SELECT id_season FROM flixadvisor.SEASON " .
                            "WHERE tv_show = :serie_id AND nb_season = :season_number)) " .
                            "ON DUPLICATE KEY UPDATE name_episode = :name, director_episode = :director, author_episode = :author, summary_episode = :summary ";
                        $queryPrepared = $pdo->prepare($query);
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
                        $queryPrepared = $pdo->prepare($query);
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
             * On récupère dans un premier temps la liste des acteurs
             */
            $actors = $api->series_actors($id);

            foreach ($actors as $actor) {
                /**
                 * On insère l'acteur s'il n'existe pas déjà
                 */
                $query = "INSERT IGNORE INTO flixadvisor.ACTOR (id_actor, name_actor) VALUES (:id, :name)";
                $queryPrepared = $pdo->prepare($query);
                $queryPrepared->execute([
                    ":id" => $actor->id,
                    ":name" => $actor->name
                ]);
                if ($queryPrepared->errorCode() != '00000')
                    die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN ACTEUR " . $queryPrepared->errorCode());

                /**
                 * On ajoute / met à jour le casting de l'acteur
                 */
                $query = "INSERT INTO flixadvisor.CASTING (tv_show, actor, role_actor, photo_actor) " .
                    "VALUES (:id, :actor_id, :role, :picture) " .
                    "ON DUPLICATE KEY UPDATE " .
                    "role_actor = :role, photo_actor = :picture";

                $queryPrepared = $pdo->prepare($query);
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
             * On passe au réseau de diffusion !
             * On vérifie si le reseau existe déjà, auquel cas on l'ajoute
             */
            $isExist = "SELECT id_network FROM flixadvisor.NETWORK WHERE name_network = :name";
            $queryPrepared = $pdo->prepare($isExist);
            $queryPrepared->execute([":name" => $serie->network]);
            if (sizeof($queryPrepared->fetchAll()) == 0) {
                $query = "INSERT IGNORE INTO flixadvisor.NETWORK (name_network) value (:name)";
                $queryPrepared = $pdo->prepare($query);
                $queryPrepared->execute([":name" => $serie->network]);
                if ($queryPrepared->errorCode() != '00000')
                    die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN RESEAU");
            }
            /**
             * On vérifie si la liaison reseau/serie existe, et on ajoute si no existante
             */

            if ($serie->network != "") {
                $query = "INSERT IGNORE INTO flixadvisor.BROADCAST (tv_show, network) VALUES (:id, (SELECT id_network FROM flixadvisor.NETWORK WHERE name_network = :name))";
                $queryPrepared = $pdo->prepare($query);
                $queryPrepared->execute([
                    ":id" => $id,
                    ":name" => $serie->network
                ]);
                if ($queryPrepared->errorCode() != '00000')
                    die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UNE DIFFUSION");

            }


            /***************************************************************************************************************
             * On passe à l'ajout des genres
             * On vérifie si le genre existe déjà, sinon on l'ajoute
             * Pareil pour le hodor
             */

            foreach ($serie->genre as $genre) {
                $isExist = "SELECT id_category FROM flixadvisor.CATEGORY where name_category = :name";
                $queryPrepared = $pdo->prepare($isExist);
                $queryPrepared->execute([":name" => $genre]);
                if (sizeof($queryPrepared->fetchAll()) == 0) {
                    $query = "INSERT INTO flixadvisor.CATEGORY (name_category) value (:name)";
                    $queryPrepared = $pdo->prepare($query);
                    $queryPrepared->execute([":name" => $genre]);
                    if ($queryPrepared->errorCode() != '00000')
                        die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN GENRE" . $queryPrepared->errorCode());
                }

                $query = "INSERT IGNORE INTO flixadvisor.CATEGORIZED_SHOW (tv_show, category) VALUES (:id, (SELECT id_category FROM flixadvisor.CATEGORY WHERE name_category = :name))";
                $queryPrepared = $pdo->prepare($query);
                $queryPrepared->execute([
                    ":id" => $id,
                    ":name" => $genre
                ]);
                if ($queryPrepared->errorCode() != '00000')
                    die("UNE ERREUR EST SURVENUE PENDANT L'INSERTION D'UN HODOR");
            }
        }
        header("Location: /Thetvdb/index.php");
    } else
        die('L\'id est incorrect');
}


function connectDB()
{
    try {
        $pdo = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
    } catch (Exception $e) {
        die("Erreur SQL : " . $e->getMessage());
    }

    return $pdo;
}