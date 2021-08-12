<?php

require_once 'common.php';

if (isset($_GET['id'])) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $result = $connection->prepare($sql);
    $result->execute([$_GET['id']]);
    $res = $result->fetchAll();
}
if (isset($_POST['send'])) {
    echo $_POST['comment'];
    if (isset($_POST['comment']) && !empty($_POST['comment'])) {
        $sql = 'INSERT INTO `comments`(`id`, `comment`, `completed`) VALUES (?, ?, ?)';
        $resultInsert = $connection->prepare($sql);
        $resultInsert->execute([$_GET['id'], $_POST['comment'], false]);
        header('Location: element.php?id=' . $_GET['id']);
        exit;
    }
}
$sqlC = 'SELECT * FROM comments WHERE completed = 1 AND id = ?';
$resultC = $connection->prepare($sqlC);
$resultC->execute([$_GET['id']]);
$resC = $resultC->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Product Page'); ?></title>
    <link rel="stylesheet" href="element.css">
</head>
<body>
    <div class="container">
        <div class="container-product">
            <div class="container-image">
                <img src="<?= $res[0]['image']; ?>" alt="<?= translateLabels('image'); ?>">
            </div>
            <div class="container-description">
                <div><?= translateLabels('Title'); ?>: <?= $res[0]['title']; ?></div>
                <div><?= translateLabels('Description'); ?>: <?= $res[0]['description']; ?></div>
                <div><?= translateLabels('Price'); ?>: <?= $res[0]['price']; ?> $</div>
                <div><a href="index.php"><?= translateLabels('Back'); ?></a></div>
            </div>
        </div>
        <div class="container-body">
            <form action="element.php?id=<?= $_GET['id'];?> " method="post">
                <div class="container-comment">
                    <textarea name="comment" placeholder="<?= translateLabels('You can leave a comment about the product'); ?>" style="resize: none;" cols="30" rows="5"></textarea><br>
                    <button type="submit" name="send"><?= translateLabels('Send'); ?></button>
                </div>
            </form>
            <div class="container-list-comment">
                <h4><?= translateLabels('-------List of comments-------'); ?></h2>
                <ol>
                    <?php foreach ($resC as $item): ?>
                        <li><?= $item['comment']; ?></li>
                    <?php endforeach;?>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>