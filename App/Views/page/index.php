<div class="col-md-9 col-lg-10 align-self">
    <h1>Flixadvisor</h1>
    <h2>Les plus vues</h2>
    <div id="slider_most_viewed">
        <?php foreach ($most_followeds as $most_followed): ?>
            <div class="white-card">
                <a href="<?= $site_url . '/show?show=' . $most_followed['id_show'] ?>">
                    <img class="card-img-top" src="<?= $most_followed['image_show'] ?>" alt="slide <?= $most_followed['name_show'] ?>">
                    <p class="text-center"><?= $most_followed['name_show'] ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <h2>Les mieux notées</h2>
    <div id="highest_rated">
        <?php foreach ($best_shows as $best_show): ?>
            <div class="white-card">
                <a href="<?= $site_url . '/show?show=' . $best_show['id_show'] ?>">
                    <img class="card-img-top" src="<?= $best_show['image_show'] ?>" alt="slide <?= $best_show['name_show'] ?>">
                    <p class="text-center"><?= $best_show['name_show'] ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <h2>Les plus recents</h2>
    <div id="most_recent">
        <?php foreach ($last_updateds as $last_updated): ?>
            <div class="white-card">
                <a href="<?= $site_url . '/show?show=' . $last_updated['id_show'] ?>">
                    <img class="card-img-top" src="<?= $last_updated['image_show'] ?>" alt="image <?= $last_updated['name_show'] ?>">
                    <p class="text-center"><?= $last_updated['name_show'] ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="<?= $site_url . '/js/slider_home.js'?>"></script>
