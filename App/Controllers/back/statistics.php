<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
class Statistics extends Controller
{
    private $member_model;

    public function __construct()
    {
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        $this->member_model->check_is_admin();
        parent::__construct(__CLASS__);
    }

    public function index()
    {
        header('Location: /back/statistics/user');
    }

    public function user()
    {
        $page_title = 'Statistiques';
        $sous_categories = ['Statistiques utilisateurs' => 'user', 'Statistiques des séries' => 'series'];
        $nb_user = $this->member_model->get_columns(['count(*)']);
        $nb_user_connected = $this->member_model->get_nb_user_connected();
        $gender = $this->member_model->getGenderStats();
        $countrys = $this->member_model->getMembersCountry();
        $citys = $this->member_model->getMembersCity();
        $years_of_users = $this->member_model->getMembersAge();

        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/statistics/user.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }
    public function series()
    {
        require self::MODEL_PATH . 'tv_show_model.php';
        $tv_show_model = new Tv_show_model;
        require self::MODEL_PATH . 'actor_model.php';
        $actor_model = new Actor_model;
        require self::MODEL_PATH . 'network_model.php';
        $network_model = new Network_model;
        require self::MODEL_PATH . 'category_model.php';
        $category_model = new Category_model;
        $page_title = 'Statistiques';
        $sous_categories = ['Statistiques utilisateurs' => 'user', 'Statistiques des séries' => 'series'];
        $nb_series = $tv_show_model->get_columns(['count(*)']);
        $nb_actors = $actor_model->get_columns(['count(*)']);
        $nb_studios = $network_model->get_columns(['count(*)']);
        $status_series = $category_model->getCategoriesStats();
        $TVYearStatusStats = $tv_show_model->getTVYearStatusStat();
        $nb_episode = $tv_show_model->getTVShowsEpisodesCount();
        $nb_saison = $tv_show_model->getTVShowsSeasonCount();


        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/statistics/series.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }
}
