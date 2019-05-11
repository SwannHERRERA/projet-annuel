<!DOCTYPE html>
<html>

<?php
$site_url = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
$site_url .= $_SERVER['HTTP_HOST'];
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$categories = ['member' => 'Membre', 'tv_show' => 'Tv_show','ticket' => 'Ticket', 'statistics' => 'Statistique'];
?>
<head>
  <meta charset="UTF-8">
  <title><?= $page_title ?? 'back_flixAdvisors'?></title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link rel="stylesheet" href="<?= $site_url . '/css/style_back.css'?>">
</head>

<body class='containter-fluid'>
  <div class='row no-gutters vh-100'>
    <aside class='col-md-2'>
      <ul class='pt-20'>
        <?php foreach ($categories as $controller => $categorie) :?>
        <li class='text-center'><a href="<?= $site_url . "/back/" . $controller?>"
            class="<?php if ($uri_segments[2] == $controller) echo "active"; ?>"><?= $categorie?></a>
        </li>
        <?php endforeach;?>
      </ul>
    </aside>
    <div class="col-md-10">
      <main class="container">
        <div class="row">
          <div class="col-12">
            <h1 class="pt-30 pb-20"><?= $page_title; ?></h1>
            <hr>
            <?= '<ul class="onglet">'?>
              <?php foreach ($sous_categories as $sous_categorie => $segment) :
              echo '<li class="';
              echo ($uri_segments[3] == $segment) ? 'active' : '';?>"<?php echo '>';
              echo '<a href="';
              echo $site_url . '/back/' . $uri_segments[2] . '/' . $segment ;
              echo '">';
              echo $sous_categorie;
              echo '</a>';
              echo '</li>';
              endforeach;
              echo '</ul>';?>
          </div>
