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
            $memberProfil = getMemberByPseudo($_GET['user']);
            if ($memberProfil) {
                require self::VIEW_PATH . 'layout/header.php';
                require self::VIEW_PATH . 'profil/profil.php';
                require self::VIEW_PATH . 'layout/footer.php';
            } else
                header('Location: /');
        } else
            header('Location: /');
    }

    public function profilList()
    {
        if (isset($_GET['list'])) {
            $list = getList($_GET['list']);
            $shows = getListContent($_GET['list']);
            if ($list) {
                require self::VIEW_PATH . 'layout/header.php';
                require self::VIEW_PATH . 'profil/profil_list.php';
                require self::VIEW_PATH . 'layout/footer.php';
            } else
                header('Location: /');
        } else
            header('Location: /');
    }

    public function createList()
    {
        if (!isset($_POST['name']) || !isset($_POST['description']) || !isset($_POST['visibility']) || !$this->member_model->isConnected()) {
            header('Location: /');
        }
        $idList = addMemberList($_SESSION['email'], $_POST['name'], $_POST['visibility'], $_POST['description']);
        ?>
        <tr id="list<?= $idList ?>">
            <th scope="row"><?= $_POST['name'] ?></th>
            <td><?= substr($_POST['description'], 0, 20) . (strlen($_POST['description']) > 20 ? '...' : '') ?></td>
            <td><?= $_POST['visibility'] == 'public' ? 'publique' : 'privée' ?></td>
            <td>
                <button onclick="removeList(<?= $idList ?>)"
                        class="btn btn-warning"><i
                            class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        <?php
    }

    public function updateList()
    {
        if (isset($_SESSION['email']) && isset($_POST['idList']) && isset($_POST['nameList']) && isset($_POST['description']) && isset($_POST['visibilityList'])) {
            updateMemberList($_POST['idList'], $_POST['nameList'], $_POST['visibilityList'], $_POST['description']);
            header('Location: /profil/profilList?list=' . $_POST['idList']);
        } else
            header('Location: /');
    }
}
