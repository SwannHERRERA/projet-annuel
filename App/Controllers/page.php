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
}
