<div class="wrapper">
    <span>Welcome To</span>
    <h1>Message Board</h1>
    <div class="mini-nav">
        <nav>
            <ul>
                <li><a href="javascript:;" class="logout">Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="breadcrumb">
        <ul>
            <li><a href="<?= Configure::read("BASE_URL") ?>/home">Home</a></li>
            <li>My Profile</li>
        </ul>
    </div>
    <hr class="divider">
    <div class="container">
        <form id="update-profile">
            <div class="left">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTox0g5LHtUBM-9I8EJJ1Ea7gfwlT60RkzmUL0lXcQ&s" alt="Profile Picture">
                <label for="preiviewer">
                    <input type="file" id="preiviewer" name="profile">
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
                            <input type="date" name="birthdate" value="<?= $profile["status"] ? $profile["userInfo"]["birth_date"] : "" ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>
                            <div class="gender-area">
                                <label for="male">
                                    <input type="radio" name="gender" value="Male" id="male" <?= !$profile["status"] ? "" : ($profile["userInfo"]["gender"] == "Male" ? "checked" : "") ?>>
                                    Male
                                </label>
                                <label for="female">
                                    <input type="radio" name="gender" value="Female" id="female" <?= !$profile["status"] ? "" : ($profile["userInfo"]["gender"] == "Female" ? "checked" : "") ?>>
                                    Female
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Hubby</td>
                        <td>
                            <textarea name="hubby" cols="30" rows="10"> <?= $profile["status"] ? $profile["userInfo"]["hubby"] : "" ?></textarea>
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