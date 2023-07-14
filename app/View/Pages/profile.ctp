<div class="wrapper">
    <?php echo $this->element('nav'); ?>
    <div class="breadcrumb">
        <ul>
            <li><a href="<?= Configure::read("BASE_URL") ?>/home">Home</a></li>
            <li>My Profile</li>
        </ul>
    </div>
    <hr class="divider">
    <div class="error-message"></div>
    <div class="container my-rpofile-container">
        <div class="left my-rpofile-image">
            <?php
            # Check if the status has joined
            if ($profile["status"]) {
                # Also check if the user has profile uploaded or null
                if ($profile["userInfo"]["profile"] == NULL) { ?>
                    <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="Default Profile Picture" id="preview-here">
                <?php } else { # Set only to default 
                ?>
                    <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/uploads/profile/<?= $profile["userInfo"]["profile"] ?>" alt="Profile Picture" id="preview-here">
                <?php } ?>
            <?php } else { # Set only to default 
            ?>
                <img src="<?= Configure::read("BASE_URL") ?>/app/webroot/img/default.png" alt="Default Profile Picture" id="preview-here">
            <?php } ?>
            <div class="other-profile-info">
                <h3><?= $profile["userInfo"]["name"]?> <span style="color: red;"><?= $profile["userInfo"]["age"]?></span></h3>
                <table>
                    <tr>
                        <td>Gender:</td>
                        <td><?= $profile["userInfo"]["gender"]?></td>
                    </tr>
                    <tr>
                        <td>Birthdate:</td>
                        <td><?= $profile["userInfo"]["birth_date"]?></td>
                    </tr>
                    <tr>
                        <td>Joined:</td>
                        <td><?= $profile["userInfo"]["date_register"]?></td>
                    </tr>
                    <tr>
                        <td>Last Login:</td>
                        <td><?= $profile["userInfo"]["last_log"]?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div>
            <label>Hubby:</label>
            <p style="margin-top: 10px;"><?= $profile["userInfo"]["hubby"] ?></p>
        </div>
        <br>
        <br>
        <a href="<?= Configure::read("BASE_URL") ?>/edit-profile">Edit Profile</a>
    </div>
</div>