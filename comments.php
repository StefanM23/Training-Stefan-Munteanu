<?php

require_once 'common.php';

$sqlComments = 'SELECT * FROM comments';
$result = $connection->query($sqlComments);
$resultFetchComments = $result->fetchAll();

if (isset($_POST['add'])) {
    $sqlUpdate = 'UPDATE comments SET accepted = ? WHERE id = ?';
    $resultUpdate = $connection->prepare($sqlUpdate);
    $resultUpdate->execute([true, $_POST['add']]);
    header('Location: comments.php');
    exit;
}
if (isset($_POST['delete'])) {
    $sqlDelete = 'DELETE FROM comments WHERE id = ?';
    $resultDelete = $connection->prepare($sqlDelete);
    $resultDelete->execute([$_POST['delete']]);
    header('Location: comments.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Admin Comments'); ?></title>
    <link rel="stylesheet" href="comments.css">
</head>
<body>
    <div class="container">
        <div class="container-title">
            <h1><?= translateLabels('List of comments'); ?></h1>
        </div>
        <form action="comments.php" method="post">
            <?php foreach ($resultFetchComments as $comment): ?>
                <div class="container-comments">
                    <div class="container-comments-body">
                        <p><?= $comment['comment']; ?></p>
                    </div>
                    <div class="container-comments-aprove">
                        <button type="submite" name="add" value="<?= $comment['id']; ?>"><?= translateLabels('Add'); ?></button>
                    </div>
                    <div class="container-comments-delete">
                        <button type="submite" name="delete" value="<?= $comment['id']; ?>"><?= translateLabels('Delete'); ?></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
    </div>
</body>
</html>