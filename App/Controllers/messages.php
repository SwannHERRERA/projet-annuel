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

    public function getMessages()
    {
        if (!$this->member_model->isConnected() || !isset($_GET['pseudo']))
            header('Location: /');
        $messages = getMessages(getMemberByPseudo($_GET['pseudo'])['email'], $_SESSION['email']);
        foreach ($messages as $message) {
            if ($message['sending_member'] == $_SESSION['email']) { ?>
                <div class="row justify-content-end mt-20">
                    <div class="col-md-6">
                        <div class="card bg-secondary">
                            <div class="card-header ">
                                Envoyé le : <?= date('d-m-Y', strtotime($message['date_message'])) ?>
                            </div>
                            <div class="card-body ">
                                <p class="card-text"><?= $message['text_message'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="row justify-content-start mt-20 ">
                    <div class="col-md-6">
                        <div class="card bg-dark">
                            <div class="card-header ">
                                Envoyé le : <?= date('d-m-Y', strtotime($message['date_message'])) ?>
                            </div>
                            <div class="card-body ">
                                <p class="card-text"><?= $message['text_message'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }

    public function sendMessage()
    {
        if (!$this->member_model->isConnected() || !isset($_POST['pseudo']) || !isset($_POST['message']))
            header('Location: /');
        sendMessage($_SESSION['email'], getMemberByPseudo($_POST['pseudo'])['email'], $_POST['message']);
        ?>
        <div class="row justify-content-end mt-20">
            <div class="col-md-6">
                <div class="card bg-secondary">
                    <div class="card-header ">
                        Envoyé le : <?= date('d-m-Y') ?>
                    </div>
                    <div class="card-body ">
                        <p class="card-text"><?= $_POST['message'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
