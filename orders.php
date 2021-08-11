<?php

require_once 'common.php';

$sql = 'SELECT orderItem.order_id,orders.creation_date, orders.customer_name, orders.adress, orders.comment, SUM(products.price) as sum_order
        FROM ((orderItem
        INNER JOIN orders ON orderItem.order_id = orders.order_id)
        INNER JOIN products ON orderItem.id = products.id) GROUP BY orderItem.order_id;';
$result = $connection->query($sql);
$res = $result->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=translateLabels('Orders Page');?></title>
    <link rel="stylesheet" href="orders.css">
</head>
<body>
    <ul>
        <?php foreach ($res as $orderInfo): ?>
            <li class="checkout-box">
                <div class="checkout-i">
                    <div><?=translateLabels('Date');?>: <?=$orderInfo['creation_date'];?></div>
                    <div><?=translateLabels('Customer');?>: <?=$orderInfo['customer_name'];?></div>
                    <div><?=translateLabels('Adress');?>: <?=$orderInfo['adress'];?></div>
                    <div><?=translateLabels('Comments');?>: <?=$orderInfo['comment'];?></div>
                    <div><?=translateLabels('Total order');?>: <?=$orderInfo['sum_order'];?></div>
                </div>
                <div class="checkout-j"><a href="order.php?id=<?=$orderInfo['order_id'];?>"><?=translateLabels('View');?></a></div>
            </li>
        <?php endforeach;?>
    </ul>
</body>
</html>
