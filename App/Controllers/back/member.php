<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Controller.php';
class Member extends Controller
{
    private $member_model;

    public function __construct()
    {
        require self::MODEL_PATH . 'member_model.php';
        $this->member_model = new Member_model;
        $this->member_model->check_is_admin();
        parent::__construct(__CLASS__);
    }

    public function index()
    {
        header('Location: /back/member/gestion');
    }
    public function gestion()
    {
        $page_title = 'Gestion des membres';
        $sous_categories = ['Gestion des membre' => 'gestion', 'Création d\'une membre' => 'creation', 'Membres bannis' => 'ban'];
        $members = $this->member_model->getMembersList();

        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/member/gestion.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }
    public function creation()
    {
        $page_title = 'Création de membre';
        $sous_categories = ['Gestion des membre' => 'gestion', 'Création d\'une membre' => 'creation', 'Membres bannis' => 'ban'];
        if (!empty($_POST)) {
            $this->member_model->back_insert();
        }

        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/member/form.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }
    public function modification()
    {
        if (!empty($_POST)) {
            $this->member_model->back_insert($_GET['pseudo']);
        }
        $page_title = 'Modification d\'un membre';
        $sous_categories = ['Gestion des membre' => 'gestion', 'Création d\'une membre' => 'creation', 'Membres bannis' => 'ban'];
        if (!empty($_GET['pseudo'])) {
            $member = $this->member_model->get_by('pseudo = "' . $_GET['pseudo'] . '"');
        } else {
            header('Location: /back/member/gestion');
        }

        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/member/form.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }
    public function ban()
    {
        $page_title = 'Membres bannis';
        $sous_categories = ['Gestion des membre' => 'gestion', 'Création d\'une membre' => 'creation', 'Membres bannis' => 'ban'];
        $members = $this->member_model->listBannedMembers();
        require self::VIEW_PATH . 'back/layout/header.php';
        require self::VIEW_PATH . 'back/member/ban.php';
        require self::VIEW_PATH . 'back/layout/footer.php';
    }

    public function ajaxban()
    {
        $result = $this->member_model->get_columns_where(['email'], ['pseudo' => $_POST['pseudo']]);
        echo json_encode($result);
    }
    public function banMember()
    {
        if (!empty($_POST)) {
            require BASEPATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Form_validation.php';
            $form_validation = new form_validation('ban_modal');
            $form_validation->set_rules('email_hidden', 'Email', ['require', 'valid_email']);
            $form_validation->set_rules('nb_day', '', ['']);
            $form_validation->set_rules('raison', 'le champs text', [['max_length' => 100]]);
            $form_validation->set_rules('type', 'veuillez séléctionnez le type', ['require']);
            if (empty($_SESSION['ban_modal'])) {
                // UPDATE MEMBER SET account_status = 'banned' WHERE email = $_POST['email_hidden'] && je sais pas ou on met le text, le nombre de jour et le type
                if ($_POST['type'] == 'permanent'){
                    $this->member_model->ban($_POST['email_hidden'], 'ban-perm', NULL);
                } else {
                    $this->member_model->ban($_POST['email_hidden'], 'ban-temp', $_POST['nb_day']);
                }
                header('Location: /back/member/ban');
            }
        }
    }
    public function unban(){
        $this->member_model->unban($_GET['member']);
        header('Location: /back/member/ban');
    }
    public function delete() {
        $this->member_model->delete($_GET['pseudo']);
        header('Location: /back/member/gestion');
    }
    public function export_csv(){
        header('Content-Disposition: attachment; filename=utilisateurs.csv');
        header('Content-Type: text/csv;  charset=UTF-8');
        try{
            $pdo = new PDO('mysql:host=51.75.249.213;dbname=flixadvisor','root', 'fredo');
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
        } catch (PDOExeption $e) {
            echo 'Connexion impossible';
        }
        $query = $pdo->prepare('SELECT * FROM MEMBER;');
        $query->execute();
        $datas = $query->fetchAll();
        echo "email;pseudo;photo;gender;birth_date;city;country;account_status;account_role;token,verified_email";
        foreach ($datas as $data) {
            echo "\n" . $data['email'] . ";" . $data['pseudo'] . ";" .$data['photo'] . ";" .$data['gender'] . ";"
             . $data['birth_date'] . ";" .$data['city'] . ";" .$data['country'] . ";" .$data['account_status'] . ";" .
             $data['account_role'] . ";" .$data['token'] . ";" .$data['verified_email'];
        }
    }
}
