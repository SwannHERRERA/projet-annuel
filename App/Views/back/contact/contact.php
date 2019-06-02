<script src="<?= $site_url . '/js/back.js' ?>"></script>
<div class="col-md-9 col-lg-10 align-self">
    <div class="row">
        <div class="col-sm-4 border-right">
            <label for="filter">Recherche :</label>
            <input class="form-control mb-20" type="text" id="filter" onkeyup="filter()">
            <div class="list-group h-auto pre-scrollable" id="list-tab" role="tablist">
                <?php
                $correspondants = listContactMessages();
                foreach ($correspondants as $key => $correspondant) {
                    if ($key == 0) { ?>
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                           role="tab"
                           aria-controls="home"
                           onclick="getMessages(<?= "'" . getMember($correspondant['correspondant'])['pseudo'] . "'" ?>)"><?= getMember($correspondant['correspondant'])['pseudo'] . "<br>" . $correspondant['correspondant'] ?></a>
                        <?php
                    } else { ?>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                           role="tab"
                           aria-controls="home"
                           onclick="getMessages(<?= "'" . getMember($correspondant['correspondant'])['pseudo'] . "'" ?>)"><?= getMember($correspondant['correspondant'])['pseudo'] . "<br>" . $correspondant['correspondant'] ?></a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="row">
                <div class="col-12 pre-scrollable" id="messages">
                    <?php $messages = getMessages($correspondants[0]['correspondant'], 'admin@admin.fr');
                    foreach ($messages as $message) {
                        if ($message['sending_member'] == 'admin@admin.fr') { ?>
                            <div class="row justify-content-end mt-20">
                                <div class="col-md-6">
                                    <div class="card bg-primary text-light">
                                        <div class="card-header ">
                                            Envoyé le : <?= date('d-m-Y', strtotime($message['date_message'])) ?>
                                        </div>
                                        <div class="card-body ">
                                            <p class="card-text"><?= $message['text_message'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="row justify-content-start mt-20 ">
                                <div class="col-md-6">
                                    <div class="card bg-dark text-light">
                                        <div class="card-header ">
                                            Envoyé le : <?= date('d-m-Y', strtotime($message['date_message'])) ?>
                                        </div>
                                        <div class="card-body ">
                                            <p class="card-text"><?= $message['text_message'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="col-10 mt-20">
                    <label for="newMessage">Nouveau message : </label>
                    <textarea id="newMessage" class="form-control"></textarea>
                </div>
                <div class="col-2 mt-20 align-self-center">
                    <button id="sendMessage" class="btn btn-primary" onclick="sendMessage()">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>