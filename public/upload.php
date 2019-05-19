<?php

$host = '51.75.249.213';
$user = 'root';
$password = 'fredo';
$database = 'flixadvisor';
$mysqli = new mysqli($host, $user, $password, $database);


try {

    // VERIFICATION DES ERREURS

    if (empty($_FILES['image'])) {
        throw new Exception('Image file is missing');
    }
    $image = $_FILES['image'];

    // check INI error
    if ($image['error'] !== 0) {
        if ($image['error'] === 1) {
            throw new Exception('Max upload size exceeded');
        }
        throw new Exception('Image uploading error: INI Error');
    }

    // check if the file exists
    if (!file_exists($image['tmp_name'])) {
        throw new Exception('Image file is missing in the server');
    }

    $maxFileSize = 2 * 10e6; // in bytes
    if ($image['size'] > $maxFileSize) {
        throw new Exception('Max size limit exceeded');
    }

    // Vérification que le fichier soit bien une image
    $imageData = getimagesize($image['tmp_name']);
    if (!$imageData) {
        throw new Exception('Invalid image');
    }
    $mimeType = $imageData['mime'];

    // Vérification du tipe MIME
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mimeType, $allowedMimeTypes)) {
        throw new Exception('Only JPEG, PNG and GIFs are allowed');
    }

    // PAS D'ERREUR : TRAITEMENT

    $fileExtension = strtolower(pathinfo($image['name'] ,PATHINFO_EXTENSION));
    $fileName = round(microtime(true)).mt_rand().'.'.$fileExtension;
    $path = '/images/upload/'.$fileName;
    $destination = $_SERVER['DOCUMENT_ROOT'].$path;

    if (move_uploaded_file($image['tmp_name'], $destination)) {
        // Création de l'URL finale de l'image
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $domain = $protocol . $_SERVER['SERVER_NAME'];
        $url = $domain.$path;

        $stmt = $mysqli -> prepare('INSERT INTO MEMBER (photo) VALUES (?)');

        if ($stmt && $stmt -> bind_param('s', $url) && $stmt -> execute()) {
            exit(
                json_encode(
                    array(
                        'status' => true,
                        'url' => $url
                    )
                )
            );
        } else {
            throw new Exception('Error in saving into the database');
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