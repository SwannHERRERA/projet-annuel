<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Network_model extends My_model
{
    protected $_table = 'NETWORK';
    protected $table_primary_key = "id_network";

    /**
     * Récupère la liste des chaines de diffusions d'une série par ordre alaphabétique dans un tableau 2 dimensions
     * @param $idShow
     * @return array [0 => [name_network, country_network], 1 => [name_network, country_network], 2 => ...]
     */
    function getTVShowNetworks($idShow)
    {

        $query = "SELECT NETWORK.name_network, NETWORK.country_network FROM flixadvisor.NETWORK, flixadvisor.BROADCAST WHERE network = id_network AND tv_show = :id ORDER BY name_network";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation des chaines de la serie.");
        }

        return $queryPrepared->fetchAll();
    }

    /**
     * Récupère la liste des chaines de diffusions existantes en BDD par ordre aphabétique dans un tableau à 2 dimensions
     * @return array [0 => [id_network, name_network, country_network], 1 => [id_network, name_network, country_network], ...]
     */
    function getNetworkList()
    {

        $query = "SELECT id_network, name_network, country_network FROM flixadvisor.NETWORK ORDER BY name_network";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation des chaines.");
        }

        return $queryPrepared->fetchAll();
    }
}
