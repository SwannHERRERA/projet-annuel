<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-30">Genre</h3>
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <tr>
                        <th>non defini</th>
                        <th>Homme</th>
                        <th>Femme</th>
                        <th>Autres</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $gender[0]['nombres']?>%</td>
                        <td><?= $gender[1]['nombres']?>%</td>
                        <td><?= $gender[2]['nombres']?>%</td>
                        <td><?= $gender[3]['nombres']?>%</td>
                    </tr>
                </tbody>
            </table>
            <h3 class="mb-30">Nombre de membre</h3>
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <tr>
                        <th>Total de membre</th>
                        <th>Membre connectés</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $nb_user[0]['count(*)']?></td>
                        <td><?= $nb_user_connected['count(*)']?></td>
                    </tr>
                </tbody>
            </table>
            <h3 class="mb-30">Pays</h3>
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <tr>
                        <?php foreach ($countrys as $country) : ?>
                            <th><?= $country['country']?></th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($countrys as $country) : ?>
                            <td><?= $country['nombres']?></td>
                        <?php endforeach ?>
                    </tr>
                </tbody>
            </table>
            <h3 class="mb-30">Villes</h3>
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <tr>
                        <?php foreach ($citys as $city) : ?>
                            <th><?= $city['city']?></th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($citys as $city) : ?>
                            <td><?= $city['nombres']?></td>
                        <?php endforeach ?>
                    </tr>
                </tbody>
            </table>
            <h3 class="mb-30">Age</h3>
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <tr>
                        <th>email</th>
                        <th>age</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($years_of_users as $years_of_user) : ?>
                        <tr>
                            <td><?= $years_of_user['email']?></td>
                            <td><?= $years_of_user['AGE']?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
