<?php

require_once 'common.php';

//if logout is push
if (isset($_POST['logout'])) {
    $_SESSION['username'] = null;
    $_SESSION['password'] = null;
}
//the consequences of pressing the button logout redirect to login
if (is_null($_SESSION['username']) && is_null($_SESSION['password'])) {
    header('Location: login.php');
    exit;
}
//when delete an element
if (isset($_POST['id'])) {
    $sqlDelete = 'DELETE FROM products WHERE id = ?';
    $resultDelete = $connection->prepare($sqlDelete);
    $resultDelete->execute([$_POST['id']]);
    header('Location: products.php');
    exit;
}

//when edit an element
if (isset($_POST['edit'])) {
    $sqlEdit = 'SELECT * FROM products WHERE id = ?';
    $resultEdit = $connection->prepare($sqlEdit);
    $resultEdit->execute([$_POST['edit']]);
    $fetchEdit = $resultEdit->fetchAll();
    
    $_SESSION['titleMod'] = $fetchEdit[0]['title'];
    $_SESSION['descriptionMod'] = $fetchEdit[0]['description'];
    $_SESSION['priceMod'] = $fetchEdit[0]['price'];
    $_SESSION['imageMod'] = $fetchEdit[0]['image'];
    $_SESSION['idMod'] = $fetchEdit[0]['id'];
    header('Location: product.php');
    exit;
}

if (isset($_POST['add'])) {
    unset($_SESSION['titleMod']);
    unset($_SESSION['descriptionMod']);
    unset($_SESSION['priceMod']);
    unset($_SESSION['imageMod']);
    unset($_SESSION['idMod']);
    header('Location: product.php');
    exit;
}

$sql = "SELECT * FROM products;";
$result = $connection->query($sql);
$res = $result->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Products page!'); ?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-section">
        <form action="products.php" method="post">
            <?php foreach($res as $product): ?>
                <div class="full-section-products" >
                    <div class="info-section">
                        <img src="<?= $product['image']; ?>" alt="<?= translateLabels('image'); ?>">
                    </div>
                    <div class="info-section">
                        <ul>
                            <li><?= $product['title']; ?></li>
                            <li><?= $product['description']; ?></li>
                            <li><?= $product['price']; ?></li>
                        </ul>
                    </div>
                    <div class="info-section">
                        <button type="submit" name="edit" value="<?= $product['id']; ?>"><?= translateLabels('Edit'); ?></button>
                    </div>
                    <div class="info-section">
                        <button type="submit" name="id" value="<?= $product['id']; ?>"><?= translateLabels('Delete'); ?></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
        <form action="products.php" method="post">
            <div class="cart-section-products">
                <button type="submit" name="add"><?= translateLabels('Add'); ?></button>
            </div>
            <div class="cart-section-products">
                <button type="submit" name="logout"><?= translateLabels('Logout'); ?></button>
            </div>
        </form>
    </div><br>
    <a class="comments" href="comment.php">Comments</a>
</body>
</html>