<div class="wrapper">
    <span>Welcome To</span>
    <h1>Message Board</h1>
    <?php echo $this->element('nav'); ?>
    <div class="breadcrumb">
        <ul>
            <li><a href="<?= Configure::read("BASE_URL") ?>/home">Home</a></li>
        </ul>
    </div>
    <hr class="divider">
    <div class="message-list-content">
        <div class="message-action">
            <a href="<?= Configure::read("BASE_URL") ?>/new-message">New Message</a>
        </div>
        <?php if (!empty($list)) { ?>
            <div class="main-list">
                <?php foreach ($list as $key => $value) : $messageInfo = $value["MessageModel"]; ?>
                    <div class="card-list" data-room="<?= $messageInfo["pair_one"].".".$messageInfo["pair_two"] ?>">
                        <?php if (!empty($messageInfo["receiver"])) {
                            $receiverInfo = $messageInfo["receiver"]["UsersDataModel"]; ?>
                            <?php if ($receiverInfo["profile"] == null) : ?>
                                <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="Avatar">
                            <?php else : ?>
                                <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/uploads/profile/<?= $receiverInfo["profile"] ?>" alt="Avatar">
                            <?php endif; ?>
                        <?php } else { ?>
                            <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="Avatar">
                        <?php } ?>
                        <div class="right-content-msg">
                            <?php
                            # Decode the string json
                            $messageContent = json_decode($messageInfo["message"]);
                            # Get the length and minus 1 to get the latest chat
                            $lastMessage = $messageContent[count($messageContent) - 1];

                            # current user set from pagesController
                            if ($lastMessage->from == $currentUser) {
                                $from = "<b>You:</b> ";
                            } else {
                                $from = "";
                            }
                            ?>
                            <h4 class="nameOfRecipient"><?=  $messageInfo["name"] ?></h4>
                            <p class="message-content"><?= $from . $lastMessage->message; ?></p>
                            <span class="message-timestamp"><?= $lastMessage->date_push ?></span>
                        </div>
                        <div class="action-area">
                            <a href="javascript:void(0);" id="delete-entire" data-ref="<?= $messageInfo["id"] ?>">Delete Message</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($totalFilter > 5) : ?>
                <div style="text-align: center; margin: 20px 0px;">
                    <button id="show-more">Show More</button>
                </div>
            <?php endif; ?>
        <?php } else { ?>
            <p style="text-align: center;">No Message List Yet</p>
        <?php
        }
        ?>
    </div>
</div>