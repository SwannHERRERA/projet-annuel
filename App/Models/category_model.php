<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Category_model extends My_model
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

    /**
     * Récupère la liste des catégories d'une série par ordre alphabétique dans un tableau 2 dimensions
     * @param $idShow
     * @return array [0 => [name_category], 1 => [name_category], 2 => ...]
     */
    public function getTVShowCategories($idShow)
    {
        $query = "SELECT CATEGORY.name_category FROM flixadvisor.CATEGORY, flixadvisor.CATEGORIZED_SHOW WHERE category = id_category AND tv_show = :id order by CATEGORY.name_category";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation des episodes de la serie.");
        }
        return $queryPrepared->fetchAll();
    }


    /**
     * Récupère la liste des catégories existantes en BDD par ordre alaphabétique dans un tableau à 2 dimensions
     * @return array [0 => [id_category, name_category], 1 => [id_category, name_category], 2 => ...]
     */
    public function getCategoryList()
    {
        $query = "SELECT id_category, name_category FROM flixadvisor.CATEGORY ORDER BY name_category";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation des categories.");
        }

        return $queryPrepared->fetchAll();
    }

    public function getCategoriesStats()
    {
        $query = "SELECT count(*) as used, count(*)*100/(select count(*) from CATEGORIZED_SHOW) as stat_used, name_category from CATEGORY, CATEGORIZED_SHOW where CATEGORIZED_SHOW.category = CATEGORY.id_category group by category";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la récupération des stats des date d'inscription des membres.");
        }
        return $queryPrepared->fetchAll();
    }
}
