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
 * @return array [0 => [id_comment, text_comment, date_comment, is_modified_comment, member, tv_show, photo, pseudo, nbLikes], 1 => ...]
 */
function getTVShowComments($idShow)
{
    $pdo = connectDB();
    $query = "SELECT id_comment, text_comment, date_comment, is_modified_comment, member, tv_show, photo, pseudo, (SELECT count(*) FROM flixadvisor.LIKED_COMMENT WHERE comment = id_comment) AS nbLikes FROM flixadvisor.COMMENT, flixadvisor.MEMBER WHERE tv_show = :id AND member = email ORDER BY nbLikes DESC, id_comment desc";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des commentaires de la serie.");
    }

    return $queryPrepared->fetchAll();
}

function isLikedComment($idComment, $email)
{
    $pdo = connectDB();
    $query = "SELECT * FROM flixadvisor.LIKED_COMMENT where comment = :id and member = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":id" => $idComment,
        ":email" => $email
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du like du commentaire.");
    }
    return sizeof($queryPrepared->fetchAll()) > 0;
}

function likeComment($idComment, $email)
{
    $pdo = connectDB();
    $query = "INSERT IGNORE INTO flixadvisor.LIKED_COMMENT (member, comment) VALUES (:email,:id)";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":id" => $idComment,
        ":email" => $email
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors du like du commentaire.");
    }
}

function unlikeComment($idComment, $email)
{
    $pdo = connectDB();
    $query = "DELETE FROM flixadvisor.LIKED_COMMENT WHERE member = :email AND comment = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":id" => $idComment,
        ":email" => $email
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors du unlike du commentaire.");
    }
}


function addTVShowComments($idShow, $email, $textComment)
{
    $pdo = connectDB();
    $query = "INSERT INTO flixadvisor.COMMENT (text_comment, date_comment, is_modified_comment, member, tv_show) VALUES (:comment, curdate(), 'n', :email, :show)";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":show" => $idShow,
        ":email" => $email,
        ":comment" => $textComment
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des commentaires de la serie.");
    }
    return $pdo->lastInsertId();
}

function removeTVShowComment($idComment)
{
    $pdo = connectDB();
    $query = "DELETE FROM LIKED_COMMENT where comment = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idComment]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la suppression du commentaire.");
    }
    $pdo = connectDB();
    $query = "DELETE FROM COMMENT where id_comment = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idComment]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la suppression du commentaire.");
    }

}

function getMemberLists($email)
{
    $pdo = connectDB();
    $query = "SELECT id_list, name_list, visibility_list, description_list, date_list, member FROM flixadvisor.LIST where member = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":email" => $email]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération des listes.");
    }
    return $queryPrepared->fetchAll();
}

function addMemberList($email, $name, $visibility, $description)
{
    $pdo = connectDB();
    $query = "INSERT INTO flixadvisor.LIST (name_list, visibility_list, description_list, date_list, member) values (:name,:visibility,:description,curdate(),:email)";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":name" => $name,
        ":visibility" => $visibility,
        ":description" => $description,
        ":email" => $email
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la création d'une liste.");
    }
    return $pdo->lastInsertId();
}

function updateMemberList($id, $name, $visibility, $description)
{
    $pdo = connectDB();
    $query = "UPDATE flixadvisor.LIST SET name_list = :name, visibility_list = :visibility, description_list = :description where id_list = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":id" => $id,
        ":name" => $name,
        ":visibility" => $visibility,
        ":description" => $description
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la maj d'une liste.");
    }
}

function deleteMemberList($id)
{
    $pdo = connectDB();
    $query = "DELETE FROM flixadvisor.IN_LIST where list = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $id]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la supressison d'une liste.");
    }
    $query = "DELETE FROM flixadvisor.LIST where id_list = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $id]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la supressison d'une liste.");
    }
}

function isInList($idShow, $idList)
{
    $pdo = connectDB();
    $query = "select * from flixadvisor.IN_LIST where tv_show = :idS and list = :idL";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":idL" => $idList,
        ":idS" => $idShow
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récuperation d'un element liste.");
    }
    return sizeof($queryPrepared->fetchAll()) > 0;
}

function addShowToList($idShow, $idList)
{
    $pdo = connectDB();
    $query = "insert ignore into flixadvisor.IN_LIST (list, tv_show) VALUES (:idL,:idS)";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":idL" => $idList,
        ":idS" => $idShow
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de l'ajout d'un element liste.");
    }
}

function removeShowFromList($idShow, $idList)
{
    $pdo = connectDB();
    $query = "delete from flixadvisor.IN_LIST where tv_show = :idS and list = :idL";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":idL" => $idList,
        ":idS" => $idShow
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la supression d'un element liste.");
    }
    return $queryPrepared->fetchAll() > 0;
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
        die("Une erreur est survenue lors de la recup d'un commentaire.");
    }

    return $queryPrepared->fetchAll();
}

function getComment($idComment)
{
    $pdo = connectDB();
    $query = "SELECT id_comment, text_comment, date_comment, is_modified_comment, member, tv_show, photo, pseudo FROM flixadvisor.COMMENT, flixadvisor.MEMBER WHERE member = email and id_comment = :id";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idComment]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recup d'un commentaire.");
    }

    return $queryPrepared->fetch(PDO::FETCH_ASSOC);

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
    $query = "SELECT nb_season, nb_episode, name_episode, first_aired_episode, director_episode, author_episode, summary_episode, id_episode FROM flixadvisor.EPISODE, flixadvisor.SEASON WHERE season = id_season AND tv_show = :id ORDER BY nb_season, nb_episode ASC";
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

function getMember($email)
{
    $pdo = connectDB();
    $query = "select email, pseudo, photo, account_role, date_inscription FROM flixadvisor.MEMBER where email = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":email" => $email]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération du membre.");
    }
    return $queryPrepared->fetch();
}

function getMemberByPseudo($pseudo)
{
    $pdo = connectDB();
    $query = "select email, pseudo, photo, account_role, date_inscription FROM flixadvisor.MEMBER where pseudo = :pseudo";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":pseudo" => $pseudo]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la récupération du membre.");
    }
    return $queryPrepared->fetch();

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

function isWatchedEpisode($email, $idEpisode)
{
    $pdo = connectDB();
    $query = "select member,episode from flixadvisor.WATCHED_EPISODES WHERE episode = :episode AND member = :email;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":episode" => $idEpisode,
        ":email" => $email
    ]);
    return sizeof($queryPrepared->fetchAll()) == 0 ? false : true;
}

function watchAllEpisodes($email, $idShow)
{
    $pdo = connectDB();
    $query = "select id_episode from flixadvisor.EPISODE left join SEASON S on EPISODE.season = S.id_season left join TV_SHOW TS on S.tv_show = TS.id_show where TS.id_show = :show;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":show" => $idShow]);
    $episodes = $queryPrepared->fetchAll();
    $query = "insert ignore into flixadvisor.WATCHED_EPISODES (member, episode, date_watched) VALUES (:email, :episode, curdate())";
    foreach ($episodes as $episode) {
        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute([
            ":email" => $email,
            ":episode" => $episode['id_episode']
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de l'insertion / suppression d'un episode regarde.");
        }
    }
}

function unwatchEpisode($email, $idEp)
{
    $pdo = connectDB();
    $query = "DELETE FROM flixadvisor.WATCHED_EPISODES where member = :email and episode = :episode";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":email" => $email,
        ":episode" => $idEp
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de l'insertion / suppression d'un episode regarde.");
    }
}

function watchEpisode($email, $idEp)
{
    $pdo = connectDB();
    $query = "INSERT IGNORE INTO flixadvisor.WATCHED_EPISODES (member, episode, date_watched) VALUES (:email, :episode, curdate())";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":email" => $email,
        ":episode" => $idEp
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de l'insertion / suppression d'un episode regarde.");
    }
}

function unwatchAllEpisodes($email, $idShow)
{
    $pdo = connectDB();
    $query = "select id_episode from flixadvisor.EPISODE left join SEASON S on EPISODE.season = S.id_season left join TV_SHOW TS on S.tv_show = TS.id_show where TS.id_show = :show;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":show" => $idShow]);
    $episodes = $queryPrepared->fetchAll();
    $query = "delete from flixadvisor.WATCHED_EPISODES where episode =:episode and member = :email";
    foreach ($episodes as $episode) {
        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute([
            ":email" => $email,
            ":episode" => $episode['id_episode']
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de l'insertion / suppression d'un episode regarde.");
        }
    }
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

function isFollowing($email, $idShow)
{
    $pdo = connectDB();
    $query = "select count(*) from FOLLOWED_SHOW where tv_show = :idShow and member =:email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":email" => $email,
        ":idShow" => $idShow
    ]);
    return $queryPrepared->fetch()[0];
}

function isNotified($email, $idShow)
{
    $pdo = connectDB();
    $query = "select notification_followed_show from FOLLOWED_SHOW where tv_show = :idShow and member =:email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":email" => $email,
        ":idShow" => $idShow
    ]);
    return $queryPrepared->fetch()[0];
}

function addOrRemoveMemberTVShowToFollowingShow($email, $idShow, $status, $notification, $mark)
{
    $pdo = connectDB();
    $query = "select member, tv_show from flixadvisor.FOLLOWED_SHOW WHERE member = :email AND tv_show = :idShow;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":idShow" => $idShow,
        ":email" => $email
    ]);
    if (sizeof($queryPrepared->fetchAll()) == 0) {
        $query = "insert ignore into flixadvisor.FOLLOWED_SHOW (member, tv_show, status_followed_show, notification_followed_show, date_followed_show, mark_followed_show) VALUES (:email, :idShow,:status,:notification, curdate(),:mark);";
        $parameters = [
            ":idShow" => $idShow,
            ":email" => $email,
            ":status" => $status,
            ":notification" => $notification,
            ":mark" => $mark
        ];
    } else {
        $query = "delete from flixadvisor.FOLLOWED_SHOW WHERE tv_show = :idShow AND member = :email;";
        $parameters = [
            ":idShow" => $idShow,
            ":email" => $email
        ];
    }
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute($parameters);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de l'insertion / suppression d'une serie en suivie.");
    }
}

function updateNotificationMemberTVShowList($email, $idShow, $notification)
{
    $pdo = connectDB();
    $query = "update flixadvisor.FOLLOWED_SHOW set notification_followed_show = :notification where tv_show = :idShow and member = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":email" => $email,
        ":idShow" => $idShow,
        ":notification" => $notification
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la maj des notifications sur la serie.");
    }
}

function updateStatusMemberTVShowList($email, $idShow, $status)
{
    $pdo = connectDB();
    $query = "update flixadvisor.FOLLOWED_SHOW set status_followed_show = :status where tv_show = :idShow and member = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":email" => $email,
        ":idShow" => $idShow,
        ":status" => $status
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la maj du statut sur la serie.");
    }
}

function updateMarkMemberTVShowList($email, $idShow, $mark)
{
    $pdo = connectDB();
    $query = "update flixadvisor.FOLLOWED_SHOW set mark_followed_show = :mark where tv_show = :idShow and member = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":email" => $email,
        ":idShow" => $idShow,
        ":mark" => $mark
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la maj de la note sur la serie.");
    }
}

/**
 * @param $nameShow string (nom de la serie ,recherche flexible : game => game of thrones, no game,...)
 * @return array[array[id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated, score, followers],...]
 */
function searchTVShow($nameShow)
{
    $pdo = connectDB();
    $query = "select id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, " .
        "last_updated,(SELECT CAST(AVG(mark_followed_show) AS DECIMAL(10,2)) FROM flixadvisor.FOLLOWED_SHOW " .
        "WHERE FOLLOWED_SHOW.tv_show = id_show) as score, (SELECT count(*) FROM FOLLOWED_SHOW where FOLLOWED_SHOW.tv_show = id_show) " .
        "as followers from flixadvisor.TV_SHOW where instr(name_show, :name) >0";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":name" => $nameShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recherhce des series.");
    }
    return $queryPrepared->fetchAll();
}

/**
 * @param $nameActor string (nom de l'acteur, recherche flexible : mai => maisie williams,...)
 * @return array[array[id_actor, name_actor],...]
 */
function searchActor($nameActor)
{
    $pdo = connectDB();
    $query = "select id_actor, name_actor from flixadvisor.ACTOR where instr(name_actor, :name) >0";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":name" => $nameActor]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recherhce des acteurs.");
    }
    return $queryPrepared->fetchAll();
}

/**
 * @param $nameMember string (pseuso du membre, recherche flexible : ma => marie, manon,...)
 * @return array[array[email,pseudo],...]
 */
function searchMember($nameMember)
{
    $pdo = connectDB();
    $query = "select email, pseudo from flixadvisor.MEMBER where instr(pseudo, :name) >0";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":name" => $nameMember]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recherhce des series.");
    }
    return $queryPrepared->fetchAll();
}

/**
 * @param $nameShow string (nom de la série, recherche flexible : game => game of thrones, no game,... , laisser "" ou null pour ne pas prendre en compte)
 * @param $minimumRating string (note minimum de la série, laisser "" ou null pour ne pas prendre en compte)
 * @param $status string (status de la série : Continuing, Ended, laisser "" ou null pour ne pas prendre en compte)
 * @param $idNetworks array[id1,id2,id3,...] (réseaux de diffusion voulus, laisser "",[] ou null pour ne pas prendre en compte)
 * @param $firstAiredYears array[year1,year2,year3,...] (années de diffusion de la série, laisser "",[] ou null pour ne pas prendre en compte)
 * @param $runtimes array[time1,time2,time3,...] (durées moyennes de la série, laisser "",[] ou null pour ne pas prendre en compte)
 * @param $idGenres array[id1,id2,id3,...] (genres voulus de la série, laisser "",[] ou null pour ne pas prendre en compte)
 * @param $idActors array[id1,id2,id3,...] (acteurs voulus de la série, laisser "",[] ou null pour ne pas prendre en compte)
 * @return array[array[id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated, score, followers],...]
 */
function searchTVShowAdvanced($nameShow, $minimumRating, $status, $idNetworks, $firstAiredYears, $runtimes, $idGenres, $idActors)
{
    $pdo = connectDB();
    $parameters = [];
    $query = "select " .
        "id_show, " .
        "name_show, " .
        "production_status, " .
        "runtime_show, " .
        "first_aired_show, " .
        "image_show, " .
        "summary_show, " .
        "last_updated, " .
        "(SELECT CAST(AVG(mark_followed_show) AS DECIMAL(10,2)) " .
        "FROM flixadvisor.FOLLOWED_SHOW WHERE FOLLOWED_SHOW.tv_show = id_show) as score, " .
        "(SELECT count(*) " .
        "FROM FOLLOWED_SHOW where FOLLOWED_SHOW.tv_show = id_show) as followers " .
        "from flixadvisor.TV_SHOW ";
    $join = "";
    $condition = "where 1=1 ";
    if ($nameShow != null && sizeof($nameShow) > 0) {
        $condition .= "AND instr(name_show, :name) >0 ";
        $parameters = array_merge($parameters, [":name" => $nameShow]);
    }
    if ($minimumRating != null && sizeof($minimumRating) > 0) {
        $condition .= "AND " .
            "(SELECT CAST(AVG(mark_followed_show) AS DECIMAL(10,2)) " .
            "FROM flixadvisor.FOLLOWED_SHOW " .
            "WHERE tv_show = id_show) " .
            ">= :mark ";
        $parameters = array_merge($parameters, [":mark" => $minimumRating]);
    }
    if ($status != null && sizeof($status) > 0) {
        $condition .= "AND production_status = :status ";
        $parameters = array_merge($parameters, [":status" => $status]);
    }
    if ($idNetworks != null and sizeof($idNetworks) > 0) {
        $join .= "left join BROADCAST ON flixadvisor.TV_SHOW.id_show = tv_show " .
            "left join NETWORK N on BROADCAST.network = N.id_network ";
        $networks = "";
        $in_params = [];
        foreach ($idNetworks as $i => $network) {
            $key = ":idn" . $i;
            $networks .= "$key,";
            $in_params[$key] = $network;
        }
        $networks = rtrim($networks, ",");
        $condition .= "AND N.id_network IN ($networks) ";
        $parameters = array_merge($parameters, $in_params);
    }
    if ($firstAiredYears != null && sizeof($firstAiredYears) > 0) {
        $years = "";
        $in_params = [];
        foreach ($firstAiredYears as $i => $year) {
            $key = ":idy" . $i;
            $years .= "$key,";
            $in_params[$key] = $year;
        }
        $years = rtrim($years, ",");
        $condition .= "AND YEAR(first_aired_show) IN ($years) ";
        $parameters = array_merge($parameters, $in_params);
    }
    if ($runtimes != null && sizeof($runtimes) > 0) {
        $runs = "";
        $in_params = [];
        foreach ($runtimes as $i => $runtime) {
            $key = ":idr" . $i;
            $runs .= "$runtime,";
            $in_params[$key] = $runtime;
        }
        $runs = rtrim($runs, ",");
        $condition .= "AND runtime_show IN ($runs) ";
        $parameters = array_merge($parameters, $in_params);
    }
    if ($idGenres != null && sizeof($idGenres) > 0) {
        $join .= "left join CATEGORIZED_SHOW CS on TV_SHOW.id_show = CS.tv_show " .
            "left join CATEGORY C on CS.category = C.id_category ";
        $genres = "";
        $in_params = [];
        foreach ($idGenres as $i => $genre) {
            $key = ":idg" . $i;
            $genres .= "$key,";
            $in_params[$key] = $genre;
        }
        $genres = rtrim($genres, ",");
        $condition .= "AND C.id_category IN ($genres) ";
        $parameters = array_merge($parameters, $in_params);
    }
    if ($idActors != null && sizeof($idActors) > 0) {
        $join .= "left join CASTING C2 on TV_SHOW.id_show = C2.tv_show " .
            "left join ACTOR A on C2.actor = A.id_actor ";
        $actors = "";
        $in_params = [];
        foreach ($idActors as $i => $actor) {
            $key = ":ida" . $i;
            $actors .= "$key,";
            $in_params[$key] = $actor;
        }
        $actors = rtrim($actors, ",");
        $condition .= "and A.id_actor IN ($actors) ";
        $parameters = array_merge($parameters, $in_params);
    }

    $condition .= "group by id_show order by name_show";

    $queryPrepared = $pdo->prepare($query . $join . $condition);
    $queryPrepared->execute($parameters);
    return $queryPrepared->fetchAll();
}

/**
 * Retourne les 10 premieres series les plus suivies
 * @return array[array[id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated, followers],...]
 */
function get10MostFollowedShows()
{
    $pdo = connectDB();
    $query = "select id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated, (select count(*) from flixadvisor.FOLLOWED_SHOW where tv_show = id_show) as followers from flixadvisor.TV_SHOW group by id_show order by followers desc limit 10;";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des series les plus suivies.");
    }
    return $queryPrepared->fetchAll();
}

function getShowRank($idShow)
{
    $pdo = connectDB();
    $query = "select ranking from (SELECT @rowNum := @rowNum + 1 as ranking, id_show, name_show, (select count(*) from flixadvisor.FOLLOWED_SHOW where tv_show = id_show) as followers from TV_SHOW, (select @rowNum := 0) as t order by followers desc) as shows where id_show = :id;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du rang de la série.");
    }
    return $queryPrepared->fetch()[0];
}

function getShowMarkMember($idShow, $email)
{
    $pdo = connectDB();
    $query = "select mark_followed_show from FOLLOWED_SHOW where tv_show = :id and member =:email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow, ":email" => $email]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation dde la note utilisateur.");
    }
    return $queryPrepared->fetch()[0];
}

function getShowStatusMember($idShow, $email)
{
    $pdo = connectDB();
    $query = "select status_followed_show from FOLLOWED_SHOW where tv_show = :id and member =:email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":id" => $idShow, ":email" => $email]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du status utilisateur.");
    }
    return $queryPrepared->fetch()[0];
}

/**
 * Retourne les 10 premieres series ayant sorti un episode recemment
 * @return array[array[id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated,last_episode],...]
 */
function get10LastUpdatedShows()
{
    $pdo = connectDB();
    $query = "select id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated, (select MAX(first_aired_episode) from flixadvisor.EPISODE left join SEASON S on EPISODE.season = S.id_season where S.tv_show = id_show and first_aired_episode <= sysdate()) as last_episode from flixadvisor.TV_SHOW group by id_show order by last_episode desc limit 10;";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des series les plus recentes.");
    }
    return $queryPrepared->fetchAll();
}

/**
 * Retourne les 10 series les mieux notés par les utilisateurs
 * @return array[array[id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated, score],...]
 */
function get10BestShows()
{
    $pdo = connectDB();
    $query = "select id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated, (SELECT CAST(AVG(mark_followed_show) AS DECIMAL(10, 2)) FROM flixadvisor.FOLLOWED_SHOW WHERE FOLLOWED_SHOW.tv_show = id_show) as score from flixadvisor.TV_SHOW group by id_show order by score desc limit 10;";
    $queryPrepared = $pdo->query($query);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des series les mieux notees.");
    }
    return $queryPrepared->fetchAll();
}

function searchUser($user)
{
    $pdo = connectDB();
    $query = "select * from flixadvisor.MEMBER where instr(pseudo, :user) >0 or instr(email, :user) > 0 group by email order by email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":user" => $user]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recherche utilisateur.");
    }
    return $queryPrepared->fetchAll();

}

function getMemberFollowedShow($pseudo)
{
    $pdo = connectDB();
    $query = "select status_followed_show, date_followed_show, mark_followed_show,first_aired_show,image_show, id_show, name_show FROM FOLLOWED_SHOW, TV_SHOW where member = (SELECT email from MEMBER where pseudo = :pseudo) and tv_show = id_show group by id_show order by name_show asc;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":pseudo" => $pseudo]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des serie suivies.");
    }
    return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
}

function getMemberWatchingShow($pseudo)
{
    $pdo = connectDB();
    $query = "select status_followed_show, date_followed_show, mark_followed_show,first_aired_show,image_show, id_show, name_show FROM FOLLOWED_SHOW, TV_SHOW where member = (SELECT email from MEMBER where pseudo = :pseudo) and tv_show = id_show and status_followed_show = 'en cours' group by id_show order by name_show asc;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":pseudo" => $pseudo]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des serie suivies.");
    }
    return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
}

function getMemberCompletedShow($pseudo)
{
    $pdo = connectDB();
    $query = "select status_followed_show, date_followed_show, mark_followed_show,first_aired_show,image_show, id_show, name_show FROM FOLLOWED_SHOW, TV_SHOW where member = (SELECT email from MEMBER where pseudo = :pseudo) and tv_show = id_show and status_followed_show = 'termine' group by id_show order by name_show asc;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":pseudo" => $pseudo]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des serie suivies.");
    }
    return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
}

function getMemberPlanToWatchShow($pseudo)
{
    $pdo = connectDB();
    $query = "select status_followed_show, date_followed_show, mark_followed_show,first_aired_show,image_show, id_show, name_show FROM FOLLOWED_SHOW, TV_SHOW where member = (SELECT email from MEMBER where pseudo = :pseudo) and tv_show = id_show and status_followed_show = 'a voir' group by id_show order by name_show asc;";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":pseudo" => $pseudo]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation des serie suivies.");
    }
    return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
}

function getMemberNumberWatchedEpisodesShow($email, $show)
{
    $pdo = connectDB();
    $query = "SELECT count(*) FROM flixadvisor.WATCHED_EPISODES where member = :email and episode in (select id_episode from EPISODE where season in (select id_season from SEASON where tv_show = :show))";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([
        ":email" => $email,
        ":show" => $show
    ]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du nombre d'episodes regardés.");
    }
    return $queryPrepared->fetch()[0];
}

function getMemberNumberWatchedEpisodes($email)
{
    $pdo = connectDB();
    $query = "SELECT count(*) FROM flixadvisor.WATCHED_EPISODES where member = :email and episode in (select id_episode from EPISODE where season in (select id_season from SEASON where tv_show in (select tv_show from FOLLOWED_SHOW where FOLLOWED_SHOW.member = :email)))";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":email" => $email]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du nombre d'episodes regardés.");
    }
    return $queryPrepared->fetch()[0];
}

function getList($idList)
{
    $pdo = connectDB();
    $query = "SELECT * FROM flixadvisor.LIST where id_list = :list";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":list" => $idList]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation de la liste");
    }
    return $queryPrepared->fetch(PDO::FETCH_ASSOC);

}

function getListContent($idList)
{
    $pdo = connectDB();
    $query = "SELECT id_show, name_show, production_status, runtime_show, first_aired_show, image_show, summary_show, last_updated FROM flixadvisor.TV_SHOW,LIST,IN_LIST where id_list = :list and list = id_list and tv_show = id_show group by id_show order by name_show asc";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":list" => $idList]);
    if ($queryPrepared->errorCode() != '00000') {
        var_dump($queryPrepared->errorInfo());
        die("Une erreur est survenue lors de la recuperation du contenu de la liste");
    }
    return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

}