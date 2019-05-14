<?php
require_once BASEPATH . '/conf.inc.php';

/**
 * @return PDO
 */
function connectDB()
{
    try {
        $pdo = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
    } catch (Exception $e) {
        die("Erreur SQL : " . $e->getMessage());
    }

    return $pdo;
}

/**
 * Retire la série et tout ce qui lui est associée
 * @param $idShow
 */
function removeTVShow($idShow)
{
    $pdo = connectDB();
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

    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de la suppression de la série");
    }
}

/**
 * Retire l'acteur et tout ce qui lui est associé
 * @param $idActor
 */
function removeActor($idActor)
{
    $pdo = connectDB();
    $query = "";
    $query .= "DELETE FROM flixadvisor.CASTING where actor = :id; ";
    $query .= "DELETE FROM flixadvisor.ACTOR WHERE id_actor = :id;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idActor]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de la suppression de l'acteur");
    }
}

/**
 * Ajoute un acteur à la BDD
 * @param $idActor
 * @param $nameActor
 */
function addActor($idActor, $nameActor)
{
    $pdo = connectDB();
    $query = "insert ignore into flixadvisor.ACTOR (id_actor, name_actor) VALUES (:id, :name);";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":id" => $idActor,
        ":name" => $nameActor]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de l'ajout de l'acteur");
    }
}

/**
 * @param $idActor
 * @param $nameActor
 */
function updateActor($idActor, $nameActor)
{
    $pdo = connectDB();
    $query = "UPDATE flixadvisor.ACTOR set name_actor = :name where id_actor = :id;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":id" => $idActor,
        ":name" => $nameActor]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de la maj de l'acteur");
    }
}

/**
 * Met à jour un acteur existant en BDD
 * @param $idShow
 * @param $idActor
 * @param $roleActor
 * @param $photoActor
 */
function addCasting($idShow, $idActor, $roleActor, $photoActor)
{
    $pdo = connectDB();
    $query = "insert into flixadvisor.CASTING (tv_show, actor, role_actor, photo_actor) VALUES (:tv, :actor, :role, :photo) " .
        "ON DUPLICATE KEY UPDATE role_actor = :role, photo_actor = :photo";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":tv" => $idShow,
        ":actor" => $idActor,
        ":role" => $roleActor,
        ":photo" => $photoActor]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de l'ajout du casting");
    }
}

/**
 * Ajoute un casting entre un acteur et une série existants
 * @param $idShow
 * @param $idActor
 */
function removeCasting($idShow, $idActor)
{
    $pdo = connectDB();
    $query = "DELETE FROM flixadvisor.CASTING WHERE actor = :actor AND tv_show = :tv";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":tv" => $idShow,
        ":actor" => $idActor]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de la suppression du casting");
    }
}

/**
 * Retire un casting entre un acteur et une série
 * @param $idCategory
 */
function removeCategory($idCategory)
{
    $pdo = connectDB();
    $query = "";
    $query .= "DELETE FROM flixadvisor.CATEGORIZED_SHOW where category = :id; ";
    $query .= "DELETE FROM flixadvisor.CATEGORY WHERE id_category = :id;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idCategory]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de la suppression de la categorie");
    }
}

/**
 * Ajoute une catégorie en BDD
 * @param $idCategory
 * @param $nameCategory
 */
function addCategory($idCategory, $nameCategory)
{
    $pdo = connectDB();
    $query = "insert ignore into flixadvisor.CATEGORY (id_category, name_category) VALUES (:id, :name);";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":id" => $idCategory,
        ":name" => $nameCategory]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de l'ajout de la categorie");
    }
}

/**
 * Met à jour une catégorie existante
 * @param $idCategory
 * @param $nameCategory
 */
function updateCategory($idCategory, $nameCategory)
{
    $pdo = connectDB();
    $query = "UPDATE flixadvisor.CATEGORY set name_category = :name where id_category = :id;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":id" => $idCategory,
        ":name" => $nameCategory]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de la maj de la categorie");
    }
}

/**
 * Ajoute une catégorie à une série
 * @param $idShow
 * @param $idCategory
 */
function addCategorizedShow($idShow, $idCategory)
{
    $pdo = connectDB();
    $query = "insert ignore into flixadvisor.CATEGORIZED_SHOW (category, tv_show) VALUES (:category, :tv);";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":tv" => $idShow,
        ":category" => $idCategory]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de l'ajout du hodor");
    }
}

/**
 * Enlève une catégorie à une série
 * @param $idShow
 * @param $idCategory
 */
function removeCategorizedShow($idShow, $idCategory)
{
    $pdo = connectDB();
    $query = "DELETE FROM flixadvisor.CATEGORIZED_SHOW WHERE tv_show = :tv AND category = :category";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":tv" => $idShow,
        ":category" => $idCategory]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("une erreur est survenu lors de la suppression du hodor");
    }
}

/**
 * Récupère l'url de l'image de la série
 * @param $idShow
 * @return string
 */
function getTVShowImage($idShow)
{
    $pdo = connectDB();
    $query = "SELECT image_show FROM flixadvisor.TV_SHOW WHERE id_show = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recherche d'image.");
    }
    return $queryPrepared->fetch()[0];
}

/**
 * Récupère un tableau 1 dimension contenant les colonnes de la série demandée
 * @param $idShow
 * @return array [id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated]
 */
function getTVShow($idShow)
{
    $pdo = connectDB();
    $query = "SELECT id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated FROM flixadvisor.TV_SHOW WHERE id_show = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation de la serie.");
    }
    return $queryPrepared->fetch();
}


/**
 * Récupère le nombre d'épisodes d'une série
 * @param INTEGER $idShow
 * @return integer
 */
function getTVNumberEpisodes($idShow)
{
    $pdo = connectDB();
    $query = "SELECT count(id_episode) FROM flixadvisor.EPISODE,flixadvisor.SEASON WHERE EPISODE.season = SEASON.id_season AND SEASON.tv_show = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du nombre d'episodes de la serie.");
    }
    return $queryPrepared->fetch()[0];
}

/**
 * Récupère le nombre de saisons d'une série
 * @param INTEGER $idShow
 * @return integer
 */
function getTVNumberSeasons($idShow)
{
    $pdo = connectDB();
    $query = "SELECT max(nb_season) FROM flixadvisor.SEASON WHERE tv_show = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du nombre de saisons de la serie.");
    }
    return $queryPrepared->fetch()[0];
}

/**
 * Récupère la liste des acteurs d'une série par odre alaphabétique (nom de l'acteur) dans un tableau 2 dimensions
 * @param $idShow
 * @return array [0 => [name_actor, role_actor, photo_actor], 1 => [name_actor, ...], 2 => ..., ...]
 */
function getTVShowActors($idShow)
{
    $pdo = connectDB();
    $query = "SELECT ACTOR.name_actor, CASTING.role_actor, CASTING.photo_actor FROM flixadvisor.ACTOR, flixadvisor.CASTING WHERE CASTING.actor = ACTOR.id_actor AND CASTING.tv_show = :id order by name_actor";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des acteurs de la serie.");
    }
    return $queryPrepared->fetchAll();
}

/**
 * Récupère la liste des chaines de diffusions d'une série par ordre alaphabétique dans un tableau 2 dimensions
 * @param $idShow
 * @return array [0 => [name_network, country_network], 1 => [name_network, country_network], 2 => ...]
 */
function getTVShowNetworks($idShow)
{
    $pdo = connectDB();
    $query = "SELECT NETWORK.name_network, NETWORK.country_network FROM flixadvisor.NETWORK, flixadvisor.BROADCAST WHERE network = id_network AND tv_show = :id ORDER BY name_network";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des chaines de la serie.");
    }

    return $queryPrepared->fetchAll();
}

/**
 * A tester quand on aura du contenu !
 */
function getTVShowRecommendations($idShow)
{
    $pdo = connectDB();
    $query = "SELECT id_reco , text_reco, date_reco, recommended_show, hosting_show, member, (SELECT COUNT(*) FROM flixadvisor.VOTED_RECO WHERE recommendation = id_reco) AS nbVotes  FROM flixadvisor.RECOMMENDATION WHERE hosting_show = :id ORDER BY nbVotes DESC ";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des recommendations de la serie.");
    }

    return $queryPrepared->fetchAll();
}

/**
 * @param $idShow
 * @return mixed
 */
function getTVShowFollowersNumber($idShow)
{
    $pdo = connectDB();
    $query = "SELECT count(*) FROM FOLLOWED_SHOW WHERE tv_show = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des followers de la serie.");
    }

    return $queryPrepared->fetch()[0];
}

/**
 * Récupère la liste des commentaires d'une série par ordre de like dans un tableau à 2 dimensions
 * @param $idShow
 * @return array [0 => [id_comment, text_comment, date_comment, is_modified_comment, member, tv_show, nbLikes], 1 => ...]
 */
function getTVShowComments($idShow)
{
    $pdo = connectDB();
    $query = "SELECT id_comment, text_comment, date_comment, is_modified_comment, member, tv_show, (SELECT count(*) FROM flixadvisor.LIKED_COMMENT WHERE comment = id_comment) AS nbLikes FROM flixadvisor.COMMENT WHERE tv_show = :id ORDER BY nbLikes DESC";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des commentaires de la serie.");
    }

    return $queryPrepared->fetchAll();
}

/**
 * Récupère la liste des réponses à un commentaire par ordre d'anciennetée dans un tableau à 2 dimensions
 * @param $idComment
 * @return array [0 => [id_reply, text_reply, is_modified_reply, `date_reply `, member, comment], 1 => [id_reply, ...], ...]
 */
function getCommentReplys($idComment)
{
    $pdo = connectDB();
    $query = "SELECT id_reply, text_reply, is_modified_reply, `date_reply `, member, comment FROM flixadvisor.REPLY WHERE comment = :id ORDER BY `date_reply ` ASC ";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idComment]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recherche d'image.");
    }

    return $queryPrepared->fetchAll();
}

/**
 * Retourne la moyenne de la série (score)
 * @param $idShow
 * @return integer
 */
function getShowLastAiringDate($idShow)
{
    $pdo = connectDB();
    $query = "SELECT max(first_aired_episode) FROM EPISODE WHERE season IN (SELECT id_season FROM SEASON WHERE tv_show = :id)";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation de la derniere date de diffusion de la série.");
    }
    return $queryPrepared->fetch()[0];
}

function getShowScore($idShow)
{
    $pdo = connectDB();
    $query = "SELECT CAST(AVG(mark_followed_show) AS DECIMAL(10,2)) FROM flixadvisor.FOLLOWED_SHOW WHERE tv_show = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du score de la série.");
    }
    return $queryPrepared->fetch()[0];
}


function getShowCategories($idShow)
{
    $pdo = connectDB();
    $query = "SELECT name_category FROM flixadvisor.CATEGORIZED_SHOW, flixadvisor.CATEGORY WHERE tv_show = :id AND category = id_category";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des categories de la série.");
    }
    return $queryPrepared->fetchAll();
}

function getShowEpisodes($idShow)
{
    $pdo = connectDB();
    $query = "SELECT nb_season, nb_episode, name_episode, first_aired_episode, director_episode, author_episode, summary_episode FROM flixadvisor.EPISODE, flixadvisor.SEASON WHERE season = id_season AND tv_show = :id ORDER BY nb_season, nb_episode ASC";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des categories de la série.");
    }
    return $queryPrepared->fetchAll();
}

/**
 * Récupère la liste des séries existantes en BDD par ordre alphabétique dans un tableau à 2 dimensions
 * @return array [0 => [id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated], 1 => ...]
 */
function getTVShowList()
{
    $pdo = connectDB();
    $query = "SELECT id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated FROM flixadvisor.TV_SHOW ORDER BY name_show";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recupération des series");
    }
    return $queryPrepared->fetchAll();
}

/**
 * Récupère la liste des catégories existantes en BDD par ordre alaphabétique dans un tableau à 2 dimensions
 * @return array [0 => [id_category, name_category], 1 => [id_category, name_category], 2 => ...]
 */
function getCategoryList()
{
    $pdo = connectDB();
    $query = "SELECT id_category, name_category FROM flixadvisor.CATEGORY ORDER BY name_category";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des categories.");
    }

    return $queryPrepared->fetchAll();
}

/**
 * Récupère la liste des chaines de diffusions existantes en BDD par ordre aphabétique dans un tableau à 2 dimensions
 * @return array [0 => [id_network, name_network, country_network], 1 => [id_network, name_network, country_network], ...]
 */
function getNetworkList()
{
    $pdo = connectDB();
    $query = "SELECT id_network, name_network, country_network FROM flixadvisor.NETWORK ORDER BY name_network";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des chaines.");
    }

    return $queryPrepared->fetchAll();
}

function banMember($email, $banType, $time)
{
    $pdo = connectDB();
    $query = "UPDATE MEMBER SET account_status = :status, banned_date = curdate(), banned_time = :time where email = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":status" => $banType,
        ":time" => $time,
        ":email" => $email
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors du banissement d'un membre.");
    }
}

function unbanMember($email)
{
    $pdo = connectDB();
    $query = "UPDATE MEMBER SET account_status = 'actif', banned_date = NULL, banned_time = NULL where email = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":email" => $email]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors du débanissement d'un membre.");
    }
}

function listBannedMembers()
{
    $pdo = connectDB();
    $query = "SELECT email, pseudo, date_inscription, account_status, banned_date, banned_time FROM MEMBER where account_status != 'actif' AND account_status != 'non-active'";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération des membres bannis.");
    }
    return $queryPrepared->fetchAll();
}

function getGenderStats()
{
    $pdo = connectDB();
    $query = "SELECT count(*) * 100 / (select count(*) from MEMBER) as nombres, gender FROM MEMBER group by gender";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération des stats des genres.");
    }
    return $queryPrepared->fetchAll();
}

function getMembersCountry()
{
    $pdo = connectDB();
    $query = "SELECT count(*) as nombres, country FROM MEMBER where country != '' AND country is not null group by country";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération des pays des membres.");
    }
    return $queryPrepared->fetchAll();
}

function getMembersCity()
{
    $pdo = connectDB();
    $query = "SELECT count(*) as nombres, city FROM MEMBER where city != '' AND city is not null group by city";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération des villes des membres.");
    }
    return $queryPrepared->fetchAll();
}

function getMembersAge()
{
    $pdo = connectDB();
    $query = "SELECT email, floor(datediff(curdate(), birth_date) / 365) as AGE FROM MEMBER where birth_date is not null";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération de l'age des membres.");
    }
    return $queryPrepared->fetchAll();
}

function getMembersInscriptionStat()
{
    $pdo = connectDB();
    $query = "SELECT count(*) as nb_inscription, date_inscription from MEMBER group by date_inscription";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération des stats des date d'inscription des membres.");
    }
    return $queryPrepared->fetchAll();
}

function getCategoriesStats()
{
    $pdo = connectDB();
    $query = "SELECT count(*) as used, count(*)*100/(select count(*) from CATEGORIZED_SHOW) as stat_used, name_category from CATEGORY, CATEGORIZED_SHOW where CATEGORIZED_SHOW.category = CATEGORY.id_category group by category";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération des stats des date d'inscription des membres.");
    }
    return $queryPrepared->fetchAll();
}

function getTVYearStatusStat()
{
    $pdo = connectDB();
    $query = "SELECT count(*) as nb_show, YEAR(first_aired_show) as year, (SELECT count(*) from TV_SHOW where YEAR(first_aired_show) = year AND production_status = 'Ended') as ended, (SELECT count(*) from TV_SHOW where YEAR(first_aired_show) = year AND production_status = 'Continuing') as continuing from TV_SHOW group by YEAR(first_aired_show)";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération des stats des stats d'année de diffusion des séries.");
    }
    return $queryPrepared->fetchAll();
}

function addOrRemoveMemberWatchedEpisode($email, $idEpisode)
{
    $pdo = connectDB();
    $query = "select member,episode from flixadvisor.WATCHED_EPISODES WHERE episode = :episode AND member = :email;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":episode" => $idEpisode,
        ":email" => $email
    ]);
    if (sizeof($queryPrepared->fetchAll()) == 0)
        $query = "insert ignore into flixadvisor.WATCHED_EPISODES (member, episode, date_watched) VALUES (:email, :episode, curdate());";
    else
        $query = "delete from flixadvisor.WATCHED_EPISODES WHERE episode = :episode AND member = :email;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":episode" => $idEpisode,
        ":email" => $email
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de l'insertion / suppression d'un episode regarde.");
    }
}

function addOrRemoveMemberTVShowList($email, $idShow, $status, $notification, $mark)
{

}

function updateNotificationMemberTVShowList($email, $idShow, $notification)
{

}

function updateStatusMemberTVShowList($email, $idShow, $status)
{

}

function updateMarkMemberTVShowList($email, $idShow, $mark)
{
    
}

