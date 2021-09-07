<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
</head>
<body>
    <p>The command for: <?= $arrayFormDetails['name']; ?> ,and the address is: <?= $arrayFormDetails['contacts']; ?></p>
    <table border="1px" width="230px" height="120px">
        <?php foreach ($fetchProducts as $product): ?>
            <tr>
                <td><img src="http://localhost/ProjectMS/<?= $product['image']; ?>" width="100%" height="100%" alt="image"></td>
                <td>
                    <ul style="list-style-type:none;margin-left:-30px;width:110px">
                        <li><?= $product['title']; ?></li>
                        <li><?= $product['description']; ?></li>
                    </ul>
                </td>
                <td><?= $product['price']; ?></td>
            </tr>
       <?php endforeach; ?>
    </table>
    <p><b>Comments:</b> <?= $arrayFormDetails['comments']; ?></p>
</body>
</html>
