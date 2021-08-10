<?php

require_once 'common.php';

//update the product
if (isset($_POST['Save'])) {
    if ($_SESSION['titleMod'] != $_POST['title'] || $_SESSION['descriptionMod'] != $_POST['description'] || $_SESSION['priceMod'] != $_POST['price'] || $_SESSION['imageMod'] != $_POST['image']) {
        $_SESSION['titleMod'] = $_POST['title'];
        $_SESSION['descriptionMod'] = $_POST['description'];
        $_SESSION['priceMod'] = $_POST['price'];

        //upload database
        $file = $_FILES['file'];
        $fileName = $_Files['file']['name'];
        //temporal location of the image
        $fileTmpName = $_Files['file']['tmp_name'];
        $fileSize = $_Files['file']['size'];
        $fileError = $_Files['file']['error'];
        $fileType = $_Files['file']['type'];

        $fileExt = strtolower(end(explode('.', $fileName)));
        $allowedExtension = ['jpg', 'jpeg', 'png'];
        echo "ok";
        if (in_array($fileExt, $allowedExtension) && $fileError === 0 && $fileSize < 500000) {
            $fileNameNew = uniqid('', true).'.'.$fileExt;
            $fileDestination = 'ProjectMS/uploads/'.$fileNameNew;
            move_uploaded_file($fileTmpName,$fileDestination);
            echo "ok";
        }


    
    }
    header('Location: product.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=translateLabels('Product Page');?></title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <form action="product.php" method="post" enctype="multipart/form-data" class="myProductFrom">
        <input class="item-i" type="text" name="title" value="<?= $_SESSION['titleMod']; ?>" placeholder="<?= translateLabels('Title'); ?>"><br>
        <input class="item-i" type="text" name="description" value="<?= $_SESSION['descriptionMod']; ?>" placeholder="<?= translateLabels('Description'); ?>"><br>
        <input class="item-i" type="text" name="price" value="<?= $_SESSION['priceMod']; ?>" placeholder="<?= translateLabels('Price'); ?>"><br>
        <input class="item-j" type="text" name="image" value="<?= $_SESSION['imageMod']; ?>" placeholder="<?= translateLabels('Image'); ?>">
        <input class="item-j-x" type="file" name="file"><br>
        <a class="item-j-y" href="products.php"><?=translateLabels('Products');?></a>
        <button class="item-j-y" type="submit" name="Save"  ><?= translateLabels('Save'); ?></button>
    </form>
</body>
</html>
