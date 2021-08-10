<?php

require_once 'common.php';

//if logout is push
if (isset($_GET['logout']) & !empty($_GET['logout'])) {
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
    $sqlDelete = 'DELETE FROM products WHERE id=?';
    $resultDelete = $connection->prepare($sqlDelete);
    $resultDelete->execute([$_POST['id']]);
    header('Location: products.php');
    exit;
}

//when edit an element
if (isset($_POST['edit'])) {
    $sqlEdit = 'SELECT * FROM products WHERE id=?';
    $resultEdit = $connection->prepare($sqlEdit);
    $resultEdit->execute([$_POST['edit']]);
    $fetchEdit = $resultEdit->fetchAll();
    
    $_SESSION['titleMod'] =  $fetchEdit[0]['title'];
    $_SESSION['descriptionMod'] =  $fetchEdit[0]['description'];
    $_SESSION['priceMod'] =  $fetchEdit[0]['price'];
    $_SESSION['imageMod'] =  $fetchEdit[0]['image'];
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
        <div class="cart-section-products">
            <a href="product.php"><?= translateLabels('Add'); ?></a>
        </div>
        <div class="cart-section-products">
            <a href="products.php?logout=Logout"><?= translateLabels('Logout'); ?></a>
        </div>
    </div>
</body>
</html>