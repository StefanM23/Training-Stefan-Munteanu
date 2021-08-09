<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
</head>
<body>
    <?php foreach ($res as $product): ?>
        <br><b>Product <?= $product['title']; ?>:</b><br>
        <img src="<?= $product['image']; ?>" alt="image"><br>
        <b>Product description:</b>
        <ol>
            <li><?= $product['title']; ?></li>
            <li><?= $product['description']; ?></li>
            <li><?= $product['price']; ?></li>
        </ol>
    <?php endforeach; ?>
</body>
</html>
