<div class="wrapper">
    <span>Welcome To</span>
    <h1>Message Board</h1>
    <?php echo $this->element('nav'); ?>
    <div class="breadcrumb">
        <ul>
            <li><a href="<?= Configure::read("BASE_URL") ?>/home">Home</a></li>
            <li>My Profile</li>
        </ul>
    </div>
    <hr class="divider">
    <div class="error-message"></div>
    <div class="container">
        <form id="profile">
            <div class="left">
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
                <label for="preiviewer">
                    <input type="file" id="preiviewer" name="profile" accept=".jpg, .gif, .png">
                    Select Profile
                </label>
            </div>
            <div class="right">
                <table>
                    <tr>
                        <td>Name</td>
                        <td>
                            <input type="text" name="name" value="<?= $profile["userInfo"]["name"] ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Birthdate</td>
                        <td>
                            <input type="text" name="birthdate" value="<?= $profile["status"] ? $profile["userInfo"]["birth_date"] : "" ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>
                            <div class="gender-area">
                                <?php if (!$profile["status"]) { ?>
                                    <label for="male">
                                        <input type="radio" name="gender" value="Male" id="male" checked>
                                        Male
                                    </label>
                                    <label for="female">
                                        <input type="radio" name="gender" value="Female" id="female">
                                        Female
                                    </label>
                                <?php } else { ?>
                                    <label for="male">
                                        <input type="radio" name="gender" value="Male" id="male" <?= $profile["userInfo"]["gender"] == "Male" ? "checked" : "" ?>>
                                        Male
                                    </label>
                                    <label for="female">
                                        <input type="radio" name="gender" value="Female" id="female" <?= $profile["userInfo"]["gender"] == "Female" ? "checked" : "" ?>>
                                        Female
                                    </label>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Hubby</td>
                        <td>
                            <textarea name="hubby" cols="30" rows="10"><?= $profile["status"] ? $profile["userInfo"]["hubby"] : "" ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button>Update</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>