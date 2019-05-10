<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
class show extends Controller
{
    public $member_model;
    public function __construct()
    {
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model();
        parent::__construct(__CLASS__);
    }

    public function index()
    {
        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'page/show.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }
}
