<?php
session_start();
/**
 * Taille par caractère aléatoire
 * Police par caractère aléatoire (devrons se trouver dans un dossier à la racine du projet .ttf)
 * Angle et position par caractère aléatoire
 * Couleur par caractère aléatoire
 * Couleur de fond aléatoire
 * Ajouter des formes géométriques aléatoire avec des couleurs utilisés dans les caractères (Nombre et forme aléatoire)
 * Contrainte : Doit toujours être lisible !!!
 * Note de CC (Code identique = 0)
 */
header("Content-Type: image/png");
$longueur = random_int(6, 8);
$autorized = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
// $randstring = '';
// for ($i = 0; $i < $long; $i++) {
//    $randstring .= $characters[rand(0, strlen($characters) - 1)];
//}
$characters = substr(str_shuffle($autorized), -$longueur);
$_SESSION["captcha"] = strtolower($characters);
$space = 170 / strlen($characters);

$image = imagecreatetruecolor(200, 50);
imageantialias($image, true);
$colors = [];
/*$fonts = getcwd().'/polices/1.ttf',
    getcwd().'/polices/2.ttf',
    getcwd().'/polices/3.ttf',
    getcwd().'/polices/4.ttf',
    getcwd().'/polices/5.ttf',
    getcwd().'/polices/6.ttf',
    getcwd().'/polices/7.ttf'];*/

for ($i = 0; $i < 10; $i++) {
    $colors[] = imagecolorallocate($image, random_int(180, 255), random_int(180, 255), rand(180, 255));
}
imagefill($image, 0, 0, imagecolorallocate($image, random_int(0, 50), random_int(0, 50), random_int(0, 50)));

for ($i = 0; $i < random_int(50,70); $i++) {
    imagesetthickness($image, random_int(1, 2));
    /*
    imagearc($image,
        random_int(1, 400),
        random_int(1, 400),
        random_int(1, 400),
        random_int(1, 400),
        random_int(1, 400),
        random_int(1, 400),
        $colors[random_int(0,9)]);*/
    /*imagecolorallocate($image, random_int(50,100), random_int(50,100), random_int(50,100))*/
    if ($i % 2 == 0) {
        imagettftext($image, random_int(10, 25), random_int(-90, 90), random_int(0, 200), random_int(20, 50), imagecolorallocate($image, random_int(50, 75), random_int(50, 75), random_int(50, 75)), getcwd()."/polices/1.ttf", $autorized[random_int(0, strlen($autorized) - 1)]);
    }
}

$init = 20;
for ($i = 0; $i < strlen($characters); $i++) {
    imagettftext($image, random_int(15, 22), random_int(-30, 30), $init + $i * $space, random_int(25, 45), $colors[random_int(0, 9)], getcwd()."/polices/1.ttf", $characters[$i]);
}

imagepng($image);
