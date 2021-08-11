<?php

require_once 'common.php';

if (isset($_GET['id'])) {
    $sql = 'SELECT orderItem.order_id,orders.creation_date, orders.customer_name, orders.adress, orders.comment, products.price, products.title, products.description, products.image
            FROM ((orderItem
            INNER JOIN orders ON orderItem.order_id = orders.order_id)
            INNER JOIN products ON orderItem.id = products.id) WHERE orderItem.order_id = ?;';

    $previewProduct = $connection->prepare($sql);
    $previewProduct->execute([$_GET['id']]);
    $res = $previewProduct->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=translateLabels('Preview order');?></title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
    <center>
        <h1><?=translateLabels('Order');?> #<?=$res[0]['order_id'];?></h1>
    </center>
    <div class="full-section">
        <div class="section-i">
            <ul>
                <li class="checkout-box">
                    <div class="checkout-i">
                        <div><?=translateLabels('Date');?>: <?=$res[0]['creation_date'];?></div>
                        <div><?=translateLabels('Customer');?>: <?=$res[0]['customer_name'];?></div>
                        <div><?=translateLabels('Adress');?>: <?=$res[0]['adress'];?></div>
                        <div><?=translateLabels('Commnets');?>: <?=$res[0]['comment'];?></div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="section-i">
            <?php foreach ($res as $previewInfo): ?>
                <div class="section-i-info">
                    <div class="info-section">
                        <img src="<?=$previewInfo['image'];?>" alt="<?=translateLabels('image');?>">
                    </div>
                    <div class="info-section">
                        <ul>
                            <li><?=translateLabels('Product');?>: <?=$previewInfo['title'];?></li>
                            <li><?=translateLabels('Description');?>: <?=$previewInfo['description'];?></li>
                            <li><?=translateLabels('Price');?>: <?=$previewInfo['price'];?></li>
                        </ul>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <div class="section-i"><a href="orders.php"><?=translateLabels('Back');?></a></div>
    </div>
</body>
</html>