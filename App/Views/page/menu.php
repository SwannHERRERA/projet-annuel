<!DOCTYPE html>
<html lang="fr">
<?php $site_url = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
      $site_url .= $_SERVER['HTTP_HOST'];
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="<?= $site_url. '/images/favicon.ico' ?>" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= $site_url . '/css/style.css' ?>">
    <title><?= $page_title ?? "Flix Advisor" ?></title>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
      integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" defer></script>
    <script src="<?= $site_url . "/js/script.js"?>" defer></script>
</head>

<body>
    <main class="menu vh-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="text-center mt-40"><a href="<?= $site_url . '/'?>" class="btn btn-tertiary">Home</a></p>
                    <?php if (!$this->member_model->isConnected()):?>
                        <p class="text-center mt-20"><button type="button" class="btn btn-tertiary" data-toggle="modal" data-target="#modal">
                            Se connecter
                        </button></p>
                        <p class="text-center"><a href="<?= $site_url . '/member/register'?>" class="btn btn-tertiary">S'inscrire</a></p>
                    <?php else : ?>
                        <p class="text-center mt-40"><a href="<?= $site_url . '/member/logout'?>" class="btn btn-tertiary">Déconnexion</a></p>
                    <?php endif ?>
                    <ul class="mb-20">
                            <li><a href="<?= $site_url . '/recherche_avancee' ?>" class="link text-dark"><i class="fas fa-search"></i>&nbsp;Recherche avancée</a>
                            <li><a href="mailto:contact@flixadvisor.fr" class="link text-dark"><i class="fas fa-address-book"></i>&nbsp;Contact</a>
                    </ul>
                    <?php if ($this->member_model->isConnected()):?>
                        <p class="text-center">
                            <a href="<?= $site_url . '/member/parameters' ?>" class="btn btn-secondary"><i class="fas fa-cog"></i></a>
                            <a href="<?= $site_url . '/profil?user=' . $this->member_model->getPseudo() ?>" class="btn btn-secondary"><i class="fas fa-user"></i> </a>
                            <a href="<?= $site_url . '/messages' ?>" class="btn btn-secondary"><i class="fas fa-envelope"></i></a>
                        </p>
                    <?php endif;?>

                    <?php if($this->member_model->is_admin()): ?>
                        <p class="text-center"><a class="btn btn-success" href="<?= $site_url . '/back/member/gestion'?>">Back office</a></p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </main>
</body>
