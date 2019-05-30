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
            <th>Membre connecter</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $nb_user[0]['count(*)']?></td>
            <td><?= $nb_user_connected['count(*)']?></td>
        </tr>
    </tbody>
</table>
<pre>
<?= var_dump($country) ?>
</pre>
