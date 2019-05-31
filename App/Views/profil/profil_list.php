<div class="col-md-9 col-lg-10 align-self">
    <div class="row mt-20 ml-20">
        <div class="col-9">
            <h1 class="h2">Liste : <?= $list['name_list'] ?></h1>
            Liste de : <a
                    href="<?= '/profil?user=' . getMember($list['member'])['pseudo'] ?>"><?= ucfirst(getMember($list['member'])['pseudo']) ?></a>
            <?php if (isset($_SESSION['email'])) {
                $user = getMember($_SESSION['email']);
                if ($user['email'] === $list['member'] || $user['account_status'] == 'admin') {
                    ?>
                    <button type="button" class="btn btn-primary ml-20" data-toggle="modal" data-target="#updateList">
                        <i
                                class="fas fa-edit"></i>
                    </button>
                    <div class="modal fade" id="updateList" tabindex="-1" role="dialog"
                         aria-labelledby="updateList" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier la liste</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?= '/profil/updateList' ?>" method="post">
                                        <input type="hidden" name="idList" value="<?= $list['id_list'] ?>">
                                        <label for="nameList">Nom</label>
                                        <input id="nameList" type="text" class="form-control" name="nameList"
                                               value="<?= $list['name_list'] ?>">
                                        <label class="mt-20" for="description">Description</label>
                                        <input id="description" type="text" class="form-control" name="description"
                                               value="<?= $list['description_list'] ?>">
                                        <label class="mt-20" for="visibilityList">Visibilité</label>
                                        <select id="visibilityList" class="form-control" name="visibilityList">
                                            <option value="public" selected>Publique</option>
                                            <option value="private">Privée</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary mt-20">Modifier</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
            <br><br>
            Date de création : <?= date('d-m-Y', strtotime($list['date_list'])) ?>
            <br><br>
            Description : <?= $list['description_list'] ?>
            <br><br>
            Nombre de séries : <?= sizeof($shows) ?>
        </div>
        <div class="col-12 mt-20">
            <hr>
            <h5>Contenu :</h5>
            <div class="row">
                <div class="col-md-2">
                    <label class="mt-20" for="searchShow">Rechercher</label>
                    <input type="text" class="form-control" id="searchShow" onkeyup="searchShow()">
                </div>
            </div>
            <table class="table table-striped table-dark align-self-center mt-20">
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Statut</th>
                    <th scope="col">Date de diffusion</th>
                    <th scope="col">Synopsis</th>
                    <th scope="col">Genres</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody id="list">
                <?php foreach ($shows as $show) { ?>
                <tr id="<?= $show['id_show'] ?>">
                    <th scope="row"><a href="<?= '/show?show=' . $show['id_show'] ?>">
                            <img src="<?= $show['image_show'] ?>" class="img-thumbnail w-50" alt="image show">
                            <br>
                            <span><?= $show['name_show'] ?></span>
                        </a></th>
                    <td class="align-middle"><?= $show['production_status'] ?></td>
                    <td class="align-middle"><?= date('d-m-Y', strtotime($show['first_aired_show'])) ?></td>
                    <td class="align-middle"><?= substr($show['summary_show'], 0, 180) . (strlen($show['summary_show']) > 180 ? '...' : '') ?></td>
                    <td class="align-middle"><?php
                        $genres = getShowCategories($show['id_show']);
                        $str = '';
                        foreach ($genres as $genre)
                            $str .= ' ' . $genre['name_category'] . ',';
                        echo rtrim($str, ',')
                        ?></td>
                    <td class="align-middle">
                    <?php
                    if (isset($_SESSION['email'])) {
                        $user = getMember($_SESSION['email']);
                        if ($user['email'] === $list['member'] || $user['account_status'] == 'admin') {
                            ?>
                            <button onclick="removeShowList(<?= $show['id_show'] . ',' . $list['id_list'] ?>)"
                                    class="btn btn-warning"><i
                                        class="fas fa-trash"></i>
                            </button>
                            </td>
                            </tr>
                        <?php }
                    } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?= $site_url . '/js/list.js' ?>"></script>