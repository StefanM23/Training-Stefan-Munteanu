<?php

require_once 'common.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$arrayFormDetails = [
    'title' => '',
    'description' => '',
    'price' => '',
    'image' => '',
];


if (isset($_GET['editId'])) {
    $sqlEdit = 'SELECT * FROM products WHERE id = ?';
    $resultEdit = $connection->prepare($sqlEdit);
    $resultEdit->execute([$_GET['editId']]);
    $fetchEdit = $resultEdit->fetch(PDO::FETCH_ORI_FIRST);

    //if result is false than will redirect to the orders.php
    if (!$fetchEdit) {
        header('Location: products.php');
        exit;
    }

    $arrayFormDetails['title'] = $fetchEdit['title'];
    $arrayFormDetails['description'] = $fetchEdit['description'];
    $arrayFormDetails['price'] = $fetchEdit['price'];
    $arrayFormDetails['image'] = $fetchEdit['image'];
}

//update the product
if (isset($_POST['submit'])) {
    //server-side validation
    if (empty($_POST['title'])) {
        $arrayFormError['title_error'] = translateLabels('Title is required.');
    } else {
        $arrayFormDetails['title'] = strip_tags($_POST['title']);
    }

    if (empty($_POST['description'])) {
        $arrayFormError['description_error'] = translateLabels('Description is required.');
    } else {
        $arrayFormDetails['description'] = strip_tags($_POST['description']);
    }

    if (empty($_POST['price'])) {
        $arrayFormError['price_error'] = translateLabels('Price is required.');
    } else {
        $arrayFormDetails['price'] = strip_tags($_POST['price']);
    }

    if (empty($_POST['image'])) {
        $arrayFormError['image_error'] = translateLabels('Image is required.');
    } else {
        $arrayFormDetails['image'] = strip_tags($_POST['image']);
    }

    if (sizeof($arrayFormError) == 0) {

        if (strlen($_FILES['file']['name'])) {
            $fileName = $_FILES['file']['name'];
            //temporal location of the image
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileError = $_FILES['file']['error'];

            $fileExt = explode('/', mime_content_type($fileTmpName));
            $fileExtImg = strtolower(end($fileExt));
            $allowedExtension = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExtImg, $allowedExtension) && $fileError === 0 && $fileSize < 1000000) {
                $fileNameNew = uniqid('', true) . '.' . $fileExtImg;
                $fileDestination = 'uploads/' . $fileNameNew;
                $arrayFormDetails['image'] = $fileDestination;
                move_uploaded_file($fileTmpName, $fileDestination);
            }
        }

        // insert/update date in database
        if (strlen($_POST['submit']) != 0) {
            $sqlEdit = 'UPDATE products SET  title = ?, description = ?, price = ? , image = ? WHERE id = ?';
            $resultEdit = $connection->prepare($sqlEdit);
            $resultEdit->execute([$arrayFormDetails['title'], $arrayFormDetails['description'], $arrayFormDetails['price'], $arrayFormDetails['image'], $_POST['submit']]);
            header('Location: product.php?editId=' . $_POST['submit']);
            exit;
        } else {
            $sqlEdit = 'INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES (?, ?, ?, ?)';
            $resultEdit = $connection->prepare($sqlEdit);
            $resultEdit->execute([$arrayFormDetails['title'], $arrayFormDetails['description'], $arrayFormDetails['price'], $arrayFormDetails['image']]);
            header('Location: product.php');
            exit;
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
    <title><?= translateLabels('Product Page'); ?></title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <form action="product.php" method="post" enctype="multipart/form-data" class="myProductFrom">
        <input class="item-i" type="text" name="title" value="<?= $arrayFormDetails['title']; ?>" placeholder="<?= translateLabels('Title'); ?>"><br>
        <?php if (isset($arrayFormError['title_error']) && !empty($arrayFormError['title_error'])): ?>
            <span class="error"><?= $arrayFormError['title_error']; ?></span>
        <?php endif; ?>
        
        <input class="item-i" type="text" name="description" value="<?= $arrayFormDetails['description']; ?>" placeholder="<?= translateLabels('Description'); ?>"><br>
        <?php if (isset($arrayFormError['description_error']) && !empty($arrayFormError['description_error'])): ?>
            <span class="error"><?= $arrayFormError['description_error']; ?></span>
        <?php endif; ?>
        
        <input class="item-i" type="text" name="price" value="<?= $arrayFormDetails['price']; ?>" placeholder="<?= translateLabels('Price'); ?>"><br>
        <?php if (isset($arrayFormError['price_error']) && !empty($arrayFormError['price_error'])): ?>
            <span class="error"><?= $arrayFormError['price_error']; ?></span>
        <?php endif; ?>

        <input class="item-j" type="text" name="image" value="<?= $arrayFormDetails['image']; ?>"  placeholder="<?= translateLabels('Image'); ?>">
        <?php if (isset($arrayFormError['image_error']) && !empty($arrayFormError['image_error'])): ?>
            <span class="error"><?= $arrayFormError['image_error']; ?></span>
        <?php endif; ?>
        <input class="item-j-x" type="file" name="file"><br>
        <a class="item-j-y" href="products.php"><?= translateLabels('Products'); ?></a>
        <?php if (isset($_GET['editId'])): ?>
            <button class="item-j-y" type="submit" name="submit" value="<?= $_GET['editId']; ?>" ><?= translateLabels('Save'); ?></button>
        <?php else: ?>
            <button class="item-j-y" type="submit" name="submit" ><?= translateLabels('Save'); ?></button>
        <?php endif; ?>
    </form>
</body>
</html>
