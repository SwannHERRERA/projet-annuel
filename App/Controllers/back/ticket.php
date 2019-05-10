<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
class Ticket extends Controller
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }
    public function index()
    {
        header('Location: /back/ticket/contact');
    }
    public function contact()
    {
        $page_title = 'Contacts';
        require self::VIEW_PATH . 'back/layout/header.php';
        echo 'page contact';
    }
    public function Signal_member()
    {
        $page_title = 'Signalement membre';
        require self::VIEW_PATH . 'back/layout/header.php';
    }
    public function Signal_comment()
    {
        $page_title = 'Signalement commentaire';
        require self::VIEW_PATH . 'back/layout/header.php';
    }
    public function old_ticket()
    {
        $page_title = 'Tickets archivés';
        require self::VIEW_PATH . 'back/layout/header.php';
    }
}
