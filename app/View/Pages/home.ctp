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
        <div class="main-list">
            <?php if (!empty($list)) { ?>
                <div class="card-list">
                    <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="">
                    <div class="right-content-msg">

                    </div>
                </div>
            <?php } else { ?>
                <p style="text-align: center;">No Message List Yet</p>
            <?php
            }
            ?>
        </div>
    </div>
</div>