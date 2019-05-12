<?php
header('Content-Disposition: attachment; filename=utilisateurs.csv');
header('Content-Type: text/csv;  charset=UTF-8');
try{
    $pdo = new PDO('mysql:host=51.75.249.213;dbname=flixadvisor','root', 'fredo');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
} catch (PDOExeption $e) {
    echo 'Connexion impossible';
}
$query = $pdo->prepare('SELECT email, pseudo, date_inscription, account_role FROM MEMBER;');
$query->execute();
$datas = $query->fetchAll();
?>"Email";"Pseudo";"date d'inscription";"role"<?php
foreach ($datas as $data) {
    echo "\n" . '"' . $data->email . '";"' . $data->pseudo . '";"' . $data->date_inscription . '";"' . $data->account_role .'"';
} ?>
