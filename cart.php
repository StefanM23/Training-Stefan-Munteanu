<?php 
    session_start();
    require_once('common.php');
    if(isset($_GET['id']) & !empty($_GET['id'])){
        $item=$_GET['id'];
        echo $_SESSION['cart'][$item];
        unset($_SESSION['cart'][$item]);
        echo $_SESSION['cart'][$item];
        
    }
    $sessionTitle=[];
    $sessionDescription=[];
    $sessionPrice=[];
    $sessionImage=[];
    $sessionId=[];
    for($i=1;$i<=count($_SESSION['cart']);++$i){
        $sql="SELECT * FROM products WHERE id=?";
        $statement=$connection->prepare($sql);
        $statement->execute([$_SESSION['cart'][$i]]); 
        if(($row=$statement->fetch(PDO::FETCH_ASSOC))!==false){
            array_push($sessionId,$row['id']);
            array_push($sessionTitle,$row['title']);
            array_push($sessionDescription,$row['description']);
            array_push($sessionPrice,$row['price']);
            array_push($sessionImage,$row['image']);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <link rel="stylesheet" href="/ProjectMS/index.css">
</head>
<body>
    <div class="main-section">
        <?php  for($i=0;$i<count($sessionTitle);++$i):?>
            <div class="full-section">
                <div class="info-section">
                    <img src="<?php echo $sessionImage[$i]; ?>" alt="poza">
                </div>
                <div class="info-section">
                    <ul>
                        <li><?php echo $sessionTitle[$i]; ?></li>
                        <li><?php echo $sessionDescription[$i];;?></li>
                        <li><?php echo $sessionPrice[$i]; ?></li>
                    </ul>
                </div>
                <div class="info-section">
                    <a href="cart.php?id=<?php echo $sessionId[$i]; ?>">Remove</a>
                </div>
            </div>
        <?php endfor; ?>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Name"><br>
            <textarea name="contacts" style="resize: none; id="" cols="30" rows="2" placeholder="Contact details"></textarea><br>
            <textarea name="comments" style="resize: none; id="" cols="30" rows="4" placeholder="Comments"></textarea><br>
            <a href="index.php">Go to Index</a>
            <button name="checkout">Checkout</button>
        </form>
    </div>
</body>
</html>