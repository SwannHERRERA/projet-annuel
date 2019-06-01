<script src="<?= $site_url . '/js/messages.js' ?>"></script>
<div class="col-md-9 col-lg-10 align-self">
    <h1 class="h1 ml-20 mt-20">Messages</h1><br>
    <hr>
    <div class="row">
        <div class="col-3 border-right">
            <div class="list-group" id="list-tab" role="tablist">
                <?php
                $correspondants = listMemberMessages($_SESSION['email']);
                foreach ($correspondants as $key => $correspondant) {
                    if ($key == 0) { ?>
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                           role="tab"
                           aria-controls="home"><?= getMember($correspondant['correspondant'])['pseudo'] ?></a>
                        <?php
                    } else { ?>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                           role="tab"
                           aria-controls="home"><?= getMember($correspondant['correspondant'])['pseudo'] ?></a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-8 ml-20" id="messages">
            <?php $messages = getMessages($correspondants[0]['correspondant'], $_SESSION['email']);
            foreach ($messages as $message) {
                if ($message['sending_member'] == $_SESSION['email']) { ?>
                    <div class="row justify-content-end mt-20">
                        <div class="card pl-200">
                            <div class="card-header pl-200">
                                Envoyé le : <?= date('d-m-Y', strtotime($message['date_message'])) ?>
                            </div>
                            <div class="card-body pl-200">
                                <p class="card-text"><?= $message['text_message'] ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="row justify-content-start mt-20 ">
                        <div class="card pr-200">
                            <div class="card-header pl-200">
                                Envoyé le : <?= date('d-m-Y', strtotime($message['date_message'])) ?>
                            </div>
                            <div class="card-body pl-200">
                                <p class="card-text"><?= $message['text_message'] ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>

        </div>
    </div>
</div>