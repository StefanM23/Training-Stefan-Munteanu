<?php

require_once 'common.php';

if (isset($_POST['submit'])) {

    if ($_POST['username'] == LOGIN_USERNAME && $_POST['password'] == LOGIN_PASSWORD) {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        header('Location: products.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Login Page'); ?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-login-page">
        <form action="login.php" method="post" class="myLoginPage">
            <input type="text" name="username" placeholder="<?= translateLabels('Username'); ?>" value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>" ><br>
            <input type="password" name="password" placeholder="<?= translateLabels('Password'); ?>" value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>"><br>
            <button type="submit" name="submit"><?= translateLabels('Login'); ?></button>
        </form>
    </div>
</body>
</html>