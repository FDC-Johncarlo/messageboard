<div class="child-content">
    <h1>Message Board</h1>
    <p>Register to continue!</p>
    <div class="error-message"></div>
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
            <input type="password" class="form-control" placeholder="Confirmation Password" name="confirmation_password">
        </div>
        <button>Register</button>
        <p>Already have an account? <a href="<?= Configure::read("BASE_URL") ?>/login">Click Here</a></p>
    </form>
</div>