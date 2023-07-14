<div class="wrapper">
    <?php echo $this->element('nav'); ?>
    <div class="breadcrumb">
        <ul>
            <li><a href="<?= Configure::read("BASE_URL") ?>/home">Home</a></li>
            <li>Message details with <b id="name-of-recip"><?= $details["userAccount"]["name"] ?></b></li>
        </ul>
    </div>
    <hr class="divider">
    <div class="error-message"></div>
    <div class="container">
        <div class="chat-fields">
            <form id="reply-form">
                <textarea name="reply" data-reply-to="<?= $receiver ?>" placeholder="Type a message..." cols="30" rows="5"></textarea>
                <div class="action-reply">
                    <button>Reply</button>
                </div>
            </form>
        </div>
        <div class="main-list">
            <?php
            $chatLimit = 5;
            $chat = array_reverse(json_decode($details["message"]));
            $countChat = 0;
            foreach ($chat as $key => $value) :
                $countChat++;
                if($countChat <= $chatLimit):
            ?>
                <div class="card-list <?= $currentUser ==  $value->from ? "me-chat" : "not-me" ?>" data-ref="<?= $value->ref ?>">
                    <?php if ($currentUser ==  $value->from) : ?>
                        <div class="right-content-msg">
                            <h4 class="nameOfRecipient">You</h4>
                            <p class="message-content"><?= $value->message ?></p>
                            <span class="message-timestamp"><?= $value->date_push ?></span>
                        </div>
                        <?php if (!empty($details["currentUserData"])) : ?>
                            <?php if ($details["currentUserData"]["profile"] != null) : ?>
                                <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/uploads/profile/<?= $details["currentUserData"]["profile"] ?>" alt="Avatar">
                            <?php else : ?>
                                <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="Avatar">
                            <?php endif; ?>
                        <?php else : ?>
                            <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="Avatar">
                        <?php endif; ?>

                        <div class="action-area">
                            <a href="javascript:void(0);" id="delete-entire">Delete Message</a>
                        </div>
                    <?php else : ?>
                        <?php if (!empty($details["userData"])) : ?>
                            <?php if ($details["userData"]["profile"] != null) : ?>
                                <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/uploads/profile/<?= $details["userData"]["profile"] ?>" alt="Avatar">
                            <?php else : ?>
                                <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="Avatar">
                            <?php endif; ?>
                        <?php else : ?>
                            <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="Avatar">
                        <?php endif; ?>

                        <div class="right-content-msg">
                            <h4 class="nameOfRecipient"><?= $details["userAccount"]["name"] ?></h4>
                            <p class="message-content"><?= $value->message ?></p>
                            <span class="message-timestamp"><?= $value->date_push ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; endforeach; ?>
            <?php if ($countChat > $chatLimit) : ?>
                <div style="text-align: center; margin: 20px 0px;">
                    <button id="show-more">Show More</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>