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
        $pdo = connectDB();
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
}
