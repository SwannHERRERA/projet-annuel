<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';

class Profil extends Controller
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
        if (isset($_GET['user'])) {
            $user = getMemberByPseudo($_GET['user']);
            if ($user) {
                require self::VIEW_PATH . 'layout/header.php';
                require self::VIEW_PATH . 'page/profil.php';
                require self::VIEW_PATH . 'layout/footer.php';
            } else
                header('Location: /');
        } else
            header('Location: /');
    }

}
