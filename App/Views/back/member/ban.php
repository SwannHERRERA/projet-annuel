<div class="mt-20 col-12">
    <table class="table table-bordered table-striped mb-20">
        <thead>
            <tr>
                <th class='text-center'>Email</th>
                <th class='text-center'>Pseudo</th>
                <th class='text-center'>Date d'inscription</th>
                <th class='text-center'></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member) :?>
                <tr>
                    <td><?php echo $member["email"]?><span class="text-right"><i></i></span></td>
                    <td><?php echo $member['pseudo']?></td>
                    <td>
                        <?php $date = new DateTime($member['date_inscription']);
                        echo $date->format('d-m-Y'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="mt-5 mr-5 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBan">Bannir un membre</button>
    </p>
</div>