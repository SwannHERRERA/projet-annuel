<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Comment_model extends My_model
{
    protected $_table = 'COMMENT';
    protected $table_primary_key = "id_comment";

    /**
     * Récupère la liste des commentaires d'une série par ordre de like dans un tableau à 2 dimensions
     * @param $idShow
     * @return array [0 => [id_comment, text_comment, date_comment, is_modified_comment, member, tv_show, nbLikes], 1 => ...]
     */
    function getTVShowComments($idShow)
    {

        $query = "SELECT id_comment, text_comment, date_comment, is_modified_comment, member, tv_show, (SELECT count(*) FROM flixadvisor.LIKED_COMMENT WHERE comment = id_comment) AS nbLikes FROM flixadvisor.COMMENT WHERE tv_show = :id ORDER BY nbLikes DESC";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idShow]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recuperation des commentaires de la serie.");
        }

        return $queryPrepared->fetchAll();
    }

    /**
     * Récupère la liste des réponses à un commentaire par ordre d'anciennetée dans un tableau à 2 dimensions
     * @param $idComment
     * @return array [0 => [id_reply, text_reply, is_modified_reply, `date_reply `, member, comment], 1 => [id_reply, ...], ...]
     */
    function getCommentReplys($idComment)
    {

        $query = "SELECT id_reply, text_reply, is_modified_reply, `date_reply `, member, comment FROM flixadvisor.REPLY WHERE comment = :id ORDER BY `date_reply ` ASC ";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":id" => $idComment]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recherche d'image.");
        }

        return $queryPrepared->fetchAll();
    }
}
