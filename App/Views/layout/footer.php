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
          <p><a href="#" class="text-light">Mot de passe oublié ?</a></p>
          <p class="text-center"><button type="submit" class="btn btn-connexion">Connexion</button></p>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
  integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
  integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
  integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="<?= $site_url . "/js/script.js"?>"></script>

<?php if (isset($_SESSION['POST'])){
  unset($_SESSION['POST']);
}?>

<script type="text/javascript">
    var previewImage = document.getElementById("preview"),
        uploadingText = document.getElementById("uploading-text")

    function submitForm(event) {
        // prevent default form submission
        event.preventDefault();
        uploadImage();
    }
    function uploadImage() {
        var imageSelecter = document.getElementById("image-selecter"),
            file = imageSelecter.files[0];

        if (!file)
            return alert("Merci de sélectionner un fichier.");

        // clear the previous image
        previewImage.removeAttribute("src");
        // show uploading text
        uploadingText.style.display = "block";

        // create form data and append the file
        var	formData = new FormData();
        formData.append("image", file);

        // do the ajax part
        var ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                console.log(this.responseText);
                var json = JSON.parse(this.responseText);
                if (!json || json.status !== true)
                    return uploadError(json.error);

                showImage(json.url);
            }
        }
        ajax.open("POST", "https://flixadvisor.fr/upload.php", true);
        ajax.send(formData); // send the form data
    }
    function uploadError(error) {
        // called on error
        alert(error || 'Une erreur a eu lieu.');
    }
    function showImage(url) {
        previewImage.src = url;
        uploadingText.style.display = "none";
    }
</script>

</body>

</html>
