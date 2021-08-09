<?php

require_once 'common.php';
require_once 'config.php';

//prepare string HTML with products for email
function loadHTML($result)
{
    $message = '<html>
                <head>
                    <title>Order</title>
                </head>
                <body>';
    foreach ($result as $product) {
        $message .= '<br><b>Product ' . $product['title'] . ' :</b><br>
                        <img src="' . $product['image'] . '" alt="image"><br>
                        <b>Product description:</b>
                        <ol>
                            <li>' . $product['title'] . '</li>
                            <li>' . $product['description'] . '</li>
                            <li>' . $product['price'] . '</li>
                        </ol>';
    }
    $message .= '</body></html>';
    return $message;
}

//when remove items
if (isset($_POST['id'])) {
    $item = $_POST['id'];
    unset($_SESSION['cart'][$item]);
    header('Location: cart.php');
    exit;
}

//prepare sql statement for query and fetch data for the cart
if (!empty($_SESSION['cart'])) {
    $strInsert = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $sql = 'SELECT * FROM products WHERE id IN (' . $strInsert . ');';

    $result = $connection->prepare($sql);

    $result->execute(array_values($_SESSION['cart']));
} else {
    $sql = 'SELECT * FROM products WHERE id=-1;';
    $result = $connection->query($sql);
}
$res = $result->fetchAll();

if (isset($_POST['checkout'])) {

    $nameClient = $_POST['name'];
    $nameClient = strip_tags($nameClient);
    $addressClient = $_POST['contacts'];
    $addressClient = strip_tags($addressClient);
    $commentClient = $_POST['comments'];
    $commentClient = strip_tags($commentClient);

    if (!empty($nameClient) && !empty($addressClient)) {

        $header = "From: <demo@stefan.me> \r\n";
        $header .= "MIME-VERSION: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $subject = 'Order for ' . $nameClient;

        $message = ' The order will be delivered to  ';
        $message .= $addressClient . ' and the command is :';

        $message .= loadHTML($res);

        if (!empty($commentClient)) {
            $message .= "<b>Comments:</b>\n" . $commentClient;
        }
        mail(MANAGER_EMAIL, $subject, $message, $header);
        header('Location: cart.php');
        exit;
    } else {
        echo '<script>alert("You must enter your name and contacts!")</script>';
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
            <?php foreach ($res as $product): ?>
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
                        <button name="id" value="<?= $product['id']; ?>"><?= translateLabels('Remove'); ?></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
        <form action="cart.php" method="post">
            <input type="text" name="name" placeholder="<?= translateLabels('Name'); ?>" value="<?= isset($_POST['name']) ? $_POST['name'] : ''; ?>"><br>
            <textarea name="contacts" style="resize: none; id="" cols="30" rows="2" placeholder="<?= translateLabels('Contact details'); ?>" ><?= isset($_POST['contacts']) ? $_POST['contacts'] : ''; ?></textarea><br>
            <textarea name="comments" style="resize: none; id="" cols="30" rows="4" placeholder="<?= translateLabels('Comments'); ?>"><?= isset($_POST['comments']) ? $_POST['comments'] : ''; ?></textarea><br>
            <a href="index.php"><?= translateLabels('Go to Index'); ?></a>
            <button type="submit" name="checkout"><?= translateLabels('Checkout'); ?></button>
        </form>
    </div>
</body>
</html>