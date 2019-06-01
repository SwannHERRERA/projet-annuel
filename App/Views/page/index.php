<div class="col-md-9 col-lg-10 align-self">
    <h1>Flixadvisor</h1>
    <h2>Les plus vues</h2>
    <div id="slider_most_viewed">
        <?php foreach ($most_followeds as $most_followed): ?>
            <div class="white-card">
                <a href="<?= $site_url . '/show?show=' . $most_followed['id_show'] ?>">
                    <img class="card-img-top" src="<?= $most_followed['image_show'] ?>"
                         alt="slide <?= $most_followed['name_show'] ?>">
                    <h3 class="text-center card-title"><?= $most_followed['name_show'] ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <h2>Les mieux notées</h2>
    <div id="highest_rated">
        <?php foreach ($best_shows as $best_show): ?>
            <div class="white-card">
                <a href="<?= $site_url . '/show?show=' . $best_show['id_show'] ?>">
                    <img class="card-img-top" src="<?= $best_show['image_show'] ?>"
                         alt="slide <?= $best_show['name_show'] ?>">
                    <h3 class="text-center card-title"><?= $best_show['name_show'] ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <h2>Les plus recents</h2>
    <div id="most_recent">
        <?php foreach ($last_updateds as $last_updated): ?>
            <div class="white-card">
                <a href="<?= $site_url . '/show?show=' . $last_updated['id_show'] ?>">
                    <img class="card-img-top" src="<?= $last_updated['image_show'] ?>"
                         alt="image <?= $last_updated['name_show'] ?>">
                    <h3 class="text-center card-title"><?= $last_updated['name_show'] ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (isset($_SESSION['email'])) {
        $personnalisedShows = getMemberPersonnalisedShows($_SESSION['email']);
        echo "<h2>Recommandations personnalisées</h2>";
        echo '<div id="recommandations">';
        foreach ($personnalisedShows as $show) { ?>
            <div class="white-card">
                <a href="<?= $site_url . '/show?show=' . $show['id_show'] ?>">
                    <img class="card-img-top" src="<?= $show['image_show'] ?>"
                         alt="image <?= $show['name_show'] ?>">
                    <h3 class="text-center card-title"><?= $show['name_show'] ?></h3>
                </a>
            </div>

        <?php }
        echo "</div>";
    } ?>
</div>

<script src="<?= $site_url . '/js/slider_home.js' ?>"></script>
