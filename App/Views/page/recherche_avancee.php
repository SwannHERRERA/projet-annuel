<div class="col-md-9 col-lg-10 align-self">
    <div class="row">
        <div class="col-12">
            <form class="mt-30">
                <!--<input type="hidden" name="token_csrf" value="<?php //echo $token ?>"> -->
                <div class="row mb-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="advanced_search".<b>Nom</b></label>
                            <input type="text" name="search" id="advanced_search" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="search_actor"><b>Acteur / Actrice</b></label>
                        <input type="text" id="search_actor" class="form-control">
                        <div id="ajax_result"></div>
                        <div id="valid_result"></div>
                    </div>
                </div>

                <div class="row mb-20">
                    <div class="col-md-6">
                        <button type="button" id="runtime" class="btn btn-block btn-primary">Durée en minutes</button>
                        <div id="time_pickers" style="display: none;">
                            <?php for ($i = 0; $i <= 120; $i += 5): ?>
                                <input type="checkbox" id="runtime<?= $i ?>" value="<?= $i ?>" name="runtimes[]" style="display: none;">
                                <label for="runtime<?= $i ?>">
                                    <div>
                                        <?= $i ?>
                                    </div>
                                </label>
                            <?php endfor ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <button type="button" id="first_aired_years" class="btn btn-block btn-primary">Année de première diffusion</button>
                        <div id="years_pickers" style="display: none;">
                            <?php for ($i = 1970; $i < 2019; $i++): ?>
                                <input type="checkbox" id="year<?= $i ?>" value="<?= $i ?>" name="years[]" style="display: none;">
                                <label for="year<?= $i ?>">
                                    <div>
                                        <?= $i ?>
                                    </div>
                                </label>
                            <?php endfor ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-20">
                    <div class="col-md-6">
                        <div id="network" class="dropdown-check-list mb-20" tabindex="100">
                            <span class="anchor">Chaîne</span>
                            <ul class="items">
                                <?php foreach ($networks as $network) : ?>
                                    <li><input type="checkbox" value="<?= $network['id_network'] ?>" id="<?= $network['name_network'] ?>" name="networks[]"/><label for="<?= $network['name_network'] ?>">&nbsp;<?= $network['name_network'] ?></label></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="list1" class="dropdown-check-list mb-20" tabindex="100">
                            <span class="anchor">Genre</span>
                            <ul class="items">
                                <?php foreach ($genders as $gender) : ?>
                                    <li><input type="checkbox" value="<?= $gender['id_category'] ?>" id="<?= $gender['name_category'] ?>" name="gender[]"/><label for="<?= $gender['name_category'] ?>">&nbsp;<?= $gender['name_category'] ?></label></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mb-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="minimum_rating">Note minimale</label>
                            <select class="form-control" name="minimum_rating" id="minimum_rating">
                                <option></option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="white-card text-dark mb-20 p-10">
                            <span>Statut</span>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="continuing" name="status" value="continuing">
                                <label class="form-check-label" for="continuing">
                                    En cours
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="ended" name="status" value="ended">
                                <label class="form-check-label" for="ended">
                                    Terminée
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="disregard" name="status" value="">
                                <label class="form-check-label" for="disregard">
                                    Les deux
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                    <div class="col-12">
                        <p class="text-center">
                            <button type="submit" class="btn btn-primary" name="">Chercher</button>
                        </p>
                </div>
            </form>
        </div>
        <div class="row">

                <?php if (!empty($tv_shows)): ?>
                <?php foreach ($tv_shows as $tv_show) : ?>
            <div class="col-md-6">
                    <div class="card mb-30" style="margin-left: 15px; margin-right: 15px">
                        <div class="card-content">
                            <div class="card-header">
                                <a href="<?= $site_url . '/show?show=' . $tv_show['id_show']?>"><h5 class="card-title"><?= $tv_show['name_show'] ?></h5></a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="text-center">
                                            <a href="<?= $site_url . '/show?show=' . $tv_show['id_show']?>"><img src="<?= $tv_show['image_show'] ?>" class="img-fluid" alt="Image série <?= $tv_show['name_show'] ?>"></a>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <p id="summarySearch" class="card-text" style="text-align: justify">
                                            <?= $tv_show['summary_show'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Statut de production : <?= $tv_show['production_status'] == 'ended' ? 'terminé' : 'en cours' ?></li>
                                <?php
                                    $nbFollowers = $tv_show['followers'];
                                    if ($nbFollowers == 0) {
                                        $nbFollowers = "Aucun";
                                    }
                                    $word = $nbFollowers > 1 ? 'membres' : 'membre';
                                    if ($nbFollowers > 1) {
                                        $verb = 'suivent';
                                    } else if ($nbFollowers == 1) {
                                        $verb = 'suit';
                                    } else {
                                        $verb = 'ne suit';
                                    }
                                ?>
                                <li class="list-group-item"><?= $nbFollowers." ".$word. " ".$verb ?> cette série</li>
                                <li class="list-group-item">Durée moyenne d'un épisode : <?= $tv_show['runtime_show'] ?> minutes</li>
                            </ul>
                        </div>
                    </div>
            </div>
                <?php endforeach; ?>
            <?php endif ?>

        </div>
    </div>
</div>
