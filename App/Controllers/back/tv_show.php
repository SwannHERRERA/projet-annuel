<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
require BASEPATH . '/vendor/autoload.php';
use Nihilarr\TheTvdbApi;

class Tv_show extends Controller
{
    private $tv_show_model;
    private $member_model;

    /* Les clés et URLS de bases Création de l'objet TheTvdbApi */
    private $imurl = "https://www.thetvdb.com/banners/";
    private $apikey = "PU7E1HBXTFB2K1UL";
    private $api;// = new TheTvdbApi ($apikey, "", "");

    public function __construct()
    {
        require_once self::MODEL_PATH . 'tv_show_model.php';
        $this->tv_show_model = new Tv_show_model;
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        $this->member_model->check_is_admin();
        parent::__construct(__CLASS__);
    }
    public function index()
    {
        header('Location: /back/tv_show/gestion');
    }
    public function gestion()
    {
        $page_title = 'Gestion des Séries';
        $sous_categories = ['Gestion des seriés' => 'gestion', 'Création d\'une serie' => 'add', 'Propositions de séries' => 'proposition'];

        $tv_shows = $this->tv_show_model->getAllTVShow();
        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/tv_show/gestion.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }
    public function add()
    {
        if (!empty($_POST['query'])) {
            header('Location: /back/tv_show/liste');
        }

        $page_title = 'Création d\'une serie';
        $sous_categories = ['Gestion des seriés' => 'gestion', 'Création d\'une serie' => 'add', 'Propositions de séries' => 'proposition'];

        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/tv_show/add.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }

    public function liste()
    {
        $page_title = 'Création d\'une serie';
        $sous_categories = ['Gestion des seriés' => 'gestion', 'Création d\'une serie' => 'add', 'Propositions de séries' => 'proposition'];

        /* Obtention du token */
        $this->api->authenticate();
        /* Appel de la fonction search du RESTAPI */
        $results = $this->api->search_series($searchWord);

        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/tv_show/liste.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }

    public function detail()
    {
        $page_title = 'Création d\'une serie';
        $sous_categories = ['Gestion des seriés' => 'gestion', 'Création d\'une serie' => 'add', 'Propositions de séries' => 'proposition'];
        if (empty($_GET['idserie'])) {
            echo 'L\'id de la série n\'est pas renseignée';
        } else {
            $this->api->authenticate();

            $serie = $this->api->series($_GET['idserie']);
            if (!empty($serie)) {
                require self::VIEW_PATH . 'back/layout/header.php';
                require self::VIEW_PATH . 'back/member/detail.php';
                require self::VIEW_PATH . 'back/layout/footer.php';
            } else {
            }
        }
    }

    public function Proposition()
    {
        $page_title = 'Proposition de séries';
        $sous_categories = ['Gestion des seriés' => 'gestion', 'Création d\'une serie' => 'add', 'Propositions de séries' => 'proposition'];
        require self::VIEW_PATH . 'back/layout/footer.php';
        require self::VIEW_PATH . 'back/layout/header.php';
    }
}
