<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Actor_model extends My_model
{
    protected $_table = 'ACTOR';
    protected $table_primary_key = "id_actor";

    /**
    * DELETE ACTOR and CASTING
    *@param INTEGER $id
    */
    public function removeActor($id)
    {
        $query = "";
        $query .= "DELETE FROM flixadvisor.CASTING WHERE actor = :id; ";
        $query .= "DELETE FROM flixadvisor.ACTOR WHERE id_actor = :id;";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $id]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de la suppression de l'acteur");
        }
    }

    /**
    * INSERT ACTOR
    *@param INTEGER $id
    *@param STRING $name
    */
    public function addActor($id, $name)
    {
        
        $query = "INSERT IGNORE INTO flixadvisor.ACTOR (id_actor, name_actor) VALUES (:id, :name);";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
            ":id" => $id,
            ":name" => $name
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de l'ajout de l'acteur");
        }
    }

    /**
    * UPDATE ACTOR
    *@param INTEGER $id
    *@param STRING $name
    */
    public function updateActor($id, $name)
    {
        $query = "UPDATE flixadvisor.ACTOR SET name_actor = :name WHERE id_actor = :id;";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
            ":id" => $id,
            ":name" => $name
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de la maj de l'acteur");
        }
    }

    /**
     * Récupère la liste des acteurs d'une série par odre alaphabétique (nom de l'acteur) dans un tableau 2 dimensions
     * @param $idShow
     * @return array [0 => [name_actor, role_actor, photo_actor], 1 => [name_actor, ...], 2 => ..., ...]
     */
    function getTVShowActors($idShow)
    {
        $query = "SELECT ACTOR.name_actor, CASTING.role_actor, CASTING.photo_actor FROM flixadvisor.ACTOR, flixadvisor.CASTING WHERE CASTING.actor = ACTOR.id_actor AND CASTING.tv_show = :id order by name_actor";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation des acteurs de la serie.");
        }
        return $queryPrepared->fetchAll();
    }
}
