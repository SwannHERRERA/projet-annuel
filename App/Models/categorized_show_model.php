<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Categorized_show_model extends My_model
{
    protected $_table = 'CATEGORIZED_SHOW';
    protected $table_primary_key = "";

    /**
    * Ajoute une catégorie à une série
    * @param INTEGER $idShow
    * @param INTEGER $idCategory
    */
    public function addCategorizedShow($idShow, $idCategory)
    {
        $query = "INSERT IGNORE INTO flixadvisor.CATEGORIZED_SHOW (category, tv_show) VALUES (:category, :tv);";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
            ":tv" => $idShow,
            ":category" => $idCategory
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de l'ajout du hodor");
        }
    }

    /**
    * DELETE CATEGORIZED_SHOW (je sais pas trop ou le mettre)
    * @param INTEGER $tv_show
    * @param INTEGER $category
    */
    public function removeCategorizedShow($tv_show, $category)
    {
        $query = "DELETE FROM flixadvisor.CATEGORIZED_SHOW WHERE tv_show = :tv AND category = :category";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
            ":tv" => $tv_show,
            ":category" => $category]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("une erreur est survenu lors de la suppression du hodor");
        }
    }
}
