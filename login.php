<?php 
     require_once('common.php');
     require_once('config.php');

     if(isset($_POST['submit'])){

         $username=(isset($_POST['username'])) ? $_POST['username'] : "STOP";
         $password=(isset($_POST['password'])) ? $_POST['password'] : "STOP";
         
         if($username==usernameInfo && $password==passwordInfo){
            header("Location: http://localhost/ProjectMS/products.php");
            exit; 
         }else{
             if(!empty($username)&&!strlen($password)){
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
    <title>Login Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-login-page">
        <form action="login.php" method="post" class="myLoginPage">
            <input type="text" name="username" placeholder="Username" ><br>
            <input type="password" name="password" placeholder="Password"><br>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>    
</body>
</html>