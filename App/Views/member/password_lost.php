<div class="col-md-9 col-lg-10 align-self">
    <h1 class="mb-50">Mot de passe oublié</h1>
    <p class="mb-50">Nous vous enverons un mot de passe par email</p>
    <div class="row align-self-center">
        <div class="col-md-6 offset-md-3">
            <? if (!empty($_SESSION['success-message'])): ?>
                <div class="alert alert-success">
                    <ul>
                        <?php foreach ($_SESSION['success-message'] as $message): ?>
                            <li><?= $message ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <? endif; ?>
            <? if (!empty($_SESSION['password_lost'])): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($_SESSION['password_lost'] as $message): ?>
                            <li><?= $message ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <? endif; ?>
            <form class="card bg-tertiary" method="POST" action="">
                <div class="card-body pt-40">
                    <div class="form-group ">
                        <input class="form-control" id="email" name="email" placeholder="Votre email">
                    </div>
                    <p class="text-center">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
<?= var_dump($_SESSION) ?>
