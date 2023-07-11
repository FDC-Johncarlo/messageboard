<div class="wrapper">
    <span>Welcome To</span>
    <h1>Message Board</h1>
    <?php echo $this->element('nav'); ?>
    <div class="breadcrumb">
        <ul>
            <li><a href="<?= Configure::read("BASE_URL") ?>/home">Home</a></li>
            <li>My Account</li>
        </ul>
    </div>
    <hr class="divider">
    <div class="error-message"></div>
    <div class="container">
        <br>
        <p style="color:#a64141;"><b>Note:</b> Updating email or password can be optional. You don't have to fill the email when updating password same with the password</p>
        <br>
        <form id="account">
            <div class="right">
                <table>
                    <tr>
                        <td>Email</td>
                        <td>
                            <input type="text" name="email">
                        </td>
                    </tr>
                    <tr>
                        <td>Old Password</td>
                        <td>
                            <input type="password" name="old_password">
                        </td>
                    </tr>
                    <tr>
                        <td>New Password</td>
                        <td>
                            <input type="password" name="password">
                        </td>
                    </tr>
                    <tr>
                        <td>Confirmation Password</td>
                        <td>
                            <input type="password" name="confirmation_password">
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