<div class="mt-20 col-12">

    <?php if (isset($_SESSION['gestion_series'])): ?>
        <div class="mb-20 alert alert-danger">
            <ul class='mb-0'>
            <?php foreach ($_SESSION['gestion_series'] as $error): ?>
                <?php if (is_string($error)) echo '<li>' . $error . '</li>';?>
            <?php endforeach; ?>
            <?php unset($_SESSION['gestion_membre']); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name_show">name show<span class="text-danger">&nbsp;*</span></label>
                    <input type="text" name="name_show" id="name_show" class="form-control" required="required" value="<?= $_POST['name_show'] ?? $tv_show['name_show'] ?? '' ?>">
                </div>

                <div class="form-group">
                    <label for="production_status">production status</label>
                    <select name="production_status" id="production_status" class="form-control">
                    <?php if (isset($_POST['production_status'])): ?>
                        <option <?php echo ($_POST['production_status'] == 'Continuing') ? 'selected' : ''?> value="Continuing">Continuing</option>
                        <option <?php echo ($_POST['production_status'] == 'endend') ? 'selected' : ''?> value="endend">endend</option>
                    <?php elseif (isset($tv_show['production_status'])): ?>
                        <option <?php echo ($tv_show['production_status'] == 'Continuing') ? 'selected' : ''?> value="Continuing">Continuing</option>
                        <option <?php echo ($tv_show['production_status'] == 'endend') ? 'selected' : ''?> value="endend">endend</option>
                    <?php else: ?>
                        <option value="Continuing" selected>Continuing</option>
                        <option value="endend">endend</option>
                    <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_show">id show</label>
                    <input type="text" name="id_show" id="id_show" class="form-control" disabled value="<?= $_POST['id_show'] ?? $tv_show['id_show'] ?? '' ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row no-gutters">
                    <label for="first_aired_show" class="col-12">First aired show</label>
                    <div class="col-10">
                        <input type="date" name="first_aired_show" id="first_aired_show" class="form-control"value="<?= $_POST['first_aired_show'] ?? $tv_show['first_aired_show'] ?? ''?>">
                    </div>
                    <label for="first_aired_show" class="col-2 col-form-label text-center">
                        <i class="fas fa-calendar-alt"></i>
                    </label>
                </div>

                <div class="form-group">
                    <label for="image_show">image show</label>
                    <input type="text" name="image_show" id="image_show" class="form-control" value="<?= $_POST['image_show'] ?? $tv_show['image_show'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label for="runtime_show">runtime show</label>
                    <input type="text" name="runtime_show" id="runtime_show" class="form-control" value="<?= $_POST['runtime_show'] ?? $tv_show['runtime_show'] ?? '' ?>">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="summary_show">summary show<span class="text-danger">&nbsp;*</span></label>
                    <textarea name="summary_show" id="summary_show" class="form-control" rows="5" ><?= $_POST['summary_show'] ?? $tv_show['summary_show'] ?? '' ?></textarea>
                </div>
            </div>
        </div>
        <p class="text-center text-md-right">
            <button type="submit" class="btn btn-primary mr-md-10">Appliqué</button>
        </p>
    </form>
</div>
