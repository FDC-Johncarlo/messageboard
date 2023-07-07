<div class="child-content">
    <h1>Message Board</h1>
    <p>Register to continue!</p>
    <form id="register">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Name" name="name">
        </div>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="E-mail" name="email">
        </div>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="Password" name="password">
        </div>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="Confirmation Password" name="confirm_password">
        </div>
        <button>Login</button>
        <p>Don't have an account yet? <a href="<?= Configure::read("BASE_URL") ?>/register">Click Here</a></p>
    </form>
</div>