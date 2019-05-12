<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Episode_model extends My_model
{
    protected $_table = 'EPISODE';
    protected $table_primary_key = "id_episode";


    /**
     * Récupère la liste des épisodes de la série par ordre croissant dans un tableau 2 dimensions
     * @param $idShow
     * @return array [0 => [id_episode, nb_episode,nb_season,name_episode,first_aired_episode,director_episode,author_episode,summary_episode,season,id_season,tv_show],
     * 1 => [id_episode,...], 2 => ...]
     */
    function getTVEpisodes($idShow)
    {

        $query = "SELECT id_episode, nb_episode,nb_season,name_episode,first_aired_episode,director_episode,author_episode,summary_episode,season,id_season,tv_show FROM flixadvisor.EPISODE, flixadvisor.SEASON WHERE EPISODE.season = SEASON.id_season AND SEASON.tv_show = :id ORDER BY SEASON.nb_season, EPISODE.nb_episode";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation des episodes de la serie.");
        }
        return $queryPrepared->fetchAll();
    }

    /**
     * Récupère le nombre d'épisodes d'une série
     * @param INTEGER $idShow
     * @return integer
     */
    function getTVNumberEpisodes($idShow)
    {
        $query = "SELECT count(id_episode) FROM flixadvisor.EPISODE,flixadvisor.SEASON WHERE EPISODE.season = SEASON.id_season AND SEASON.tv_show = :id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation du nombre d'episodes de la serie.");
        }
        return $queryPrepared->fetch()[0];
    }
}
