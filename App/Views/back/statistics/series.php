<div class="container">
    <div class="row">
        <div class="col-md-4">
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <tr>
                        <th>Nombre de séries</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $nb_series[0]['count(*)'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <tr>
                        <th>Nombre d'acteurs</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $nb_actors[0]['count(*)'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <tr>
                        <th>Nombre de Studio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $nb_studios[0]['count(*)'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h3 class="mb-20">Genre des series</h3>
            <table class="table table-striped table-bordered text-center mb-20">
                <tbody>
                    <?php foreach ($status_series as $status_serie) : ?>
                        <tr>
                            <th class="align-middle"><?= $status_serie['name_category'] ?></th>
                            <td>
                                <table class="table mx-auto">
                                    <tr>
                                        <th>Nombre de show</th>
                                        <th>utilisation</th>
                                    </tr>
                                    <tr>
                                        <td><?= $status_serie['used']?></td>
                                        <td><?= $status_serie['stat_used']?>%</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h3 class="mb-20">Année des séries</h3>
            <table class="table table-striped table-bordered text-center mb-20">
                <thead>
                    <th>Année</th>
                    <th>Nombre de show</th>
                    <th>continuing</th>
                    <th>ended</th>
                </thead>
                <tbody>
                    <?php foreach ($TVYearStatusStats as $TVYearStatusStat): ?>
                    <tr>
                        <td><?= $TVYearStatusStat['year'] ?></td>
                        <td><?= $TVYearStatusStat['nb_show'] ?></td>
                        <td><?= $TVYearStatusStat['continuing'] ?></td>
                        <td><?= $TVYearStatusStat['ended'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
