<div class="col-md-9 col-lg-10">
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
  <form method="POST" class='mt-50 mb-65'>
    <h1>Création de compte</h1>
    <div class="form-row mt-5">
      <div class="form-group col-md-6">
        <label for="email">E-mail<span class="require">&nbsp;*</span></label>
        <input type="text" class="form-control" name="email" id="email" value="<?= $_POST['email'] ?? ''?>">
      </div>
      <div class="form-group col-md-6">
        <label for="pseudo">Pseudonyme<span class="require">&nbsp;*</span></label>
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
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="password">Mot de passe<span class="require">&nbsp;*</span></label>
        <input type="password" class="form-control" id="password" name="password">
      </div>
      <div class="form-group col-md-6">
        <label for="confPassword">Confirmation du mot de passe<span class="require">&nbsp;*</span></label>
        <input type="password" name="confPassword" id="confPassword" class="form-control">
      </div>
    </div>
    <div class="form-row">
      <p class="col-md-6 mt-15 text-center"><img src="<?= $site_url . '/images/captcha.php' ?>" alt="captcha" width="300px" class="img-fluid"></p>
      <div class="form-group col-md-6">
        <label for="captcha">Captcha<span class="require">&nbsp;*</span></label>
        <input type="text" name="captcha" id='captcha' class="form-control">
      </div>
    </div>
    <p>Les champs marqués d'une astérisque * sont obligatoires</p>
    <p class="text-center">
      <button type='submit' class="btn btn-yellow">Envoyer</button>
    </p>
  </form>
</div>


<div class="modal fade" id="valid_email" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Merci</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          Merci de vous être inscrit un email vous a été envoyé pour valider votre compte
        </div>
    </div>
  </div>
</div>
