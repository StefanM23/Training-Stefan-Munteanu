<?php

require_once 'common.php';

if (isset($_GET['id'])) {
    $sql = 'SELECT * FROM products WHERE id = ?';
    $result = $connection->prepare($sql);
    $result->execute([$_GET['id']]);
    $resultFetchAll = $result->fetch(PDO::FETCH_ORI_FIRST);

    //if result is false than will redirect to the orders.php
    if (!$resultFetchAll) {
        header('Location: index.php');
        exit;
    }
}

$comment_error = '';
$comment_message = '';

if (isset($_POST['send'])) {
    //validation
    if (empty($_POST['comment'])) {
        $comment_error = 'You did not write anything';
    } else {
        $comment_message = strip_tags($_POST['comment']);
    }

    if (isset($_POST['comment']) && strlen($comment_message) != 0) {
        $sql = 'INSERT INTO `comments`(`product_id`, `comment`, `accepted`) VALUES (?, ?, ?)';
        $resultInsert = $connection->prepare($sql);
        $resultInsert->execute([$_GET['id'], $comment_message, false]);
        header('Location: product_detail.php?id=' . $_GET['id']);
        exit;
    }
}
$sqlComments = 'SELECT * FROM comments WHERE accepted = 1 AND product_id = ?';
$resultComments = $connection->prepare($sqlComments);
$resultComments->execute([$_GET['id']]);
$resultFetchComments = $resultComments->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Product Page'); ?></title>
    <link rel="stylesheet" href="product_detail.css">
</head>
<body>
    <div class="container">
        <div class="container-product">
            <div class="container-image">
                <img src="<?= $resultFetchAll['image']; ?>" alt="<?= translateLabels('image'); ?>">
            </div>
            <div class="container-description">
                <div><?= translateLabels('Title'); ?>: <?= $resultFetchAll['title']; ?></div>
                <div><?= translateLabels('Description'); ?>: <?= $resultFetchAll['description']; ?></div>
                <div><?= translateLabels('Price'); ?>: <?= $resultFetchAll['price']; ?> $</div>
                <div><a href="index.php"><?= translateLabels('Back'); ?></a></div>
            </div>
        </div>
        <div class="container-body">
            <form action="product_detail.php?id=<?= $_GET['id']; ?>" method="post">
                <div class="container-comment">
                    <textarea name="comment" placeholder="<?= translateLabels('You can leave a comment about the product'); ?>" style="resize: none;" cols="30" rows="5"><?= $comment_message; ?></textarea><br>
                    <span class="error"><?= $comment_error; ?></span>
                    <button type="submit" name="send"><?= translateLabels('Send'); ?></button>
                </div>
            </form>
            <div class="container-list-comment">
                <h4><?= translateLabels('-------List of comments-------'); ?></h2>
                <ol>
                    <?php foreach ($resultFetchComments as $comment): ?>
                        <li><?= $comment['comment']; ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>