<div class="col-md-9 col-lg-10 align-self">
    <h1 class="h2">Les plus vues</h1>
    <hr>
    <div id="carouselExample3" class="carousel text-center slide" data-ride="carousel" data-interval="9000">
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
