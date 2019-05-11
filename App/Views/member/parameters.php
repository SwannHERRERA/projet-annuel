<div class="col-md-9 col-lg-10 align-self">
    <?php if (isset($_SESSION['register'])): ?>
        <div class="mt-30 alert alert-danger">
            <ul class='mb-0'>
                <?php foreach ($_SESSION['register'] as $error): ?>
                    <?php if (is_string($error)) echo '<li>' . $error . '</li>';?>
                <?php endforeach; ?>
                <?php unset($_SESSION['register']); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php var_dump($current_param) ?>

    <form method="POST" class='mt-50 mb-65'>
        <h1>Paramètres</h1>
        <div class="form-row mt-5">
            <div class="form-group col-md-6">
                <label for="email">E-mail</label>
                <input type="text" class="form-control" name="email" id="email" value="<?= $_POST['email'] ?? ''?>">
            </div>
            <div class="form-group col-md-6">
                <label for="pseudo">Pseudonyme</label>
                <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $_POST['pseudo'] ?? ''?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="genre">Genre</label>
                <select name="genre" class="form-control" id="genre">
                    <?php if (isset($_POST['genre'])):?>
                        <option <?php echo ($_POST['genre'] == 0) ? 'selected' : ''?> value="0"></option>
                        <option <?php echo ($_POST['genre'] == 1) ? 'selected' : ''?> value="1">Homme</option>
                        <option <?php echo ($_POST['genre'] == 2) ? 'selected' : ''?> value="2">Femme</option>
                        <option <?php echo ($_POST['genre'] == 3) ? 'selected' : ''?> value="3">Autre</option>
                    <?php else : ?>
                        <option value="0"></option>
                        <option value="1">Homme</option>
                        <option value="2">Femme</option>
                        <option value="3">Autre</option>
                    <?php endif;?>
                </select>
            </div>
            <div class="col-md-6">
                <div class="form-group row no-gutters">
                    <label for="dateNaissance" class="col-12">Date de naissance
                    </label>
                    <div class="col-10">
                        <input type="date" name="dateNaissance" id="dateNaissance" class="form-control"value="<?= $_POST['dateNaissance'] ?? ''?>">
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
                <input type="text" name="country" id="country" class="form-control" value="<?= $_POST['country'] ?? ''?>">
            </div>
            <div class="form-group col-md-6">
                <label for="city">Ville</label>
                <input type="text" class="form-control" name="city" id="city" value="<?= $_POST['city'] ?? ''?>">
            </div>
        </div>

        <p class="text-center">
            <button type='submit' class="btn btn-yellow">Enregistrer</button>
        </p>
    </form>
</div>
