<div class="col-md-9 col-lg-10 align-self">
  <h1 class="h2">Les plus vues</h1>
  <hr>
  <div id="carouselExample3" class="carousel text-center slide" data-ride="carousel" data-interval="9000">
    <div class="carousel-inner row w-10 mxauto" role="listbox">

<?php 
function connectDB(){
require_once BASEPATH . '/conf.inc.php';
	// "mysql:host=localhost;dbname=projet1a1"
	try{
		$pdo = new PDO( DBDRIVER.":host=".DBHOST.";dbname=".DBNAME ,DBUSER,DBPWD);
		//Permet d'afficher les erreurs SQL
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	}catch(Exception $e){
		die("Erreur SQL : ".$e->getMessage());
	}
	return $pdo;
}
function getImage($id) {
  $pdo = connectDB();
  $query = "SELECT image_show FROM flixadvisor.TV_SHOW WHERE id_show = :id";
  $queryPrepared = $pdo->prepare($query);
  $queryPrepared->execute([":id" => $id]);
  if ($queryPrepared->errorCode() != '00000') {
      var_dump($queryPrepared->errorInfo());
      die("Une erreur est survenue lors de la recherche d'image.");
  }
  return $queryPrepared->fetch()[0];
}
$image_1 = getImage('79168');
$image_2 = getImage('80534');
$image_3 = getImage('121361');
$image_4 = getImage('278155');
$image_5 = getImage('74796');

function getName($id) {
  $pdo = connectDB();
  $query = "SELECT name_show FROM flixadvisor.TV_SHOW WHERE id_show = :id";
  $queryPrepared = $pdo->prepare($query);
  $queryPrepared->execute([":id" => $id]);
  if ($queryPrepared->errorCode() != '00000') {
      var_dump($queryPrepared->errorInfo());
      die("Une erreur est survenue lors de la recherche de nom.");
  }
  return $queryPrepared->fetch()[0];
}
$titre_1 = getName('79168');
$titre_2 = getName('80534');
$titre_3 = getName('121361');
$titre_4 = getName('278155');
$titre_5 = getName('74796');
?>
      <div class="carousel-item col-md-4 active">
        <a href="">
          <img class="img-fluid mx-auto d-bloc" src="<?= $image_1;?>" alt="slide 1">
        </a>
        <p class="text-center"><?= $titre_1?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_2;?>" alt="slide 2">
        </a>
        <p class="text-center"><?= $titre_2?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_3;?>" alt="slide 3">
        </a>
        <p class="text-center"><?= $titre_3?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_4;?>" alt="slide 4">
        </a>
        <p class="text-center"><?= $titre_4?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_5;?>" alt="slide 5">
        </a>
        <p class="text-center"><?= $titre_5?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_1;?>" alt="slide 6">
        </a>
        <p class="text-center"><?= $titre_1?></p>
      </div>
    </div>
  </div>
  <h2>Les mieux notées</h2>
  <hr>
  <div id="carouselExample1" class="carousel text-center slide" data-ride="carousel" data-interval="9000">
    <div class="carousel-inner row w-10 mxauto" role="listbox">
      <div class="carousel-item col-md-4 active">
        <a href="">
          <img class="img-fluid mx-auto d-bloc" src="<?= $image_1;?>" alt="slide 1">
        </a>
        <p class="text-center"><?= $titre_1?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_2;?>" alt="slide 2">
        </a>
        <p class="text-center"><?= $titre_2?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_3;?>" alt="slide 2">
        </a>
        <p class="text-center"><?= $titre_3?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_4;?>" alt="slide 2">
        </a>
        <p class="text-center"><?= $titre_4?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_5;?>" alt="slide 2">
        </a>
        <p class="text-center"><?= $titre_5?></p>
      </div>
      <div class="carousel-item col-md-4">
        <a href="">
          <img class="img-fluid mx-auto d-block" src="<?= $image_1;?>" alt="slide 2">
        </a>
        <p class="text-center"><?= $titre_1?></p>
      </div>
    </div>
  </div>
  <h2>Les plus récentes</h2>
  <hr>
  <div id="carouselExample" class="carousel text-center slide" data-ride="carousel" data-interval="9000">
  <div class="carousel-inner row w-10 mxauto" role="listbox">
    <div class="carousel-item col-md-4 active">
      <a href="">
        <img class="img-fluid mx-auto d-bloc" src="<?= $image_1;?>" alt="slide 1">
      </a>
      <p class="text-center"><?= $titre_1?></p>
    </div>
    <div class="carousel-item col-md-4">
      <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_2;?>" alt="slide 2">
      </a>
      <p class="text-center"><?= $titre_2?></p>
    </div>
    <div class="carousel-item col-md-4">
      <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_3;?>" alt="slide 2">
      </a>
      <p class="text-center"><?= $titre_3?></p>
    </div>
    <div class="carousel-item col-md-4">
      <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_4;?>" alt="slide 2">
      </a>
      <p class="text-center"><?= $titre_4?></p>
    </div>
    <div class="carousel-item col-md-4">
      <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_5;?>" alt="slide 2">
      </a>
      <p class="text-center"><?= $titre_5?></p>
    </div>
    <div class="carousel-item col-md-4">
      <a href="">
        <img class="img-fluid mx-auto d-block" src="<?= $image_1;?>" alt="slide 2">
      </a>
      <p class="text-center"><?= $titre_1?></p>
    </div>
  </div>
  </div>
  </div>
  <script>
 /*  $('#carouselExample').on('slide.bs.carousel', function(e) {
    var $e = $(e.relatedTarget);
    var idx = $e.index();
    var itemsPerSlide = 3;
    var totalItems = $('.carousel-item').length;

    if (idx >= totalItems - (itemsPerSlide - 1)) {
      var it = itemsPerSlide - (totalItems - idx);
      for (var i = 0; i < it; i++) {
        // append slides to end
        if (e.direction == "left") {
          $('.carousel-item').eq(i).appendTo('.carousel-inner');
        } else {
          $('.carousel-item').eq(0).appendTo('.carousel-inner');
        }
      }
    }
  });*/
    </script>
