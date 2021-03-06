<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
require BASEPATH . '/vendor/autoload.php';
require BASEPATH . '/TheTvdbApi.php';

use RestAPI\TheTvdbApi;


class Tv_show extends Controller
{
    private $tv_show_model;
    private $member_model;
    private $apikey = "PU7E1HBXTFB2K1UL";
    private $api;
    /* Les clés et URLS de bases Création de l'objet TheTvdbApi */
    private $imurl = "https://www.thetvdb.com/banners/";

    public function __construct()
    {
        require_once self::MODEL_PATH . 'tv_show_model.php';
        require_once BASEPATH . '/Core/functions.php';
        $this->tv_show_model = new Tv_show_model;
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        $this->member_model->check_is_admin();
        $this->api = new TheTvdbApi($this->apikey, '', '');
        parent::__construct(__CLASS__);
    }

    public function index()
    {
        header('Location: /back/tv_show/gestion');
    }

    public function gestion()
    {
        $page_title = 'Gestion des Séries';
        $sous_categories = ['Gestion des series' => 'gestion', 'Création d\'une serie' => 'add'];

        $tv_shows = $this->tv_show_model->getTVShowList();
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
        $sous_categories = ['Gestion des series' => 'gestion', 'Création d\'une serie' => 'add'];

        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/tv_show/add.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }

    public function liste()
    {
        $page_title = 'Création d\'une serie';
        $sous_categories = ['Gestion des series' => 'gestion', 'Création d\'une serie' => 'add'];

        /* Obtention du token */
        $this->api->authenticate();
        /* Appel de la fonction search du RESTAPI */
        $results = $this->api->search_series($_POST['query']);

        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/tv_show/liste.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }

    public function detail()
    {
        if (!empty($_POST)) {
            $this->api->authenticate();
            $serie = $this->api->series($_GET['idserie']);

            $result = $this->tv_show_model->insertTV($serie->id, $serie, $this->api, $this->imurl, true);
            header("Location: /back/tv_show/gestion");
        } else {
            $page_title = 'Création d\'une serie';
            $sous_categories = ['Gestion des series' => 'gestion', 'Création d\'une serie' => 'add'];
            $categorie_active = 'add';
            if (empty($_GET['idserie'])) {
                echo 'L\'id de la série n\'est pas renseignée';
            } else {
                $this->api->authenticate();

                $serie = $this->api->series($_GET['idserie']);
                $result = $this->tv_show_model->get_last_updated($serie->id);
                if (!empty($serie)) {
                    require self::VIEW_PATH . 'back/layout/header.php';
                    require self::VIEW_PATH . 'back/tv_show/detail.php';
                    require self::VIEW_PATH . 'back/layout/footer.php';
                } else {
                }
            }
        }
    }

    public function updateLight()
    {
        if (isset($_GET['show'])) {
            $serie = $this->api->series($_GET['show']);
            $this->tv_show_model->insertTV($serie->id, $serie, $this->api, $this->imurl, false);
            header('Location: /back/tv_show/gestion');
        }
        header('Location: /back/tv_show/gestion');
    }

    public function updateHard()
    {
        if (isset($_GET['show'])) {
            $serie = $this->api->series($_GET['show']);
            $this->tv_show_model->insertTV($serie->id, $serie, $this->api, $this->imurl, true);
            header('Location: /back/tv_show/gestion');
        }
        header('Location: /back/tv_show/gestion');

    }

    public function remove()
    {
        var_dump($_GET);
        $this->tv_show_model->removeTVShow($_GET['id']);
        header('Location: /back/tv_show/gestion');
    }

    public function edit()
    {
        if (!empty($_POST)) {
            require BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Form_validation.php';
            $form_validation = new form_validation('');
            $form_validation->set_rules('id_show', 'l\id', ['require']);
            $form_validation->set_rules('name_show', 'l\id', ['require']);
            $this->tv_show_model->update();
        }

        $tv_show = $this->tv_show_model->getTVShow($_GET['id']);

        $page_title = 'Modification d\'une serie';
        $sous_categories = ['Gestion des series' => 'gestion', 'Création d\'une serie' => 'add'];
        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/tv_show/form.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }

    public function updateShowsLight()
    {
        set_time_limit(600);
        $shows = getTVShowList();
        foreach ($shows as $show) {
            echo $show['name_show'] . '<br>';
            $serie = $this->api->series($show['id_show']);
            $this->tv_show_model->insertTV($serie->id, $serie, $this->api, $this->imurl, false);
        }
        header('Location: /back/tv_show/gestion');

    }

    public function updateShowsHard()
    {
        set_time_limit(600);
        $shows = getTVShowList();
        foreach ($shows as $show) {
            $serie = $this->api->series($show['id_show']);
            $this->tv_show_model->insertTV($serie->id, $serie, $this->api, $this->imurl, true);
        }
        header('Location: /back/tv_show/gestion');

    }
}
