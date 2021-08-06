<?php 

require_once "common.php";
require_once "config.php";
if (isset($_POST['submit'])) {

    $username=(isset($_POST['username'])) ? $_POST['username'] : "STOP";
    $password=(isset($_POST['password'])) ? $_POST['password'] : "STOP";
    
    if ($username==LOGIN_USERNAME && $password==LOGIN_PASSWORD) {
        $_SESSION['username']=$username;
        $_SESSION['password']=$password;
        header("Location: http://localhost/ProjectMS/products.php");
        exit; 
    } else { 
        if (!empty($username) && !empty($password)) {
            echo "<script>alert('The data entered is incorrect.');</script>";
        }
    }  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=translateLabels("Login Page");?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-login-page">
        <form action="login.php" method="post" class="myLoginPage">
            <input type="text" name="username" placeholder="<?=translateLabels("Username");?>" value="<?php echo isset($_POST["username"]) ? $_POST["username"] : ''; ?>" ><br>
            <input type="password" name="password" placeholder="<?=translateLabels("Password");?>" value="<?php echo isset($_POST["password"]) ? $_POST["password"] : ''; ?>"><br>
            <input type="submit" name="submit" value="<?=translateLabels("Login");?>">
        </form>
    </div>    
</body>
</html>