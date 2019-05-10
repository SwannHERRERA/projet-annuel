<div class="mt-20 col-12">

    <?php if (isset($_SESSION['gestion_membre'])): ?>
        <div class="mb-20 alert alert-danger">
            <ul class='mb-0'>
            <?php foreach ($_SESSION['gestion_membre'] as $error): ?>
                <?php if (is_string($error)) echo '<li>' . $error . '</li>';?>
            <?php endforeach; ?>
            <?php unset($_SESSION['gestion_membre']); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="email">Email<span class="">&nbsp;*</span></label>
                    <input type="text" name="email" id="email" class="form-control" required="required"<?= isset($member['email']) ? "disabled" : '' ?> value="<?= $_POST['email'] ?? $member['email'] ?? '' ?>">
                </div>

                <div class="form-group">
                    <label for="genre">Genre</label>
                    <select name="genre" id="genre" class="form-control">
                        <?php if (isset($_POST['genre'])): ?>
                        <option <?php echo ($_POST['genre'] == 0) ? 'selected' : ''?> value="0"></option>
                        <option <?php echo ($_POST['genre'] == 1) ? 'selected' : ''?> value="1">Homme</option>
                        <option <?php echo ($_POST['genre'] == 2) ? 'selected' : ''?> value="2">Femme</option>
                        <option <?php echo ($_POST['genre'] == 3) ? 'selected' : ''?> value="3">autre</option>
                        <?php elseif (isset($member['gender'])): ?>
                        <option <?php echo ($member['gender'] == 0) ? 'selected' : ''?> value="0"></option>
                        <option <?php echo ($member['gender'] == 1) ? 'selected' : ''?> value="1">Homme</option>
                        <option <?php echo ($member['gender'] == 2) ? 'selected' : ''?> value="2">Femme</option>
                        <option <?php echo ($member['gender'] == 3) ? 'selected' : ''?> value="3">autre</option>
                        <?php else: ?>
                        <option value="0" selected></option>
                        <option value="1">Homme</option>
                        <option value="2">Femme</option>
                        <option value="3">autre</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" name="ville" id="ville" class="form-control" value="<?= $_POST['ville'] ?? $member['city'] ?? '' ?>">
                </div>

            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="pseudo">Pseudo<span class="">&nbsp;*</span></label>
                    <input type="text" name="pseudo" id="pseudo" class="form-control" required="required" value="<?= $_POST['pseudo'] ?? $member['pseudo'] ?? '' ?>">
                </div>

                <div class="form-group row no-gutters">
                    <label for="dateNaissance" class="col-12">Date de naissance</label>
                    <div class="col-10">
                        <input type="date" name="dateNaissance" id="dateNaissance" class="form-control"value="<?= $_POST['dateNaissance'] ?? $member['birth_date'] ?? ''?>">
                    </div>
                    <label for="dateNaissance" class="col-2 col-form-label text-center">
                        <i class="fas fa-calendar-alt"></i>
                    </label>
                </div>

                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" name="pays" id="pays" class="form-control" value="<?= $_POST['pays'] ?? $member['country'] ?? '' ?>">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="role">Rôle<span class="">&nbsp;*</span></label>
                    <select id="role" name="role" class="form-control">
                    <?php if(!empty($_POST['role'])): ?>
                        <option <?= $_POST['role'] == 'admin' ? 'selected' : ''?>>admin</option>
                        <option <?= $_POST['role'] == 'user' ? 'selected' : ''?>>user</option>
                    <?php elseif(!empty($member['account_role'])): ?>
                        <option <?= $member['account_role'] == 'admin' ? 'selected' : ''?>>admin</option>
                        <option <?= $member['account_role'] == 'user' ? 'selected' : ''?>>user</option>
                    <?php else : ?>
                        <option selected>admin</option>
                        <option>user</option>
                    <?php endif;?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pwd">Mot de passe<span class="">&nbsp;*</span></label>
                    <input type="password" name="pwd" id="pwd" class="form-control" placeholder="<?= isset($member) ? "Laisser vide pour ne pas changer" : '' ?>">
                </div>
            </div>
        </div>
        <p class="text-center text-md-right">
            <button type="submit" class="btn btn-primary mr-md-10"><?= isset($member) ? 'Modifier' : 'Ajouté un member' ?></button>
        </p>
    </form>
</div>
