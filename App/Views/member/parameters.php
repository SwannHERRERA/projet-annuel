<div class="col-md-9 col-lg-10 align-self">

    <script src="<?= $site_url . "/js/download.js" ?>" defer></script>
    <script src="<?= $site_url . "/js/canvas.js" ?>" defer></script>

    <?php if (isset($_SESSION['update_param'])): ?>
        <div class="mt-30 alert alert-danger">
            <ul class='mb-0'>
                <?php foreach ($_SESSION['update_param'] as $error): ?>
                    <?php if (is_string($error)) echo '<li>' . $error . '</li>';?>
                <?php endforeach; ?>
                <?php unset($_SESSION['update_param']); ?>
            </ul>
        </div>
    <?php endif; ?>

    <h1>Paramètres</h1>

    <form method="POST" class='mt-50' onchange="submitForm(event)" enctype='multipart/form-data'>
        <div class="form-row mt-5">
            <div class="form-group col-md-6" style="text-align: center;">
                <img id="preview" width="200" src="<?= $current_param['photo'] ?>">
            </div>
            <div class="form-group col-md-6">
                <p><b>Changer ma photo de profil :</b><br>
                Formats autorisés : JPEG, PNG et GIF <br></p>
                <div id="uploading-text" style="display:none;">Téléchargement en cours...</div>
                <input type="file" name="image" id="image-selecter" accept="image/*">
                <br><br>
                <button type="button" class="btn d-none d-md-block btn-info btn-block" data-toggle="modal" data-target="#canvas">
                    Dessiner ma photo de profil
                </button>
            </div>
        </div>
    </form>
        <hr>
        <form method="POST" action="">
            <div class="form-row mt-5">
                <div class="form-group col-md-6">
                    <label for="email">E-mail</label>
                    <input type="text" class="form-control" name="email" id="email" disabled value="<?= $current_param['email'] ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="pseudo">Pseudonyme</label>
                    <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $current_param['pseudo']?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="genre">Genre</label>
                    <select name="genre" class="form-control" id="genre">
                        <option <?php echo ($current_param['gender'] == 0) ? 'selected' : ''?> value="0"></option>
                        <option <?php echo ($current_param['gender'] == 1) ? 'selected' : ''?> value="1">Homme</option>
                        <option <?php echo ($current_param['gender'] == 2) ? 'selected' : ''?> value="2">Femme</option>
                        <option <?php echo ($current_param['gender'] == 3) ? 'selected' : ''?> value="3">Autre</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-group row no-gutters">
                        <label for="dateNaissance" class="col-12">Date de naissance
                        </label>
                        <div class="col-10">
                            <input type="date" name="dateNaissance" id="dateNaissance" class="form-control" value="<?= $current_param['birth_date'] ?>">
                        </div>
                        <label for="dateNaissance" class="col-2 col-form-label text-center">
                            <i class="fas fa-calendar-alt"></i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="country">Pays</label>
                    <input type="text" name="country" id="country" class="form-control" value="<?= $current_param['country'] ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="city">Ville</label>
                    <input type="text" class="form-control" name="city" id="city" value="<?= $current_param['city']?>">
                </div>
            </div>

            <p class="text-center">
                <button type='submit' name="submit" class="btn btn-yellow">Enregistrer</button>
            </p>
        </form>

    <hr>

    <div class="form-row mt-5">
        <div class="form-group col-md-6">
            <button type="button" class="btn btn-info btn-block mb-30" data-toggle="modal" data-target="#passwordChange">
                Modifier mon mot de passe
            </button>
        </div>
        <div class="form-group col-md-6">
            <button onclick="confirmAccountDelete('<?=$site_url."/member/delete"?>')" class="btn btn-info btn-block mb-30">
                Supprimer mon compte
            </button>
        </div>
    </div>



</div>

<!-- Modal Canvas-->
<div class="modal fade" id="canvas" tabindex="-1" role="dialog" aria-labelledby="modalCanvas" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCanvas">Dessiner ma photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <form id="canvasImage" method="POST">
                    <div class="row">
                        <div class="col" style="text-align: center;">
                            <canvas width="400" id="can" height="400" style="background-color: #000000; border:2px solid;"></canvas>
                            <img id="canvasimg" style="display:none;">
                        </div>
                    </div>
                        <div id="green" class="color-picker" onclick="color(this)"></div>
                        <div id="yellow" class="color-picker" onclick="color(this)"></div>
                        <div id="blue" class="color-picker" onclick="color(this)"></div>
                        <div id="red" class="color-picker" onclick="color(this)"></div>
                        <div id="orange" class="color-picker" onclick="color(this)"></div>
                        <div id="white" class="color-picker" onclick="color(this)"></div>
                        <div id="black" class="color-picker" onclick="color(this)"></div>
                    </div>
                </form>
                <div class="modal-footer d-flex justify-content-between">
                    <input type="button" class="btn btn-danger" value="Tout effacer" id="clr" size="23" onclick="erase()">
                    <button type="button" class="btn btn-primary" id="btn" onclick="save()">Sauvegarder</button>
                </div>
        </div>
        </div>
    </div>
</div>


<!-- Modal Password -->
<div class="modal fade" id="passwordChange" tabindex="-1" role="dialog" aria-labelledby="modalPassWordChangeTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPassWordChangeTitle">Modifier mon mot de passe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= $site_url . '/member/changePassword'?>" method="POST">
                    <div class="form-group">
                        <label for="current_password_modal">Mot de passe actuel :</label>
                        <input type="password" class="form-control" id="current_password_modal" name="current_password_modal">
                        <br>
                        <label for="new_password_modal">Nouveau mot de passe :</label>
                        <input type="password" class="form-control" id="new_password_modal" name="new_password_modal">
                        <br>
                        <label for="confirmation_password_modal">Confirmation du nouveau mot de passe :</label>
                        <input type="password" class="form-control" id="confirmation_password_modal" name="confirmation_password_modal">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
