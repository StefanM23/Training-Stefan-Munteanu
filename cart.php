<?php 
    session_start();
    require_once('common.php');
    if(isset($_GET['id']) & !empty($_GET['id'])){
        $item=$_GET['id'];
        unset($_SESSION['cart'][$item]);    
    }
  
    if(!empty($_SESSION['cart'])){
        $sql="SELECT * FROM products WHERE id IN (";
        foreach($_SESSION['cart'] as $element){
            $sql.=$_SESSION['cart'][$element].",";
        }
        $sql=substr($sql ,0,-1);
        $sql.=");";  
    }else{
        $sql="SELECT * FROM products WHERE id=-1;";
    }
    $result=$connection->query($sql);

    if(isset($_POST['checkout'])){

        $nameClient=$_POST['name'];
        $nameClient=strip_tags($nameClient);

        $addressClient=$_POST['contacts'];
        $addressClient=strip_tags($addressClient);

        $commentClient=$_POST['comments'];
        $commentClient=strip_tags($commentClient);


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
        <?php while(($row=$result->fetch(PDO::FETCH_ASSOC))!==false): ?>
            <div class="full-section">
                <div class="info-section">
                    <img src="<?php echo $row['image']; ?>" alt="poza">
                </div>
                <div class="info-section">
                    <ul>
                        <li><?php echo $row['title']; ?></li>
                        <li><?php echo $row['description']; ?></li>
                        <li><?php echo $row['price']; ?></li>
                    </ul>
                </div>
                <div class="info-section">
                    <a href="cart.php?id=<?php echo $row['id']; ?>">Remove</a>
                </div>
            </div>
        <?php endwhile; ?>
        <form action="cart.php" method="post">
            <input type="text" name="name" placeholder="Name"><br>
            <textarea name="contacts" style="resize: none; id="" cols="30" rows="2" placeholder="Contact details"></textarea><br>
            <textarea name="comments" style="resize: none; id="" cols="30" rows="4" placeholder="Comments"></textarea><br>
            <a href="index.php">Go to Index</a>
            <button name="checkout">Checkout</button>
        </form>
    </div>
</body>
</html>