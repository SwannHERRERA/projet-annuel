<div class="col-md-9 col-lg-10 align-self">
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

    <form method="POST" class='mt-50 mb-65' onsubmit="submitForm(event);">
        <div class="form-row mt-5">
            <div class="form-group col-md-6" style="text-align: center;">
                <img id="preview" height="200" src="<?= $current_param['photo'] ?>">
            </div>
            <div class="form-group col-md-6">
                <p><b>Changer ma photo de profil :</b><br>
                Formats autorisés : JPEG, PNG et GIF <br>
                Poids maximum : TBD</p>
                <div id="uploading-text" style="display:none;">Téléchargement en cours...</div>
                <input type="file" name="image" id="image-selecter" accept="image/*">
            </div>
        </div>
        <div class="form-row mt-5">
            <div class="form-group col-md-6">
                <label for="email">E-mail</label>
                <input type="text" class="form-control" name="email" id="email" value="<?= $current_param['email'] ?>">
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

    <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#passwordChange">
        Modifier mon mot de passe
    </button>
</div>

<!-- Modal -->
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
                <form action="<?= $site_url . '/member/login'?>" method="POST">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>