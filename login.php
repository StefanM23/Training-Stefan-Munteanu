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
$arrayFormError = [
    'username_error' => '',
    'password_error' => '',
];

if (isset($_POST['submit'])) {

    //server-side validation
    if (empty($_POST['username'])) {
        $arrayFormError['username_error'] = 'Username is required.';
    } else {

        if ($arrayFormDetails['username'] != LOGIN_USERNAME) {
            $arrayFormError['username_error'] = 'Username is wrong.';
        }

        $arrayFormDetails['username'] = strip_tags($_POST['username']);
    }

    if (empty($_POST['password'])) {
        $arrayFormError['password_error'] = 'Password is required.';
    } else {

        if ($arrayFormDetails['password'] != LOGIN_USERNAME) {
            $arrayFormError['password_error'] = 'Password is wrong.';
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
            <span class="error"><?= $arrayFormError['username_error']; ?></span>
            <input type="password" name="password" placeholder="<?= translateLabels('Password'); ?>" value="<?= $arrayFormDetails['password']; ?>"><br>
            <span class="error"><?= $arrayFormError['password_error']; ?></span>
            <button type="submit" name="submit"><?= translateLabels('Login'); ?></button>
        </form>
    </div>
</body>
</html>