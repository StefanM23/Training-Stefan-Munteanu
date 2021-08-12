<?php

require_once 'common.php';

if (isset($_POST['id'])) {
    $item = $_POST['id'];
    $_SESSION['cart'][$item] = $item;
    header('Location: index.php');
    exit;
}

if (!empty($_SESSION['cart'])) {
    $strInsert = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $sql = 'SELECT * FROM products WHERE id NOT IN (' . $strInsert . ');';

    $result = $connection->prepare($sql);

    $result->execute(array_values($_SESSION['cart']));
} else {
    $sql = 'SELECT * FROM products;';
    $result = $connection->query($sql);
}

$res = $result->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Index Page'); ?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-section">
        <form action="index.php" method="post">
            <?php foreach ($res as $product): ?>
                    <div class="full-section">
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
                            <button type="submit" name="id" value="<?= $product['id']; ?>"><?= translateLabels('Add'); ?></button>
                        </div>
                        <div class="info-section">
                            <a href="element.php?id=<?= $product['id']; ?>"><?= translateLabels('View'); ?></a>
                        </div>
                    </div>
            <?php endforeach; ?>
        </form>
        <div class="cart-section">
            <a href="cart.php"><?= translateLabels('Go to cart'); ?></a>
        </div>
    </div>
</body>
</html>