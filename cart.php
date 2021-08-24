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
$countCart = 0;
if (!empty($_SESSION['cart'])) {
    $countCart = count($_SESSION['cart']);
}

$strInsert = implode(',', array_fill(0, $countCart, '?'));
$sql = 'SELECT * FROM products WHERE id IN (' . $strInsert . ');';
$result = $connection->prepare($sql);

if (!empty($strInsert)) {
    $result->execute(array_values($_SESSION['cart']));
}

$resultFetchAll = $result->fetchAll();

$arrayFormDetails = [
    'name' => '',
    'contacts' => '',
    'comments' => '',
];
$arrayFormError = [
    'name_error' => '',
    'contacts_error' => '',
    'comments_error' => '',
];
if (isset($_POST['checkout'])) {

    //server-side validation
    if (empty($_POST['name'])) {
        $arrayFormError['name_error'] = 'Name is required.';
    } else {
        $arrayFormDetails['name'] = strip_tags($_POST['name']);
    }

    if (empty($_POST['contacts'])) {
        $arrayFormError['contacts_error'] = 'Contacts is required.';
    } else {
        $arrayFormDetails['contacts'] = strip_tags($_POST['contacts']);
    }

    if (empty($_POST['comments'])) {
        $arrayFormError['comments_error'] = 'Comments is required.';
    } else {
        $arrayFormDetails['comments'] = strip_tags($_POST['comments']);
    }

    if (!empty($arrayFormDetails['name']) && !empty($arrayFormDetails['contacts'])) {
        $header = "From: <demo@stefan.me>\r\n";
        $header .= "MIME-VERSION: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $subject = 'Order' . $arrayFormDetails['name'];

        ob_start();
        include 'mail_template.php';
        $mailTemplate = ob_get_clean();

        mail(MANAGER_EMAIL, $subject, $mailTemplate, $header);
    
        $dateTime = new DateTime();
        $dateISO = $dateTime->format('c');
    
        $sqlOrder = 'INSERT INTO `orders`(`customer_name`, `customer_address`, `customer_comment`, `creation_date`) VALUES (?, ?, ?, ?);';
        $resultOrder = $connection->prepare($sqlOrder);
        $resultOrder->execute([$arrayFormDetails['name'], $arrayFormDetails['contacts'], $arrayFormDetails['comments'], $dateISO]);

        $lastOrderInsertId = (int) $connection->lastInsertId();
        $sqlOrderProducts = 'INSERT INTO `order_product`(`order_id`, `product_id`, `price`) VALUES (?, ?, ?);';
        $resultOrderProducts = $connection->prepare($sqlOrderProducts);

        foreach ($resultFetchAll as $product) {
            $resultOrderProducts->execute([$lastOrderInsertId, $product['id'], $product['price']]);
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
            <input type="text" name="name" placeholder="<?= translateLabels('Name'); ?>" value="<?= $arrayFormDetails['name']; ?>">
            <span class="error"><?= $arrayFormError['name_error']; ?></span>
            <textarea name="contacts" style="resize: none;"  cols="30" rows="2" placeholder="<?= translateLabels('Contact details'); ?>"><?= $arrayFormDetails['contacts']; ?></textarea>
            <span class="error"><?= $arrayFormError['contacts_error']; ?></span>
            <textarea name="comments" style="resize: none;" cols="30" rows="4" placeholder="<?= translateLabels('Comments'); ?>" ><?= $arrayFormDetails['comments']; ?></textarea>
            <span class="error"><?= $arrayFormError['comments_error']; ?></span>
            <a href="index.php"><?= translateLabels('Go to Index'); ?></a>
            <button type="submit" name="checkout"><?= translateLabels('Checkout'); ?></button>
        </form>
    </div>
</body>
</html>