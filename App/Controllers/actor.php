<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
class Actor extends Controller
{
    private $member_model;
    private $actor_model;
    public function __construct()
    {
        require self::MODEL_PATH . 'actor_model.php';
        $this->actor_model = new Actor_model;
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        parent::__construct(__CLASS__);
    }

    public function seach(){
        $result = $this->actor_model->searchActor($_POST['q']);
        echo json_encode($result);
    }
}
