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
        $image_1 = $this->tv_show_model->getImage('79168');
        $image_2 = $this->tv_show_model->getImage('80534');
        $image_3 = $this->tv_show_model->getImage('121361');
        $image_4 = $this->tv_show_model->getImage('278155');
        $image_5 = $this->tv_show_model->getImage('74796');

        $titre_1 = $this->tv_show_model->getName('79168');
        $titre_2 = $this->tv_show_model->getName('80534');
        $titre_3 = $this->tv_show_model->getName('121361');
        $titre_4 = $this->tv_show_model->getName('278155');
        $titre_5 = $this->tv_show_model->getName('74796');

        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'page/index.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }
    public function page(){
        $most_vieweds = [
            [
                'id' => '79168',
                'image' => $this->tv_show_model->getImage('79168'),
                'title' => $this->tv_show_model->getName('79168')
            ],
            [
                'id' => '80534',
                'image' => $this->tv_show_model->getImage('80534'),
                'title' => $this->tv_show_model->getName('80534')
            ],
            [
                'id' => '121361',
                'image' => $this->tv_show_model->getImage('121361'),
                'title' => $this->tv_show_model->getName('121361')
            ],
            [
                'id' => '278155',
                'image' => $this->tv_show_model->getImage('278155'),
                'title' => $this->tv_show_model->getName('278155')
            ],
            [
                'id' => '74796',
                'image' => $this->tv_show_model->getImage('74796'),
                'title' => $this->tv_show_model->getName('74796')
            ]
        ];

        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'page/page.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }
}
