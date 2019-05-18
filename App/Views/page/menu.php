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
</head>

<body>
    <main class="menu vh-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php if (!$this->member_model->isConnected()):?>
                        <p class="text-center mt-40"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal">
                            Se connecter
                        </button></p>
                        <p class="text-center"><a href="<?= $site_url . '/member/register'?>" class="btn btn-primary">S'inscrire</a></p>
                    <?php else : ?>
                        <p class="text-center mt-40"><a href="<?= $site_url . '/member/logout'?>" class="btn btn-primary">Déconnexion</a></p>
                    <?php endif ?>
                    <ul class="mb-20">
                        <li><a href="#" class="link"><i class="fas fa-search"></i>&nbsp;Recherche avancée</a>
                        <li><a href="#" class="link"><i class="fas fa-flask"></i>&nbsp;Surprenez-moi !</a>
                        <li><a href="#" class="link"><i class="fas fa-plus"></i>&nbsp;Proposer une série</a>
                        <li><a href="#" class="link"><i class="fas fa-address-book"></i>&nbsp;Contact</a>
                    </ul>
                    <?php if ($this->member_model->isConnected()):?>
                        <p class="text-center">
                            <a href="#" class="btn btn-secondary"><i class="fas fa-cog"></i></a>
                            <a href="#" class="btn btn-secondary"><i class="fas fa-question"></i></a>
                            <a href="#" class="btn btn-secondary"><i class="fas fa-tv"></i></a>
                        </p>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </main>
</body>
