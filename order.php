<?php

require_once 'common.php';

if (isset($_GET['id'])) {

    //query for details retrieval
    $sqlOrderDetails = 'SELECT order_product.order_id,
                               orders.creation_date,
                               orders.customer_name,
                               orders.customer_address,
                               orders.customer_comment
                        FROM (order_product
                        INNER JOIN orders ON order_product.order_id = orders.id)
                        GROUP BY order_product.order_id
                        HAVING order_product.order_id = ?;';

    $previewOrderDetails = $connection->prepare($sqlOrderDetails);
    $previewOrderDetails->execute([$_GET['id']]);

    $resultOrderDetails = $previewOrderDetails->fetch(PDO::FETCH_ORI_FIRST);
    
    //if result is false than will redirect to the orders.php
    if (!$resultOrderDetails) {
        header('Location: orders.php');
        exit;
    }
    //query for products order retrieval
    $sqlOrderProducts = 'SELECT order_product.order_id,
                                 order_product.price,
                                 products.title,
                                 products.description,
                                 products.image
                            FROM (order_product
                            INNER JOIN products ON order_product.product_id = products.id)
                            WHERE order_product.order_id = ?;';

    $previewOrderProducts = $connection->prepare($sqlOrderProducts);
    $previewOrderProducts->execute([$_GET['id']]);
    $resultOrderProducts = $previewOrderProducts->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Preview order'); ?></title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
    <center>
        <h1><?= translateLabels('Order'); ?> #<?= $resultOrderDetails['order_id']; ?></h1>
    </center>
    <div class="full-section">
        <div class="section-i">
            <ul>
                <li class="checkout-box">
                    <div class="checkout-i">
                        <div><?= translateLabels('Date'); ?>: <?= $resultOrderDetails['creation_date']; ?></div>
                        <div><?= translateLabels('Customer'); ?>: <?= $resultOrderDetails['customer_name']; ?></div>
                        <div><?= translateLabels('Adress'); ?>: <?= $resultOrderDetails['customer_address']; ?></div>
                        <div><?= translateLabels('Commnets'); ?>: <?= $resultOrderDetails['customer_comment']; ?></div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="section-i">
            <?php foreach ($resultOrderProducts as $previewInfo): ?>
                <div class="section-i-info">
                    <div class="info-section">
                        <img src="<?= $previewInfo['image']; ?>" alt="<?= translateLabels('image'); ?>">
                    </div>
                    <div class="info-section">
                        <ul>
                            <li><?= translateLabels('Product'); ?>: <?= $previewInfo['title']; ?></li>
                            <li><?= translateLabels('Description'); ?>: <?= $previewInfo['description']; ?></li>
                            <li><?= translateLabels('Price'); ?>: <?= $previewInfo['price']; ?></li>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section-i"><a href="orders.php"><?= translateLabels('Back'); ?></a></div>
    </div>
</body>
</html>