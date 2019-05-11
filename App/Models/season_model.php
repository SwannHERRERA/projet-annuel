<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Season_model extends My_model
{
    protected $_table = 'SEASON';
    protected $table_primary_key = "id_season";

    /**
     * Récupère le nombre de saisons d'une série
     * @param INTEGER $idShow
     * @return integer
     */
    function getTVNumberSeasons($idShow)
    {
        
        $query = "SELECT max(nb_season) FROM flixadvisor.SEASON WHERE tv_show = :id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation du nombre de saisons de la serie.");
        }
        return $queryPrepared->fetch()[0];
    }
}
