<div class="child-content">
    <h1>Message Board</h1>
    <p>Log in to continue!</p>
    <form id="login">
        <div class="input-group">
            <input type="email" class="form-control" placeholder="E-mal" name="email">
        </div>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="Password" name="password">
        </div>
        <button>Login</button>
        <p>Don't have an account yet? <a href="<?= Configure::read("BASE_URL") ?>/register">Click Here</a></p>
    </form>
</div>