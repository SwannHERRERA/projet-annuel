<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Member_model extends My_model
{
    public $_table = 'MEMBER';
    public $table_primary_key = "email";
    //ici on met les methode propre a la table

    /**
    * INSERT a user via front
    */
    public function register()
    {
        if (!empty($_POST)) {
            require BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Form_validation.php';
            $form_validation = new Form_validation('register');
            $form_validation->set_rules('email', 'email', ['require', 'valid_email', 'is_unique', ['max_length' => 100]]);
            $form_validation->set_rules('pseudo', 'pseudo', ['trim', 'require', 'is_unique', ['max_length' => 20]]);
            $form_validation->set_rules('password', 'Mot de passe', ['require', ['match' => 'confPassword']]);
            $form_validation->set_rules('genre', '', []);
            $form_validation->set_rules('dateNaissance', '', []);
            $form_validation->set_rules('city', '', ['trim', ['max_length' => 60]]);
            $form_validation->set_rules('country', '', ['trim', ['max_length' => 50]]);
            $form_validation->set_rules('captcha', 'captcha', ['trim']);
            $captcha = strtolower($_POST['captcha']);
            if ($_SESSION['captcha'] != $captcha) {
                $_SESSION['register'][] = 'Le captcha ne corespond pas';
            }
            $query = $this->pdo->prepare("SELECT pseudo FROM MEMBER WHERE pseudo = :pseudo");
            $result = $query->execute([':pseudo' => $_POST['pseudo']]);
            $result = $query->fetch();
            if (!empty($result['pseudo'])) {
                $_SESSION['register'][] = 'Le pseudo existe déjà';
            }
            $query = $this->pdo->prepare("SELECT email FROM MEMBER WHERE email = :email");
            $result = $query->execute([':email' => $_POST['email']]);
            $result = $query->fetch();
            if (!empty($result['email'])) {
                $_SESSION['register'][] = 'L\'email existe déjà';
            }
            if (empty($_SESSION['register'])) {
                $lien = substr(md5($_POST['email'].time().uniqid()), 0, 30);
                $query = $this->pdo->prepare('INSERT INTO ' . $this->_table . ' (email, pseudo, gender, birth_date, photo, city, country, password,account_status, account_role, date_inscription, verified_email)
                VALUES (:email,:pseudo, :gender, :birth_date, :photo, :city, :country, :password, "non-active", "user", :date_inscription, :verified_email)');
                $query->execute([
                    ':email' => $_POST['email'],
                    ':pseudo' => $_POST['pseudo'],
                    ':gender' => $_POST['genre'],
                    ':birth_date' => ($_POST['dateNaissance'] == "")? null : $_POST['dateNaissance'],
                    ':photo' => 'https://flixadvisor.fr/images/default_pp.jpg',
                    ':city' => $_POST['city'],
                    ':country' => $_POST['country'],
                    ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    ':date_inscription' => date("Y-m-d"),
                    ':verified_email' => $lien
                ]);

                require_once BASEPATH . DIRECTORY_SEPARATOR . "vendor/autoload.php";
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 2;
                    $mail->isSMTP();
                    $mail->CharSet = 'UTF-8';
                    $mail->Host = 'ssl0.ovh.net';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'contact@flixadvisor.fr';
                    $mail->Password = '42A2}-7Bq*tQNir';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('contact@flixadvisor.fr', 'Flix Advisor');
                    $mail->addAddress($_POST['email'], $_POST['pseudo']);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Flix Advisor : confirmation d\'adresse e-mail';
                    $mail->Body = "Bonjour " .  $_POST["pseudo"] .",
                    Vous venez de créer un compte sur FlixAdvisor.fr : merci d'avoir rejoint notre communauté !<br>
                    Terminez la création de votre compte et validez votre adresse e-mail<br>
                    en cliquant sur le lien suivant : https://flixadvisor.fr/member/verify?link=" . $lien .
                    "<br>A bientôt,<br>
                    L'équipe Flix Advisor<br>
                    https://flixadvisor.fr";
                    //$mail->AltBody = 'non-HTML mail clients';

                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                header("Location: /member/register/#valid_email");
            }
        }
    }

    /**
    * Back INSERT /UPDATE User
    * @param STRING $member = pseudo
    */
    public function back_insert($member = null)
    {
        $arr_of_post = [];
        require BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Form_validation.php';
        $form_validation = new Form_validation('gestion_membre');
        $form_validation->set_rules('pseudo', 'pseudo', ['trim', 'require', ['max_length' => 20]]);
        if (!isset($member)) {
            $form_validation->set_rules('email', 'email', ['require', 'valid_email', ['max_length' => 100]]);
            $form_validation->set_rules('pwd', 'Mot de passe', ['require']);
        }
        $query = $this->pdo->prepare("SELECT pseudo FROM MEMBER WHERE pseudo = :pseudo");
        $result = $query->execute([':pseudo' => $_POST['pseudo']]);
        $result = $query->fetch();
        if ($_POST['pseudo'] != $member && !empty($result['pseudo'])) {
            $_SESSION['gestion_membre'][] =  'Le pseudo existe déjà';
        }

        if (empty($_SESSION['gestion_membre'])) {
            $arr_of_post = [
                ":pseudo" => $_POST['pseudo'],
                ":genre" => $_POST['genre'],
                ":dateNaissance" => $_POST['dateNaissance'],
                ":ville" => $_POST['ville'],
                ":pays" => $_POST['pays'],
                ":role" => $_POST['role']
            ];
            if (isset($member)) {
                $str= "UPDATE " . $this->_table . " SET
                pseudo = :pseudo,
                gender = :genre,
                birth_date = :dateNaissance,
                city = :ville,
                country = :pays,
                account_role = :role";

                if (!empty($_POST['pwd'])) {
                    $str .= ',password = :pwd';
                    $arr_of_post += ['pwd' => password_hash($_POST['pwd'], PASSWORD_DEFAULT)];
                }
                $arr_of_post += [":old_pseudo" => $member];
                $str .= ' WHERE pseudo=:old_pseudo';
                $update = $this->pdo->prepare($str);
                $update->execute($arr_of_post);
                header('Location: /back/member/gestion');
            } else {
                $str= "INSERT INTO " . $this->_table . "
                (email, pseudo, gender, birth_date, city, country, account_role, password, date_inscription, verified_email, account_status)
                VALUES (:email,:pseudo,:genre,:dateNaissance,:ville,:pays,:role, :pwd, :date_inscription, :verified_email, :account_status)";

                $arr_of_post += [
                    ":email" => $_POST['email'],
                    ':pwd' => password_hash($_POST['pwd'], PASSWORD_DEFAULT),
                    ":date_inscription" => date("Y-m-d"),
                    ":verified_email" => null,
                    ":account_status" => 'actif'
                ];

                $insert = $this->pdo->prepare($str);
                $insert->execute($arr_of_post);
                header('Location: /back/member/gestion');
            }
        }
    }

    /**
    * login
    * @param STRING $email = primary_key
    */
    public function login($email)
    {
        $token = md5($email."cfcccfde;".time().uniqid());
        $token = substr($token, 0, rand(15, 20));
        //On la refera avec le My_model
        $query = $this->pdo->prepare("UPDATE " . $this->_table . " SET token=:token WHERE email=:email AND verified_email IS NULL");
        $query->execute([":token"=>$token,":email"=>$email]);
        $_SESSION["token"] = $token;
        $_SESSION["email"] = $email;
        header("Location: /");
    }

    /**
    * logout
    * @param STRING $email = primary_key
    */
    public function logout($email)
    {
        $queryPrepared = $this->pdo->prepare("UPDATE " . $this->_table . " SET token=null WHERE email=:email ");
        $queryPrepared->execute([":email"=>$email]);
    }

    /**
    * DELETE a user
    * @param STRING $pseudo = UNIQUE AND NOT NULL
    */
    public function delete($pseudo)
    {
        $queryPrepared = $this->pdo->prepare("DELETE FROM " . $this->_table . " WHERE pseudo=:pseudo");
        $queryPrepared->execute([":pseudo"=>$pseudo]);
    }

    /**
    * check is connected
    */
    public function isConnected()
    {
        if (!empty($_SESSION['email']) && !empty($_SESSION['token'])) {
            $email = $_SESSION['email'];
            $token = $_SESSION['token'];
            $queryPrepared = $this->pdo->prepare("SELECT email FROM " . $this->_table . " WHERE email=:email AND token=:token");
            $queryPrepared->execute([
                ":email"=>$email,
                ":token"=>$token
            ]);
            if (!empty($queryPrepared->fetch())) {
                return true;
            }
        }
        return false;
    }

    /**
    * check is admin and redirect if is not admin to modal of connection
    */
    public function check_is_admin()
    {
        $role = '';
        if (isset($_SESSION['email'])) {
            $role = $this->get_columns_where(['account_role'], ['email' => $_SESSION['email']]);
        }
        if ($role[0]['account_role'] != 'admin') {
            $_SESSION['login_modal'][] = 'Vous n\'être pas autorisé a aller dans cette partie du site';
            header('Location: /#modal');
        }
    }

    public function is_admin()
    {
        if (isset($_SESSION['email'])) {
            $role = $this->get_columns_where(['account_role'], ['email' => $_SESSION['email']]);
                return $role[0]['account_role'] === 'admin';
        }
    }

    /**
    * get link for validate the user
    * @param STRING $link = hash
    */
    public function get_link($link)
    {
        $query = $this->pdo->prepare('SELECT email FROM ' . $this->_table . ' WHERE verified_email = :link');
        $query->execute([':link' => $link]);
        return $query->fetch();
    }

    /**
    * UPDATE the user to validate
    * @param STRING $email = primary_key
    */
    public function valid_member($email)
    {
        $query = $this->pdo->prepare("UPDATE " . $this->_table . " SET verified_email = NULL, account_status = 'actif' where email = :email");
        $query->execute([':email' => $email]);
    }

    /**
    * @return ARRAY of int with the nb of user connected for stat in BO
    */
    public function get_nb_user_connected()
    {
        $query = $this->pdo->query("SELECT count(*) FROM " . $this->_table . " WHERE TOKEN IS NOT NULL");
        return $query->fetch();
    }


    public function ban($email, $banType, $time)
    {
        $query = "UPDATE MEMBER SET account_status = :status, banned_date = curdate(), banned_time = :time where email = :email";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([
            ":status" => $banType,
            ":time" => $time,
            ":email" => $email
        ]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors du banissement d'un membre.");
        }
    }

    public function unban($pseudo)
    {
        $query = "UPDATE MEMBER SET account_status = 'actif', banned_date = NULL, banned_time = NULL where pseudo = :pseudo";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":pseudo" => $pseudo]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors du débanissement d'un membre.");
        }
    }

    public function listBannedMembers()
    {
        $query = "SELECT email, pseudo, date_inscription, account_status, banned_date, banned_time FROM MEMBER where account_status != 'actif' AND account_status != 'non-active'";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la récupération des membres bannis.");
        }
        return $queryPrepared->fetchAll();
    }

    public function getGenderStats()
    {
        $query = "SELECT count(*) * 100 / (select count(*) from MEMBER) as nombres, gender FROM MEMBER group by gender";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la récupération des stats des genres.");
        }
        return $queryPrepared->fetchAll();
    }

    public function getMembersCountry()
    {
        $query = "SELECT count(*) as nombres, country FROM MEMBER where country != '' AND country is not null group by country";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la récupération des pays des membres.");
        }
        return $queryPrepared->fetchAll();
    }

    public function getMembersCity()
    {
        $query = "SELECT count(*) as nombres, city FROM MEMBER where city != '' AND city is not null group by city";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la récupération des villes des membres.");
        }
        return $queryPrepared->fetchAll();
    }

    public function getMembersAge()
    {
        $query = "SELECT email, floor(datediff(curdate(), birth_date) / 365) as AGE FROM MEMBER where birth_date is not null";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la récupération de l'age des membres.");
        }
        return $queryPrepared->fetchAll();
    }

    public function getMembersInscriptionStat()
    {
        $query = "SELECT count(*) as nb_inscription, date_inscription from MEMBER group by date_inscription";
        $queryPrepared = $this->pdo->query($query);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la récupération des stats des date d'inscription des membres.");
        }
        return $queryPrepared->fetchAll();
    }

    public function request_parameters($email)
    {
        $query = $this->pdo->prepare("SELECT email, pseudo, password, gender, birth_date, city, country, photo FROM " . $this->_table . " WHERE token IS NOT NULL AND email = :email");
        $query->execute([':email' => $email]);
        return $query->fetch();
    }

    public function update_parameters($email)
    {
        if (!empty($_POST)) {
            $currentUser = $this->request_parameters($email);
            require BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Form_validation.php';
            $form_validation = new Form_validation('update_param');
            $form_validation->set_rules('email', 'email', ['require', 'valid_email', 'is_unique', ['max_length' => 100]]);
            $form_validation->set_rules('pseudo', 'pseudo', ['trim', 'require', 'is_unique', ['max_length' => 20]]);
            $form_validation->set_rules('city', '', ['trim', ['max_length' => 60]]);
            $form_validation->set_rules('country', '', ['trim', ['max_length' => 50]]);

            $query = $this->pdo->prepare("SELECT pseudo FROM MEMBER WHERE pseudo = :pseudo");
            $result = $query->execute([':pseudo' => $_POST['pseudo']]);
            $result = $query->fetch();
            if (!empty($result['pseudo']) && $result['pseudo'] != $currentUser['pseudo']) {
                $_SESSION['update_param'][] = 'Ce pseudonyme est dÃ©jÃ  pris !';
            }

            $query = $this->pdo->prepare("SELECT email FROM MEMBER WHERE email = :email");
            $result = $query->execute([':email' => $_POST['email']]);
            $result = $query->fetch();
            if ($_POST['email'] != $email && !empty($result['email'])) {
                $_SESSION['update_param'][] = 'L\'email existe dÃ©jÃ .';
            }

            if (empty($_SESSION['update_param'])) {
                $query = $this->pdo->prepare('UPDATE ' . $this->_table . ' SET email=:email, pseudo=:pseudo, gender=:gender, birth_date=:birth_date, city=:city, country=:country WHERE email = :email2');
                $query->execute([
                    ':email' => $_POST['email'],
                    ':pseudo' => $_POST['pseudo'],
                    ':gender' => $_POST['genre'],
                    ':birth_date' => ($_POST['dateNaissance'] == "") ? null : $_POST['dateNaissance'],
                    ':city' => $_POST['city'],
                    ':country' => $_POST['country'],
                    ':email2' => $_SESSION['email']
                ]);
            }
        }
    }

    /**
    * @param $nameMember string (pseuso du membre, recherche flexible : ma => marie, manon,...)
    * @return array[array[email,pseudo],...]
    */
    public function searchMember($nameMember)
    {
        $query = "select email, pseudo from flixadvisor.MEMBER where instr(pseudo, :name) >0";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute([":name" => $nameMember]);
        if ($queryPrepared->errorCode() != '00000') {
            var_dump($queryPrepared->errorInfo());
            die("Une erreur est survenue lors de la recherhce des series.");
        }
        return $queryPrepared->fetchAll();
    }
}
