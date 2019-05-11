<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Actor_model extends My_model
{
    protected $_table = 'CASTING';
    protected $table_primary_key = ['tv_show', 'actor'];

    /**
    * DELETE CASTING
    *@param INTEGER $tv_show
    *@param INTEGER $actor
    */
    public function removeCasting($tv_show, $actor)
    {
        $query = "DELETE FROM flixadvisor.CASTING WHERE actor = :actor AND tv_show = :tv";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
        ":tv" => $tv_show,
        ":actor" => $actor]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de la suppression du casting");
        }
    }

    /**
    * Insert CASTING
    *@param INTEGER $tv_show
    *@param INTEGER $actor
    *@param STRING $role
    *@param STRING $photo_actor
    */
    public function addCasting($tv_show, $actor, $role, $photo_actor)
    {
        $query = "insert into flixadvisor.CASTING (tv_show, actor, role_actor, photo_actor) VALUES (:tv, :actor, :role, :photo) " .
        "ON DUPLICATE KEY UPDATE role_actor = :role, photo_actor = :photo";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
        ":tv" => $tv_show,
        ":actor" => $actor,
        ":role" => $role,
        ":photo" => $photo_actor]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de l'ajout du casting");
        }
    }
}
