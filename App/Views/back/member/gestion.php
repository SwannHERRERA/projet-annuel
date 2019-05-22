<div class="mt-20 col-12">
  <table class="table table-bordered table-striped mb-20">
    <thead>
      <tr>
        <th class='text-center'>Email</th>
        <th class='text-center'>Pseudo</th>
        <th class='text-center'>Date</th>
        <th class='text-center'>Role</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($members as $member) :?>
      <tr>
          <td><?= $member["email"]?><span class="text-right"><i></i></span></td>
          <td><?= $member['pseudo']?></td>
          <td>
              <?php $date = new DateTime($member['date_inscription']);
              echo $date->format('d-m-Y'); ?>
          </td>
          <td>
              <?php echo $member['account_role']?><span class="d-inline-block float-right"><a><i class="fas fa-edit"></i></a></span>
          </td>
          <td class="text-center">
              <a href="<?= $site_url . '/back/member/modification?pseudo=' . $member['pseudo']?>"><i class="fas fa-edit"></i></a>
              <a onclick="confdel('<?= $site_url . '/back/member/delete?pseudo=' . $member['pseudo']?>')" class="text-danger"><i class="fas fa-trash-alt"></i></a>
              <button class="open-modal text-danger" data-toggle="modal" id="<?= $member['pseudo']?>" data-target="#modal_ban"><i class="fas fa-ban"></i></button>
          </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p class="mt-5 mr-5 text-right">
    <a href="<?= $site_url . '/export.php'?>" class="btn btn-primary">exporter en CSV</a>
  </p>
</div>


<div class="modal fade" id="modal_ban" tabindex="-1" role="dialog" aria-labelledby="modal_ban" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Bannir un membre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex mb-20">
                      <div class="mr-auto" id="email_modal"></div>
                      <div id="pseudo_modal"></div>
                </div>
                <form method="POST" action="<?= $site_url . '/back/member/banMember'?>">
                <div class="row">
                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="permanent" checked name="type" id="permanent">
                            <label class="form-check-label" for="permanent">
                            Permanent
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="temporaire" name="type" id="temporaire">
                            <label class="form-check-label" for="temporaire">
                            Temporaire
                            </label>
                        </div>
                    </div>
                    <div class="col-6 align-self-end">
                        <select class="select-day form-control form-control-sm" name="nb_day" id="inputState">
                            <option selected>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>15</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="email_hidden" id="email_hidden">
                <div class="form-group mt-20">
                    <textarea class="form-control" name="raison" row="3"></textarea>
                </div>
                <p class="text-center"><button type="submit" class="btn btn-primary">Valider</button></p>
            </div>
        </div>
    </div>
  </div>
</div>

<script src="<?= $site_url . '/js/ajax_modal_back.js' ?>"></script>
