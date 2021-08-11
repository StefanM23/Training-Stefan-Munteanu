<?php

require_once 'common.php';

$title = (isset($_SESSION['titleMod'])) ? $_SESSION['titleMod'] : '';
$description = (isset($_SESSION['descriptionMod'])) ? $_SESSION['descriptionMod'] : '';
$price = (isset($_SESSION['priceMod'])) ? $_SESSION['priceMod'] : '';
$image = (isset($_SESSION['imageMod'])) ? $_SESSION['imageMod'] : '';

//update the product
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if ($title != $_POST['title'] || $description != $_POST['description'] || $price != $_POST['price'] || !empty($_FILES['file'])) {
        $_SESSION['titleMod'] = $_POST['title'];
        $_SESSION['descriptionMod'] = $_POST['description'];
        $_SESSION['priceMod'] = $_POST['price'];

        //upload database
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        //temporal location of the image
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];

        $fileExt = explode('.', $fileName);
        $fileExtImg = strtolower(end($fileExt));
        $allowedExtension = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExtImg, $allowedExtension) && $fileError === 0 && $fileSize < 1000000) {
            $fileNameNew = uniqid('', true) . '.' . $fileExtImg;
            $fileDestination = 'uploads/' . $fileNameNew;
            $_SESSION['imageMod'] = $fileDestination;
            $image = $fileDestination;
            move_uploaded_file($fileTmpName, $fileDestination);
        }

        //insert/update date in database
        if (isset($_SESSION['idMod'])) {
            $sqlEdit = 'UPDATE products SET  title = ?, description = ?, price = ? , image = ? WHERE id = ?';
            $resultEdit = $connection->prepare($sqlEdit);
            $resultEdit->execute([$title, $description, $price, $image, $_SESSION['idMod']]);
        } else {
            $sqlEdit = 'INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES (?, ?, ?, ?)';
            $resultEdit = $connection->prepare($sqlEdit);
            $resultEdit->execute([$title, $description, $price, $image]);
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
    <title><?= translateLabels('Product Page'); ?></title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <form action="product.php" method="post" enctype="multipart/form-data" class="myProductFrom">
        <input class="item-i" type="text" name="title" value="<?= $title; ?>" placeholder="<?= translateLabels('Title'); ?>"><br>
        <input class="item-i" type="text" name="description" value="<?= $description; ?>" placeholder="<?= translateLabels('Description'); ?>"><br>
        <input class="item-i" type="text" name="price" value="<?= $price; ?>" placeholder="<?= translateLabels('Price'); ?>"><br>
        <input class="item-j" type="text" name="image" value="<?= $image; ?>"  placeholder="<?= translateLabels('Image'); ?>">
        <input class="item-j-x" type="file" name="file"><br>
        <a class="item-j-y" href="products.php"><?= translateLabels('Products'); ?></a>
        <button class="item-j-y" type="submit" name="submit"  ><?= translateLabels('Save'); ?></button>
    </form>
</body>
</html>
