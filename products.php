<?php

require_once 'common.php';

if (!isset($_SESSION['username'])) {
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

$sql = "SELECT * FROM products;";
$result = $connection->query($sql);
$resultFetchAll = $result->fetchAll();

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
            <?php foreach($resultFetchAll as $product): ?>
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
                        <a href="product.php?editId=<?= $product['id']; ?>" name="edit"><?= translateLabels('Edit'); ?></a>
                    </div>
                    <div class="info-section">
                        <button type="submit" name="id" value="<?= $product['id']; ?>"><?= translateLabels('Delete'); ?></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
        <form action="logout.php" method="post">
            <div class="cart-section-products">
                <a href="product.php" name="add"><?= translateLabels('Add'); ?></a>
            </div>
            <div class="cart-section-products">
                <button type="submit" name="logout"><?= translateLabels('Logout'); ?></button>
            </div>
        </form>
    </div><br>
    <a class="comments" href="comment.php"<?= translateLabels('Comments'); ?>></a>
</body>
</html>