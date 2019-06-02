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
        if ($this->member_model->isConnected()) {
            $user = getMember($_SESSION['email']);
        }
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

    public function updateStatus()
    {
        if (!isset($_GET['show']) || !isset($_GET['status']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        updateStatusMemberTVShowList($_SESSION['email'], $_GET['show'], $_GET['status']);
    }

    public function submitComment()
    {
        $site_url = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
        $site_url .= $_SERVER['HTTP_HOST'];

        if (!isset($_POST['show']) || !isset($_POST['comment']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        $re = '/\[spoil\]([^\[]*)\[\/spoil\]/m';
        $str = $_POST['comment'];

        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        foreach ($matches as $key => $value) {
            $unhideSpoiler = 'unhideSpoiler(\'' . 'spoiler' . $key . '\')';
            $result = '<span class="spoiler" id="spoiler' . $key . '" onclick="' .$unhideSpoiler . '">' . $value[1] . '</span>';
            $str = preg_replace($re, $result, $str, 1);
        }

        $idComment = addTVShowComments($_POST['show'], $_SESSION['email'], $str);
        $comment = getComment($idComment);
        ?>
        <div id="<?= $comment['id_comment'] ?>" class="row mb-10">
            <div class="col-2">
                <div class="row">
                    <div class="col-12">
                        <a target="_blank"
                           href="<?= $site_url . '/profil?user=' . $comment['pseudo'] ?>"><img
                                    src="<?= $comment['photo'] ?>"
                                    class="img-thumbnail" alt="photo profile"></a>
                    </div>
                    <div class="col-12 mt-5 text-center">
                        <button class="btn btn-warning" onclick="deleteComment(<?= $comment['id_comment'] ?>)"><i
                                    class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <a target="_blank"
                           href="<?= $site_url . '/profil?user=' . $comment['pseudo'] ?>"><strong><?= $comment['pseudo'] ?></strong></a>
                        <span class="text-muted">commenté le <?= date('d-m-Y', strtotime($comment['date_comment'])) ?></span>
                    </div>
                    <div class="card-body">
                        <?= $comment['text_comment'] ?>
                    </div>
                </div>
            </div>
            <div class="col-1 align-self-center">
                <div class="row">
                    <div class="col-12 text-center">
                        <button onclick="checkLike(<?= $comment['id_comment'] ?>)"
                                class="btn btn-primary">
                            <i id="thumb<?= $comment['id_comment'] ?>"
                               class="far fa-thumbs-up"></i>
                            <span id="nblikes<?= $comment['id_comment'] ?>">0</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function deleteComment()
    {
        if (!isset($_GET['comment']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        removeTVShowComment($_GET['comment']);
    }

    public function likeComment()
    {
        if (!isset($_GET['comment']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        likeComment($_GET['comment'], $_SESSION['email']);
    }

    public function unlikeComment()
    {
        if (!isset($_GET['comment']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        unlikeComment($_GET['comment'], $_SESSION['email']);
    }

    public function createList()
    {
        if (!isset($_POST['name']) || !isset($_POST['description']) || !isset($_POST['visibility']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        echo addMemberList($_SESSION['email'], $_POST['name'], $_POST['visibility'], $_POST['description']);
    }

    public function deleteList()
    {
        if (!isset($_GET['list']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        deleteMemberList($_GET['list']);
    }

    public function addShowList()
    {
        if (!isset($_GET['list']) || !isset($_GET['show']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        addShowToList($_GET['show'], $_GET['list']);
    }

    public function removeShowList()
    {
        if (!isset($_GET['list']) || !isset($_GET['show']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        removeShowFromList($_GET['show'], $_GET['list']);
    }
}
