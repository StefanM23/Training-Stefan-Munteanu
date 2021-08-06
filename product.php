<?php

require_once "common.php";
require_once "config.php";

if (isset($_POST['Save'])) {
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <form action="product.php" method="post" enctype="multipart/form-data" class="myProductFrom">
        <input class="item-i" type="text" name="title" placeholder="Title"><br>
        <input class="item-i" type="text" name="description" placeholder="Description"><br>
        <input class="item-i" type="text" name="price" placeholder="Price"><br>
        <input class="item-j" type="text" name="image" placeholder="Image">
        <input class="item-j-x" type="file" ><br>
        <a class="item-j-y" href="#">Products</a>
        <input class="item-j-y" type="submit" value="Save">
    </form>
</body>
</html>