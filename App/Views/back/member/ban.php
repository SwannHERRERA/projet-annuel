<div class="mt-20 col-12">
    <table class="table table-bordered table-striped mb-20">
        <thead>
            <tr>
                <th class='text-center'>Email</th>
                <th class='text-center'>Pseudo</th>
                <th class='text-center'>Date d'inscription</th>
                <th class='text-center'>type</th>
                <th class='text-center'>Date de bannissement</th>
                <th class='text-center'>Durée</th>
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
                    <td><?= $member['account_status'] ?></td>
                    <td><?= $member['banned_date'] ?></td>
                    <td><?= $member['banned_time'] ?? 'Permanent'?></td>
                    <td class="text-center"><a href="<?= $site_url . '/back/member/unban?member=' . $member['pseudo']?>" class="btn btn-secondary"><i class="fas fa-balance-scale"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
