<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Actor_model extends My_model
{
    protected $_table = 'CATEGORY';
    protected $table_primary_key = "id_category";

    /**
    * DELETE CATEGORY
    *@param INTEGER $id
    */
    public function removeCategory($id)
    {
        $query = "";
        $query .= "DELETE FROM flixadvisor.CATEGORIZED_SHOW where category = :id; ";
        $query .= "DELETE FROM flixadvisor.CATEGORY WHERE id_category = :id;";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $id]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de la suppression de la categorie");
        }
    }

    /**
    * INSERT CATEGORY
    *@param INTEGER $id
    *@param STRING $name
    */
    public function addCategory($id, $name)
    {
        $query = "insert ignore into flixadvisor.CATEGORY (id_category, name_category) VALUES (:id, :name);";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
            ":id" => $id,
            ":name" => $name
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de l'ajout de la categorie");
        }
    }

    /**
    * UPDATE CATEGORY
    *@param STRING $name
    *@param INTEGER $id
    */
    public function updateCategory($id, $name)
    {
        $query = "UPDATE flixadvisor.CATEGORY set name_category = :name where id_category = :id;";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
            ":id" => $id,
            ":name" => $name
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de la maj de la categorie");
        }
    }

    /**
    * INSERT CATEGORIZED_SHOW
    *@param INTEGER $tv_show
    *@param INTEGER $category
    */
    public function addCategorizedShow($tv_show, $category)
    {
        $query = "INSERT IGNORE INTO flixadvisor.CATEGORIZED_SHOW (category, tv_show) VALUES (:category, :tv);";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
            ":tv" => $tv_show,
            ":category" => $category
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de l'ajout du hodor");
        }
    }
}
