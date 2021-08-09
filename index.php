<?php

require_once 'common.php';

if (isset($_POST['id'])) {
    $item = $_POST['id'];
    $_SESSION['cart'][$item] = $item;
    header("Location: index.php");
}

if (!empty($_SESSION['cart'])) {
    $str_insert = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $sql = 'SELECT * FROM products WHERE id NOT IN (' . $str_insert . ');';

    $result = $connection->prepare($sql);

    $arr = $_SESSION['cart'];
    sort($arr);
    $result->execute($arr);

} else {
    $sql = "SELECT * FROM products;";
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
    <title><?= translateLabels("Index Page"); ?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-section">
        <form action="index.php" method="post">
            <?php for ($i = 0; $i < count($res); ++$i): ?>
                    <div class="full-section">
                        <div class="info-section">
                            <img src="<?= $res[$i]['image']; ?>" alt="<?= translateLabels('image'); ?>">
                        </div>
                        <div class="info-section">
                            <ul>
                                <li><?= $res[$i]['title']; ?></li>
                                <li><?= $res[$i]['description']; ?></li>
                                <li><?= $res[$i]['price']; ?></li>
                            </ul>
                        </div>
                        <div class="info-section">
                            <button type="submit" name="id" value="<?= $res[$i]['id']; ?>"><?= translateLabels('Add'); ?></button>
                        </div>
                    </div>
            <?php endfor;?>
        </form>
        <div class="cart-section">
            <a href="cart.php"><?= translateLabels('Go to cart'); ?></a>
        </div>
    </div>
</body>
</html>