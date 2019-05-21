<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
class Page extends Controller
{
    private $member_model;
    private $tv_show_model;
    public function __construct()
    {
        require self::MODEL_PATH . 'tv_show_model.php';
        $this->tv_show_model = new Tv_show_model;
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        parent::__construct(__CLASS__);
    }
    public function index()
    {
        $most_followeds = $this->tv_show_model->get10MostFollowedShows();
        $last_updateds = $this->tv_show_model->get10LastUpdatedShows();
        $best_shows = $this->tv_show_model->get10BestShows();

        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'page/index.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }

    public function menu()
    {
        require self::VIEW_PATH . 'page/menu.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }

    /**
    * Methode qui renvoie le resutat de la recheche en AJAX
    */
    public function recherche()
    {
        require self::MODEL_PATH . 'actor_model.php';
        $actor_model = new Actor_model;
        $result = $this->tv_show_model->searchTVShow($_POST['q']);
        $result += $actor_model->searchActor($_POST['q']);
        $result += $this->member_model->searchMember($_POST['q']);
        echo json_encode($result);
    }

    /**
    * Page de recherche complète
    */
    public function recherche_avancer()
    {
        if (!empty($_GET)){
            $q = $_GET['search'];
        }
        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'page/recherche_avancer.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }
}
