<div class="col-md-9 col-lg-10 align-self">
    <div class="row">
        <div class="col-12">
            <form class="mt-30">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" value="">
                </div>
                <div class="row mb-20">
                    <div class="col-4">
                        <div id="network" class="dropdown-check-list" tabindex="100">
                            <span class="anchor">Network</span>
                            <ul class="items">
                                <?php foreach ($networks as $network) : ?>
                                    <li><input type="checkbox" value="<?= $network['id_network'] ?>" id="<?= $network['name_network'] ?>" name="network[]"/><label for="<?= $network['name_network'] ?>">&nbsp;<?= $network['name_network'] ?></label></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <div id="list1" class="dropdown-check-list" tabindex="100">
                            <span class="anchor">Genre</span>
                            <ul class="items">
                                <?php foreach ($genders as $gender) : ?>
                                    <li><input type="checkbox" value="<?= $gender['id_category'] ?>" id="<?= $gender['name_category'] ?>" name="gender[]"/><label for="<?= $gender['name_category'] ?>">&nbsp;<?= $gender['name_category'] ?></label></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <div id="valid_result"></div>
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
    </div>
</div>
