</div>
<!-- <footer class="row">
  <div class="col-md-4">
    <h3>Nous suivre</h3>
    <ul class="d-flex">
      <li class="flex-fill"><a><img src="https://lorempixel.com/300/300" class="img-fluid" alt="..."></a></li>
      <li class="flex-fill"><a><img src="https://lorempixel.com/300/300" class="img-fluid" alt="..."></a></li>
      <li class="flex-fill"><a><img src="https://lorempixel.com/300/300" class="img-fluid" alt="..."></a></li>
      <li class="flex-fill"><a><img src="https://lorempixel.com/300/300" class="img-fluid" alt="..."></a></li>
    </ul>
  </div>
  <div class="col-md-4">
    <h3>Nous Contacter</h3>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Possimus nisi saepe quia quis
      autem consequatur itaque earum exercitationem eius excepturi,</p>
    <a href="#" class="btn">Nous contacter</a>
  </div>
  <div class="col-md-4">
    <h3>Information sur le site</h3>
    <a href="#" class="btn">Mention Légales</a>
  </div>
</footer> -->
<!-- Modal -->
<?php if (!$this->member_model->isConnected()): ?>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Connexion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php if (!empty($_SESSION['login_modal'])): ?>
        <div class="mt-30 alert alert-danger">
          <ul class='mb-0'>
          <?php foreach ($_SESSION['login_modal'] as $error): ?>
            <?php if (is_string($error)) echo '<li>' . $error . '</li>';?>
          <?php endforeach; ?>
          <?php unset($_SESSION['login_modal']); ?>
          </ul>
        </div>
      <?php endif; ?>
        <form action="<?= $site_url . '/member/login'?>" method="POST">
          <div class="form-group">
            <label for="email_modal">Email</label>
            <input type="email" class="form-control" id="email_modal" name="email_modal" value="<?= isset($_SESSION['POST']->email_modal) ? $_SESSION['POST']->email_modal : ''?>">
            <label for="password_modal">Mot de passe</label>
            <input type="password" class="form-control" id="password_modal" name="password_modal">
          </div>
          <p><a href="<?= $site_url . '/member/password_lost' ?>" class="text-light">Mot de passe oublié ?</a></p>
          <p class="text-center"><button type="submit" class="btn btn-connexion">Connexion</button></p>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif ?>

<?php if (isset($_SESSION['POST'])){
  unset($_SESSION['POST']);
}?>

</body>

</html>
