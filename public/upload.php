<?php
session_start();

define('BASEPATH', dirname(__DIR__));
require '../Core/functions.php';

$pdo = connectDB();

try {

    // VERIFICATION DES ERREURS

    if (empty($_FILES['image'])) {
        throw new Exception('Image manquante.');
    }
    $image = $_FILES['image'];

    // Vérification des erreurs liées au ini.php
    if ($image['error'] !== 0) {
        if ($image['error'] === 1) {
            throw new Exception('La taille du fichier téléchargé excède la valeur maximale.');
        }
        throw new Exception('Erreur du téléchargement (ini.php)');
    }

    // Vérification de l'existence du fichier
    if (!file_exists($image['tmp_name'])) {
        throw new Exception('L\'image est absente du serveur.');
    }

    $maxFileSize = 2 * 10e6; // in bytes
    if ($image['size'] > $maxFileSize) {
        throw new Exception('Poids maximal de l\'image dépassé.');
    }

    // Vérification que le fichier soit bien une image
    $imageData = getimagesize($image['tmp_name']);
    if (!$imageData) {
        throw new Exception('Fichier non valide.');
    }
    $mimeType = $imageData['mime'];

    // Vérification du type MIME
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mimeType, $allowedMimeTypes)) {
        throw new Exception('Seuls les formats JPEG, PNG et GIFs sont autorisés.');
    }

    // PAS D'ERREUR : TRAITEMENT
    $fileExtension = strtolower(pathinfo($image['name'] ,PATHINFO_EXTENSION));
    $fileName = round(microtime(true)).mt_rand().'.'.$fileExtension;
    $path = '/images/upload/'.$fileName;
    $destination = $_SERVER['DOCUMENT_ROOT'].$path;

    if (move_uploaded_file($image['tmp_name'], $destination)) {
        // Création de l'URL finale de l'image
        $protocol = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://');
        $domain = $protocol . $_SERVER['SERVER_NAME'];
        $url = $domain.$path;

        $stmt = $pdo -> prepare('UPDATE MEMBER SET photo = :photo WHERE email = :email');

        if ($stmt && $stmt -> execute([':photo' => $url, ':email' => $_SESSION['email']])) {
            exit(
            json_encode(
                array(
                    'status' => true,
                    'url' => $url
                )
            )
            );
        } else {
            throw new Exception('Erreur lors de l\'enregistrement en base de données.');
        }
    }


} catch (Exception $e) {

    exit(json_encode(
        array (
            'status' => false,
            'error' => $e -> getMessage()
        )
    ));

}
