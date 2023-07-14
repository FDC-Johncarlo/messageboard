<span>Welcome To</span>
<h1>Message Board</h1>
<p><?= $userInfoPub["name"] ?></p>
<div class="mini-nav">
    <nav>
        <ul>
            <li class="<?= $path[0] == "profile" ? "active" : "in-active" ?>"><a href="<?= Configure::read("BASE_URL") ?>/my-profile">My Profile</a></li>
            <li class="<?= $path[0] == "account" ? "active" : "in-active" ?>"><a href="<?= Configure::read("BASE_URL") ?>/my-account">My Account</a></li>
            <li><a href="<?= Configure::read("BASE_URL") ?>/logout" class="logout">Logout</a></li>
        </ul>
    </nav>
</div>