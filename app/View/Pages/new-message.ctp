<div class="wrapper">
    <span>Welcome To</span>
    <h1>Message Board</h1>
    <?php echo $this->element('nav'); ?>
    <div class="breadcrumb">
        <ul>
            <li><a href="<?= Configure::read("BASE_URL") ?>/home">Home</a></li>
            <li>New Messages</li>
        </ul>
    </div>
    <hr class="divider">
    <div class="error-message"></div>
    <div class="message-list-content">
        <form id="new-message">
            <div class="right">
                <table>
                    <tr>
                        <td>Recipient</td>
                        <td>
                            <select name="to">
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Message</td>
                        <td>
                            <textarea name="message" cols="30" rows="10"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button>Send</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>