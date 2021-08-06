<?php

require_once "common.php";

if(isset($_POST['id'])){
    $item=$_POST['id'];
    $_SESSION['cart'][$item]=$item;
}

if(!empty($_SESSION['cart'])){
    $sql="SELECT * FROM products WHERE id NOT IN (";

    for($i=0;$i<count($_SESSION['cart']);++$i){
        $sql.="?,";
    }
    $sql=substr($sql ,0,-1);
    $sql.=");";

    $result=$connection->prepare($sql);
    $parameters=[];
    foreach($_SESSION['cart'] as $element){
        array_push($parameters,$element);
    }
    $result->execute($parameters);
    
}else{
    $sql="SELECT * FROM products;";
    $result=$connection->query($sql);
}

$imageIndex=[];
$titleIndex=[];
$descriptionIndex=[];
$priceIndex=[];
$idIndex=[];


while(($row=$result->fetch(PDO::FETCH_ASSOC))!==false){
    array_push($imageIndex,$row['image']);
    array_push($titleIndex,$row['title']);
    array_push($descriptionIndex,$row['description']);
    array_push($priceIndex,$row['price']);
    array_push($idIndex,$row['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=translateLabels("Index Page");?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-section">
        <form action="index.php" method="post">
            <?php for($i=0;$i<count($titleIndex);++$i): ?>
                    <div class="full-section">
                        <div class="info-section">
                            <img src="<?=$imageIndex[$i];?>" alt="<?=translateLabels("image");?>">
                        </div>
                        <div class="info-section">
                            <ul>
                                <li><?=$titleIndex[$i]; ?></li>
                                <li><?=$descriptionIndex[$i]; ?></li>
                                <li><?=$priceIndex[$i]; ?></li>
                            </ul>
                        </div>
                        <div class="info-section">
                            <button name="id" value="<?=$idIndex[$i]; ?>"><?=translateLabels("Add");?></button>
                        </div>
                    </div>
            <?php endfor; ?>
        </form>
        <div class="cart-section">
            <a href="cart.php"><?=translateLabels("Go to cart");?></a>
        </div>
    </div>
</body>
</html>