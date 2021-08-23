<?php

require_once 'common.php';

//when remove items
if (isset($_POST['id'])) {
    $item = $_POST['id'];
    unset($_SESSION['cart'][$item]);
    header('Location: cart.php');
    exit;
}

//prepare sql statement for query and fetch data for the cart

$strInsert = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
$sql = 'SELECT * FROM products WHERE id IN (' . $strInsert . ');';

$result = $connection->prepare($sql);

$result->execute(array_values($_SESSION['cart']));

$resultFetchAll = $result->fetchAll();

if (isset($_POST['checkout'])) {


    $arrayDetails = [
        'name' => strip_tags($_POST['name']),
        'contacts' => strip_tags($_POST['contacts']),
        'comments' => strip_tags($_POST['comments']),
    ];
   
    if (!empty($arrayDetails['name']) && !empty($arrayDetails['contacts'])) {
        
        $header = "From: <demo@stefan.me>\r\n";
        $header .= "MIME-VERSION: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $subject = 'Order for ' . $arrayDetails['name'];
        $output = 'The command for:' . $arrayDetails['name'] . ' ,' . ' and the address is: ' . $arrayDetails['contacts'];

        ob_start();
        include 'model.php';
        $output .= ob_get_clean();

        $output .= "<b>Comments:</b>\n" . $arrayDetails['comments'];
       
        mail(MANAGER_EMAIL, $subject, $output, $header);

        $objDateTime = new DateTime();
        $dateISO = $objDateTime->format('c');
        $arrProductsId = array_column($res, 'id');

        $sqlOrder = 'INSERT INTO `orders`(`customer_name`, `adress`, `comment`, `creation_date`) VALUES (?, ?, ?, ?);';
        $resultOrder = $connection->prepare($sqlOrder);
        $resultOrder->execute([$nameClient, $addressClient, $commentClient, $dateISO]);

        $lastOrderId = (int) $connection->lastInsertId();
        $sqlOrderItem = 'INSERT INTO `orderitem`(`order_id`, `id`) VALUES (?, ?);';
        $resultOrderItem = $connection->prepare($sqlOrderItem);
        foreach ($arrProductsId as $order) {
            $resultOrderItem->execute([$lastOrderId, $order]);
        }
        header('Location: cart.php');
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
    <title><?= translateLabels('Cart Page'); ?></title>
    <link rel="stylesheet" href="/ProjectMS/index.css">
</head>
<body>
    <div class="main-section">
        <form action="cart.php" method="post">
            <?php foreach ($resultFetchAll as $product): ?>
                <div class="full-section">
                    <div class="info-section">
                        <img src="<?= $product['image']; ?> " alt="<?= translateLabels('image'); ?>">
                    </div>
                    <div class="info-section">
                        <ul>
                            <li><?= $product['title']; ?></li>
                            <li><?= $product['description']; ?></li>
                            <li><?= $product['price']; ?></li>
                        </ul>
                    </div>
                    <div class="info-section">
                        <button type="submit" name="id" value="<?= $product['id']; ?>"><?= translateLabels('Remove'); ?></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
        <form action="cart.php" method="post">
            <input type="text" name="name" placeholder="<?= translateLabels('Name'); ?>" value="<?= isset($_POST['name']) ? $_POST['name'] : ''; ?>" require><br>
            <textarea name="contacts" style="resize: none;"  cols="30" rows="2" placeholder="<?= translateLabels('Contact details'); ?>" require><?= isset($_POST['contacts']) ? $_POST['contacts'] : ''; ?></textarea><br>
            <textarea name="comments" style="resize: none;" cols="30" rows="4" placeholder="<?= translateLabels('Comments'); ?>" ><?= isset($_POST['comments']) ? $_POST['comments'] : ''; ?></textarea><br>
            <a href="index.php"><?= translateLabels('Go to Index'); ?></a>
            <button type="submit" name="checkout"><?= translateLabels('Checkout'); ?></button>
        </form>
    </div>
</body>
</html>