<?php 

require_once "common.php";

//if logout is push
if(isset($_GET['logout']) & !empty($_GET['logout'])){
    $_SESSION['username']=null;
    $_SESSION['password']=null;
}
//the consequences of pressing the button logout redirect to login
if(is_null($_SESSION['username'])&& is_null($_SESSION['password'])){
    header("Location: http://localhost/ProjectMS/login.php");
    exit;
}
//when delete a element
if(isset($_POST['id'])){
    $sqlDelete="DELETE FROM products WHERE id=?";
    $result=$connection->prepare($sqlDelete);
    $result->execute([$_POST['id']]);
}
$sql="SELECT * FROM products;";
$result=$connection->query($sql);

$imageProducts=[];
$titleProducts=[];
$descriptionProducts=[];
$priceProducts=[];
$idProducts=[];

while(($row=$result->fetch(PDO::FETCH_ASSOC))!==false){
    array_push($imageProducts,$row['image']);
    array_push($titleProducts,$row['title']);
    array_push($descriptionProducts,$row['description']);
    array_push($priceProducts,$row['price']);
    array_push($idProducts,$row['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=translateLabels("Products page!");?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-section">
        <form action="products.php" method="post">  
            <?php for($i=0;$i<count($titleProducts);++$i): ?>
                <div class="full-section-products" >
                    <div class="info-section">
                        <img src="<?=$imageProducts[$i]; ?>" alt="<?=translateLabels("image");?>">
                    </div>
                    <div class="info-section">
                        <ul>
                            <li><?=$titleProducts[$i]; ?></li>
                            <li><?=$descriptionProducts[$i]; ?></li>
                            <li><?=$priceProducts[$i]; ?>$</li>
                        </ul>
                    </div>
                    <div class="info-section">
                        <a href="products.php?id="><?=translateLabels("Edit");?></a>
                    </div>
                    <div class="info-section">
                        <button name="id" value="<?=$idProducts[$i]; ?>"><?=translateLabels("Delete");?></button>
                    </div>
                </div> 
            <?php endfor; ?>
        </form>
        <div class="cart-section-products">
            <a href="products.php"><?=translateLabels("Add");?></a>
        </div>
        <div class="cart-section-products">
            <a href="products.php?logout=Logout"><?=translateLabels("Logout");?></a>
        </div>
    </div>
</body>
</html>