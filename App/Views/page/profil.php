<div class="col-md-9 col-lg-10 align-self">
    <div class="row mt-20 ml-20">
        <div class="col-2">
            <!--<img class="img-fluid" src="<?= $user['photo'] ?>" alt="Photo de profil">-->
            <img class="img-fluid"
                 src="https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png"
                 alt="Photo de profil">
        </div>
        <div class="col-10">
            <h1 class="h2"><?= $user['pseudo'] ?></h1>
        </div>
        <div class="col-12 mt-30">
            <hr>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="show-tab" data-toggle="tab" href="#show" role="tab"
                       aria-controls="show" aria-selected="true">Series suivies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list"
                       aria-selected="false">Listes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="list-tab" data-toggle="tab" href="#activity" role="tab"
                       aria-controls="activity" aria-selected="false">Activités</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="stats-tab" data-toggle="tab" href="#stats" role="tab" aria-controls="list"
                       aria-selected="false">Statistiques</a>
                </li>

            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
                    suivies
                </div>
                <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
                    listes
                </div>
                <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                    activités
                </div>
                <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                    stats
                </div>
            </div>
        </div>
    </div>
</div>