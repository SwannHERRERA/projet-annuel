<div class="col-md-9 col-lg-10 align-self">
    <div class="row">
        <div class="col-12">
            <form class="mt-30">
                <input type="hidden" name="token_csrf" value="<?= $token ?>">
                <div class="form-group">
                    <label for="advanced_search">Séries</label>
                    <input type="text" name="search" id="advanced_search" class="form-control" value="">
                </div>
                <div class="row mb-20">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="minimum_rating">Note minimal</label>
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
                        <div class="form-group">
                            <button type="button" id="runtime" class="btn btn-block btn-primary">Durée (en min)</button>
                            <div id="time_pickers" style="display: none;">
                                <? for ($i = 0; $i <= 120; $i += 5): ?>
                                <input type="checkbox" id="runtime<?= $i ?>" value="<?= $i ?>" name="runtimes[]" style="display: none;">
                                <label for="runtime<?= $i ?>">
                                    <div>
                                        <?= $i ?>
                                    </div>
                                </label>
                                <? endfor ?>
                            </div>
                        </div>
                        <div id="network" class="dropdown-check-list" tabindex="100">
                            <span class="anchor">Network</span>
                            <ul class="items">
                                <?php foreach ($networks as $network) : ?>
                                    <li><input type="checkbox" value="<?= $network['id_network'] ?>" id="<?= $network['name_network'] ?>" name="networks[]"/><label for="<?= $network['name_network'] ?>">&nbsp;<?= $network['name_network'] ?></label></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="white-card text-dark mb-20 p-10">
                            <span>Statut</span>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="continuing" name="status" value="continuing">
                                <label class="form-check-label" for="continuing">
                                    En cour
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="ended" name="status" value="ended">
                                <label class="form-check-label" for="ended">
                                    Terminé
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="disregard" name="status" value="">
                                <label class="form-check-label" for="disregard">
                                    Les deux
                                </label>
                            </div>
                        </div>
                        <div id="list1" class="dropdown-check-list mb-20" tabindex="100">
                            <span class="anchor">Genre</span>
                            <ul class="items">
                                <?php foreach ($genders as $gender) : ?>
                                    <li><input type="checkbox" value="<?= $gender['id_category'] ?>" id="<?= $gender['name_category'] ?>" name="gender[]"/><label for="<?= $gender['name_category'] ?>">&nbsp;<?= $gender['name_category'] ?></label></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                            <button type="button" id="first_aired_years" class="btn btn-block btn-primary">Date de premère diffusion</button>
                            <div id="years_pickers" style="display: none;">
                                <? for ($i = 1999; $i < 2019; $i++): ?>
                                <input type="checkbox" id="year<?= $i ?>" value="<?= $i ?>" name="years[]" style="display: none;">
                                <label for="year<?= $i ?>">
                                    <div>
                                        <?= $i ?>
                                    </div>
                                </label>
                                <? endfor ?>
                            </div>
                        <div id="valid_result"></div>
                        <label for="search_actor">Acteur</label>
                        <input type="text" id="search_actor" class="form-control">
                        <div id="ajax_result"></div>
                    </div>
                    <div class="col-12">
                        <p class="text-center">
                            <button type="submit" class="btn btn-primary" name="">Submit</button>
                        </p>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                <?php if (!empty($tv_shows)): ?>
                <?php foreach ($tv_shows as $tv_show) : ?>
                    <div class="card mb-30">
                        <div class="card-content">
                            <div class="card-header">
                                <a href="<?= $site_url . '/show?' . $tv_show['id_show']?>"><h5 class="card-title"><?= $tv_show['name_show'] ?></h5></a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <a href="<?= $site_url . '/show?show=' . $tv_show['id_show']?>"><img src="<?= $tv_show['image_show'] ?>" class="img-fluid" alt="banniere serie <?= $tv_show['name_show'] ?>"></a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="card-text"><?= $tv_show['summary_show'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><?= $tv_show['production_status'] == 'ended' ? 'Terminé' : 'En cour' ?></li>
                                <li class="list-group-item"><?= $tv_show['followers'] ?> Abonnées</li>
                                <li class="list-group-item">Durée moyenne d'un épisode <?= $tv_show['runtime_show'] ?> minutes</li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif ?>
            </div>
        </div>
    </div>
</div>
