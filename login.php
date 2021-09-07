<?php

require_once 'common.php';

if (isset($_SESSION['username'])) {
    header('Location: products.php');
    exit;
}

$arrayFormDetails = [
    'username' => '',
    'password' => '',
];

if (isset($_POST['submit'])) {

    //server-side validation
    if (empty($_POST['username'])) {
        $arrayFormError['username_error'] = translateLabels('Username is required.');
    } else {

        if ($_POST['username'] != LOGIN_USERNAME) {
            $arrayFormError['username_error'] = translateLabels('Username is wrong.');
        }

        $arrayFormDetails['username'] = strip_tags($_POST['username']);
    }

    if (empty($_POST['password'])) {
        $arrayFormError['password_error'] = translateLabels('Password is required.');
    } else {

        if ($_POST['password'] != LOGIN_PASSWORD) {
            $arrayFormError['password_error'] = translateLabels('Password is wrong.');
        }

        $arrayFormDetails['password'] = strip_tags($_POST['password']);
    }

    if ($arrayFormDetails['username'] == LOGIN_USERNAME && $arrayFormDetails['password'] == LOGIN_PASSWORD) {
        $_SESSION['username'] = $arrayFormDetails['username'];
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
            <input type="text" name="username" placeholder="<?= translateLabels('Username'); ?>" value="<?= $arrayFormDetails['username']; ?>" ><br>
            <?php if (isset($arrayFormError['username_error']) && !empty($arrayFormError['username_error'])): ?>
                <span class="error"><?= $arrayFormError['username_error']; ?></span>
            <?php endif; ?>
            <input type="password" name="password" placeholder="<?=translateLabels('Password');?>" value="<?= $arrayFormDetails['password']; ?>"><br>
            <?php if (isset($arrayFormError['password_error']) && !empty($arrayFormError['password_error'])): ?>
                <span class="error"><?=$arrayFormError['password_error']; ?></span>
            <?php endif; ?>
            <button type="submit" name="submit"><?= translateLabels('Login'); ?></button>
        </form>
    </div>
</body>
</html>