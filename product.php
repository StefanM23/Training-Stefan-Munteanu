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
$arrayFormError = [
    'title_error' => '',
    'description_error' => '',
    'price_error' => '',
    'image_error' => '',
];

if (isset($_GET['editId'])){
    $sqlEdit = 'SELECT * FROM products WHERE id = ?';
    $resultEdit = $connection->prepare($sqlEdit);
    $resultEdit->execute([$_GET['editId']]);
    $fetchEdit = $resultEdit->fetchAll();

    $arrayFormDetails['title'] = $fetchEdit[0]['title'];
    $arrayFormDetails['description'] = $fetchEdit[0]['description'];
    $arrayFormDetails['price'] = $fetchEdit[0]['price'];
    $arrayFormDetails['image'] = $fetchEdit[0]['image'];
}

//update the product
if (isset($_POST['submit'])) {

    //server-side validation
    if (empty($_POST['title'])) {
        $arrayFormError['title_error'] = 'Title is required.';
    } else {
        if ($arrayFormDetails['title'] != $_POST['title']) {
            $arrayFormDetails['title'] = strip_tags($_POST['title']);
        } else {
            $arrayFormError['title_error'] = 'Same title';
        }    
    }

    if (empty($_POST['description'])) {
        $arrayFormError['description_error'] = 'Description is required.';
    } else {
        if ($arrayFormDetails['description'] != $_POST['description']){
            $arrayFormDetails['description'] = strip_tags($_POST['description']);
        } else {
            $arrayFormError['description_error'] = 'Same description';
        }
    }

    if (empty($_POST['price'])) {
        $arrayFormError['price_error'] = 'Price is required.';
    } else {
        if ($arrayFormDetails['price'] != $_POST['price']){
            $arrayFormDetails['price'] = strip_tags($_POST['price']);
        } else {
            $arrayFormError['price_error'] = 'Same price';
        }
    }

    if (empty($_POST['image'])) {
        $arrayFormError['image_error'] = 'Image is required.';
    } else {
        if ($arrayFormDetails['image'] != $_POST['image']){
            $arrayFormDetails['image'] = strip_tags($_POST['image']);
        } else {
            $arrayFormError['image_error'] = 'Same image';
        }
    }
    
    //upload database
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
        $_SESSION['imageMod'] = $fileDestination;
        $image = $fileDestination;
        move_uploaded_file($fileTmpName, $fileDestination);
    }

    // insert/update date in database
    if (strlen($_POST['submit'])!=0) {
        $sqlEdit = 'UPDATE products SET  title = ?, description = ?, price = ? , image = ? WHERE id = ?';
        $resultEdit = $connection->prepare($sqlEdit);
        $resultEdit->execute([$arrayFormDetails['title'], $arrayFormDetails['description'], $arrayFormDetails['price'], $_POST['image'], $_POST['submit']]);
        header('Location: product.php?editId=' . $_POST['submit']);
        exit;
    } else {
        $sqlEdit = 'INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES (?, ?, ?, ?)';
        $resultEdit = $connection->prepare($sqlEdit);
        $resultEdit->execute([$arrayFormDetails['title'], $arrayFormDetails['description'], $arrayFormDetails['price'], $image]);
        header('Location: product.php');
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
    <title><?= translateLabels('Product Page'); ?></title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <form action="product.php" method="post" enctype="multipart/form-data" class="myProductFrom">
        <input class="item-i" type="text" name="title" value="<?= $arrayFormDetails['title']; ?>" placeholder="<?= translateLabels('Title'); ?>"><br>
        <span class="error"><?= $arrayFormError['title_error'] ?></span>
        <input class="item-i" type="text" name="description" value="<?= $arrayFormDetails['description']; ?>" placeholder="<?= translateLabels('Description'); ?>"><br>
        <span class="error"><?= $arrayFormError['description_error'] ?></span>
        <input class="item-i" type="text" name="price" value="<?= $arrayFormDetails['price']; ?>" placeholder="<?= translateLabels('Price'); ?>"><br>
        <span class="error"><?= $arrayFormError['price_error'] ?></span>
        <input class="item-j" type="text" name="image" value="<?= $arrayFormDetails['image']; ?>"  placeholder="<?= translateLabels('Image'); ?>">
        <input class="item-j-x" type="file" name="file"><br>
        <a class="item-j-y" href="products.php"><?= translateLabels('Products'); ?></a>
        <?php if(isset($_GET['editId'])): ?> 
            <button class="item-j-y" type="submit" name="submit" value="<?= $_GET['editId']; ?>" ><?= translateLabels('Save'); ?></button> 
        <?php else: ?>
            <button class="item-j-y" type="submit" name="submit" ><?= translateLabels('Save'); ?></button>
        <?php endif; ?>
    </form>
</body>
</html>
