<script src="<?= $site_url . '/js/messages.js' ?>"></script>
<div class="col-md-9 col-lg-10 align-self">
    <h1 class="h1 ml-20 mt-20">Messages</h1><br>
    <hr>
    <div class="row">
        <div class="col-sm-3 border-right">
            <label for="filter">Recherche :</label>
            <input class="form-control mb-20" type="text" id="filter" onkeyup="filter()">
            <div class="list-group h-auto pre-scrollable" id="list-tab" role="tablist">
                <?php
                $correspondants = listMemberMessages($_SESSION['email']);
                foreach ($correspondants as $key => $correspondant) {
                    if ($key == 0) { ?>
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                           role="tab"
                           aria-controls="home"
                           onclick="getMessages(<?= "'" . getMember($correspondant['correspondant'])['pseudo'] . "'" ?>)"><?= getMember($correspondant['correspondant'])['pseudo'] ?></a>
                        <?php
                    } else { ?>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                           role="tab"
                           aria-controls="home"
                           onclick="getMessages(<?= "'" . getMember($correspondant['correspondant'])['pseudo'] . "'" ?>)"><?= getMember($correspondant['correspondant'])['pseudo'] ?></a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="row ">
                <div class="col-12 h-auto pre-scrollable" id="messages">
                    <?php
                    if (!empty($correspondants)) {
                        $messages = getMessages($correspondants[0]['correspondant'], $_SESSION['email']);
                        if (!empty($messages)) {
                            foreach ($messages as $message) {
                                if ($message['sending_member'] == $_SESSION['email']) { ?>
                                    <div class="row justify-content-end my-20">
                                        <div class="col-md-6">
                                            <div class="card bg-secondary">
                                                <div class="card-header ">
                                                    Envoyé le
                                                    : <?= date('d-m-Y', strtotime($message['date_message'])) ?>
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
                                    <div class="row justify-content-start my-20 ">
                                        <div class="col-md-6">
                                            <div class="card bg-dark">
                                                <div class="card-header ">
                                                    Envoyé le
                                                    : <?= date('d-m-Y', strtotime($message['date_message'])) ?>
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
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <label for="newMessage">Nouveau message : </label>
                    <textarea id="newMessage" class="form-control"></textarea>
                </div>
                <div class="col-md-3 align-self-center">
                    <button id="sendMessage" class="btn btn-primary mt-20" onclick="sendMessage()">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>
