<div class="mt-20 col-12">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th class='text-center'>Nom de la série</th>
        <th class='text-center'>Année</th>
        <th class='text-center'>Statut</th>
        <th class='text-center'>Ajouté par</th>
        <th class='text-center'>Ajouté le</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tv_shows as $tv_show) :?>
      <tr>
          <td><?php echo $tv_show["name_show"]?><span class="text-right"><i></i></span></td>
          <td>
              <?php //$date = new DateTime($tv_show['date_inscription']);
              //echo $date->format('d-m-Y'); ?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-center">
              <a><i class="fas fa-edit"></i></a>
              <a><i class="fas fa-trash-alt"></i></a>
          </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p class="mt-5 mr-5 text-right">
    <a href="export svg" class="btn btn-primary">exporter en CSV</a>
  </p>
</div>