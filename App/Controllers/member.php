<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
class Member extends Controller
{
    public $member_model;

    public function __construct()
    {
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        parent::__construct(__CLASS__);
    }

    public function register()
    {
        if (!empty($_POST)) {
            $this->member_model->register();
        }
        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'member/register.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }
    public function login()
    {
        if (!empty($_POST['email_modal'])) {
            $_SESSION['POST'] = new stdClass();
            $_SESSION['POST']->email_modal = $_POST['email_modal'];
        }
        require BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Form_validation.php';
        $form_validation = new form_validation('login_modal');
        $form_validation->set_rules('email_modal', 'email', ['valid_email']);
        $form_validation->set_rules('password_modal', 'Mot de passe', ['require']);

        if (empty($_SESSION['login_modal'])) {
            $result = $this->member_model->get_columns_where(['password', 'account_status'], ['email' => $_POST['email_modal']]);
            if (password_verify($_POST["password_modal"], $result[0]["password"])) {
                if ($result[0]["account_status"] != 'actif') {
                    $_SESSION['login_modal'][] = "Veuillez valider votre email avant de vous connecter";
                    header("Location: /#modal");
                } else {
                    $this->member_model->login($_POST['email_modal']);
                }
            } else {
                $_SESSION['login_modal'][] = "Le mot de passe ou l'email est erroné";
                header("Location: /#modal");
            }
        } else {
            $_SESSION['login_modal'][] = "Le mot de passe ou l'email est erroné";
            header("Location: /#modal");
        }
    }
    public function logout()
    {
        if (isset($_SESSION['email'])) {
            $this->member_model->logout($_SESSION['email']);
            unset($_SESSION['email']);
            unset($_SESSION['token']);
        }
        header("Location: /");
    }
    public function verify()
    {
        //SELECT email FROM MEMBER WHERE verified_email = $link
        $result = $this->member_model->get_link($_GET['link']);
        if ($result['email'] != false) {
            $this->member_model->valid_member($result['email']);
        }
        header("Location: /#modal");
    }

    public function parameters() {
        if (!$this->member_model->isConnected()) {
            header('Location: /');
        }
        if (!empty($_POST)) {
            $this->member_model->update_parameters($_SESSION['email']);
        }
        $current_param = $this->member_model->request_parameters($_SESSION['email']);
        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'member/parameters.php';
        require self::VIEW_PATH . 'layout/footer.php';
    }
}
