<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Member extends Controller
{
    public $member_model;
    private $pdo;

    public function __construct()
    {
        require_once BASEPATH . '/Core/functions.php';
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
                    $_SESSION['login_modal'][] = "Veuillez valider votre e-mail avant de vous connecter";
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

    public function parameters()
    {
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

    public function changePassword()
    {
        if (!isset($_POST['current_password_modal']) || !isset($_POST['new_password_modal']) || !isset($_POST['confirmation_password_modal'])) {
            header('Location: /');
        } else {
            $pdo = connectDB();
            $query = "SELECT password FROM MEMBER WHERE email = :email";
            $queryPrepared = $pdo->prepare($query);
            $queryPrepared->execute([":email" => $_SESSION['email']]);

            if (password_verify($_POST['current_password_modal'], $queryPrepared->fetch()[0])
                && $_POST['new_password_modal'] == $_POST['confirmation_password_modal']
                && preg_match("#[a-z]#", $_POST['new_password_modal'])
                && preg_match("#[A-Z]#", $_POST['new_password_modal'])
                && preg_match("#\d#", $_POST['new_password_modal'])) {

                $query = "UPDATE MEMBER SET password = :password WHERE email = :email";
                $queryPrepared = $pdo->prepare($query);
                $queryPrepared->execute([
                    ":email" => $_SESSION['email'],
                    ":password" => password_hash($_POST['new_password_modal'], PASSWORD_DEFAULT)
                ]);
                header('Location: /member/parameters');
                exit();
            } else {
                header('Location: /');
            }
        }
    }

    public function delete()
    {
        if ($this->member_model->isConnected()) {
            $pseudo = $this->member_model->getPseudo();
            $this->member_model->delete($pseudo);
            header("Location: /");
            session_destroy();
        }
    }

    public function password_lost()
    {
        if ($this->member_model->isConnected()) {
            header('Location: /');
        }
        if (!empty($_POST)) {
            require BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Form_validation.php';
            $form_validation = new form_validation('password_lost');
            $form_validation->set_rules('email', 'email', ['valid_email']);
            $member = $this->member_model->get($_POST['email']);
            if (isset($member['email'])) {
                $newPassword = substr(Sha1(uniqid() . 'zdnkzdb'), 0, rand(6, 8));
                $this->member_model->update_pass($_POST['email'], $newPassword);

                require_once BASEPATH . DIRECTORY_SEPARATOR . "vendor/autoload.php";
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->CharSet = 'UTF-8';
                    $mail->Host = 'ssl0.ovh.net';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'contact@flixadvisor.fr';
                    $mail->Password = 'EyMtPbDuqyaeM2b';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('contact@flixadvisor.fr', 'Flix Advisor');
                    $mail->addAddress($_POST['email']);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Flix Advisor : Nouveau password';
                    $mail->Body = "Bonjour " . $member["pseudo"] . ",<br>
                                Vous avez fait une demande de nouveau mot de passe.<br>
                                Votre nouveau mot de passe est : " . $newPassword .
                        "<br>A bientôt,<br>
                                L'équipe Flix Advisor<br>
                                https://flixadvisor.fr";
                    //$mail->AltBody = 'non-HTML mail clients';

                    if ($mail->send()) {
                        $_SESSION['success-message'][] = 'Un e-mail de confirmation vous a été envoyé : merci de cliquer sur le lien afin de confirmer votre adresse et terminer votre inscription.';
                    }
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $_SESSION['password_lost'][] = 'Cet utilisateur n\'est pas inscrit';
            }
        }
        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'member/password_lost.php';
        require self::VIEW_PATH . 'layout/footer.php';
        unset($_SESSION['success-message']);
        unset($_SESSION['password_lost']);
    }

    public function submit_contact()
    {
        if (!$this->member_model->isConnected() || !isset($_POST['message']) || strlen($_POST['message']) < 1)
            header('Location: /');
        sendMessage($_SESSION['email'], 'admin@admin.fr', $_POST['message']);
        header('Location: /');
    }

    public function contact()
    {
        if (!$this->member_model->isConnected())
            header('Location: /');
        require self::VIEW_PATH . 'layout/header.php';
        require self::VIEW_PATH . 'member/contact.php';
        require self::VIEW_PATH . 'layout/footer.php';

    }
}
