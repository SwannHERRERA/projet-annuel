<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
class Messages extends Controller
{
    private $member_model;
    public function __construct()
    {
        require_once BASEPATH . '/Core/functions.php';
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        parent::__construct(__CLASS__);
    }
    public function index()
    {
        if (!$this->member_model->isConnected()) {
            header('Location: /');
        }
        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'member/messages.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }

}
