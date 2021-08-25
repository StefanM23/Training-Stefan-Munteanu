<?php

require_once 'common.php';

$sql = 'SELECT order_product.order_id,
               orders.creation_date, 
               orders.customer_name, 
               orders.customer_address, 
               orders.customer_comment ,
               SUM(order_product.price) as sum_prices
        FROM (orders
        INNER JOIN order_product ON order_product.order_id = orders.id) 
        GROUP BY order_product.order_id;';
       
$result = $connection->query($sql);
$resultFetchAll = $result->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Orders Page'); ?></title>
    <link rel="stylesheet" href="orders.css">
</head>
<body>
    <ul>
        <?php foreach ($resultFetchAll as $orderInfo): ?>
            <li class="checkout-box">
                <div class="checkout-i">
                    <div><?= translateLabels('Date'); ?>: <?= $orderInfo['creation_date']; ?></div>
                    <div><?= translateLabels('Customer'); ?>: <?= $orderInfo['customer_name']; ?></div>
                    <div><?= translateLabels('Adress'); ?>: <?= $orderInfo['customer_address']; ?></div>
                    <div><?= translateLabels('Comments'); ?>: <?= $orderInfo['customer_comment']; ?></div>
                    <div><?= translateLabels('Total order'); ?>: <?= $orderInfo['sum_prices']; ?></div>
                </div>
                <div class="checkout-j"><a href="order.php?id=<?= $orderInfo['order_id']; ?>"><?= translateLabels('View'); ?></a></div>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
