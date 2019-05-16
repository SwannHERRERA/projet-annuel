<div class="col-md-9 col-lg-10 align-self">
    <h1>Flixadvisor</h1>
    <h2>Les plus vues</h2>
    <div id="slider_most_viewed">
        <?php foreach ($most_vieweds as $most_viewed): ?>
            <div class="white-card">
                <a href="<?= $site_url . '/show?show=' . $most_viewed['id'] ?>">
                    <img class="card-img-top" src="<?= $most_viewed['image'] ?>" alt="slide 1">
                    <p class="text-center"><?= $most_viewed['title'] ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="<?= $site_url . '/js/slider_home.js'?>" async></script>
