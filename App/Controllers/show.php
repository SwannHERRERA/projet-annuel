<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';

class show extends Controller
{
    public $member_model;

    public function __construct()
    {
        require self::MODEL_PATH . 'member_model.php';
        require_once BASEPATH . '/Core/functions.php';
        $this->member_model = new Member_model();
        parent::__construct(__CLASS__);
    }

    public function index()
    {
        if (!isset($_GET['show'])) {
            header('Location: /');
        }
        $idShow = $_GET['show'];
        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'page/show.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }

    public function follow()
    {
        if (!isset($_POST['show']) || !isset($_POST['status']) || !isset($_POST['mark']) || !isset($_POST['notification']) || !$this->member_model->isConnected())
            header('Location: /');
        addOrRemoveMemberTVShowToFollowingShow($_SESSION['email'], $_POST['show'], $_POST['status'],
            $_POST['notification'] == 'o' ? 'o' : 'n', $_POST['mark'] == '' ? null : $_POST['mark']);
        if ($_POST['status'] == 'termine')
            watchAllEpisodes($_SESSION['email'], $_POST['show']);
        header('Location: /show?show=' . $_POST['show']);
    }

    public function unfollow()
    {
        if (!isset($_GET['show']) || !$this->member_model->isConnected())
            header('Location: /');
        addOrRemoveMemberTVShowToFollowingShow($_SESSION['email'], $_GET['show'], '', '', '');
        unwatchAllEpisodes($_SESSION['email'], $_GET['show']);
        header('Location: /show?show=' . $_GET['show']);
    }

    public function watchAll()
    {
        if (!isset($_GET['show']) || !$this->member_model->isConnected())
            header('Location: /');
        watchAllEpisodes($_SESSION['email'], $_GET['show']);
    }

    public function unwatchAll()
    {
        if (!isset($_GET['show']) || !$this->member_model->isConnected())
            header('Location: /');
        unwatchAllEpisodes($_SESSION['email'], $_GET['show']);
    }

    public function watchEpisode()
    {
        if (!isset($_GET['ep']) || !$this->member_model->isConnected())
            header('Location: /');
        watchEpisode($_SESSION['email'], $_GET['ep']);
    }

    public function unwatchEpisode()
    {
        if (!isset($_GET['ep']) || !$this->member_model->isConnected())
            header('Location: /');
        unwatchEpisode($_SESSION['email'], $_GET['ep']);
    }

    public function updateRating()
    {
        if (!isset($_GET['show']) || !isset($_GET['rate']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        updateMarkMemberTVShowList($_SESSION['email'], $_GET['show'], $_GET['rate']);
    }

    public function enableNotification()
    {
        if (!isset($_GET['show']) || !$this->member_model->isConnected())
            header('Location: /');
        updateNotificationMemberTVShowList($_SESSION['email'], $_GET['show'], 'o');
    }

    public function disableNotification()
    {
        if (!isset($_GET['show']) || !$this->member_model->isConnected())
            header('Location: /');
        updateNotificationMemberTVShowList($_SESSION['email'], $_GET['show'], 'n');
    }
}
