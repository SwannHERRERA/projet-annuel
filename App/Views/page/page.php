<div class="col-md-9 col-lg-10 align-self">
    <h1>Flixadvisor</h1>
    <h2>Les plus vues</h2>
    <?php foreach ($most_vieweds as $most_viewed): ?>
        <a href="<?= $site_url . '/show/' . $most_viewed['image'] ?>">
            <div class="white-card">
                <img class="img-fluid mx-auto d-bloc" src="<?= $most_viewed['image'] ?>" alt="slide 1">
                <p class="text-center"><?= $most_viewed['title'] ?></p>
            </div>
        </a>
    <?php endforeach; ?>

    <h2>Les mieux notées</h2>

    <a href="">
        <img class="img-fluid mx-auto d-bloc" src="<?= $image_1;?>" alt="slide 1">
    </a>
    <p class="text-center"><?= $titre_1?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_2;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_2?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_3;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_3?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_4;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_4?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_5;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_5?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_1;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_1?></p>

    <h2>Les plus récentes</h2>

    <a href="">
        <img class="img-fluid mx-auto d-bloc" src="<?= $image_1;?>" alt="slide 1">
    </a>
    <p class="text-center"><?= $titre_1?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_2;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_2?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_3;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_3?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_4;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_4?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_5;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_5?></p>

    <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_1;?>" alt="slide 2">
    </a>
    <p class="text-center"><?= $titre_1?></p>
</div>
