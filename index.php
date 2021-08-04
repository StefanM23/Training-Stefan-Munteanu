<?php
    session_start();
    require_once('common.php');
   
    if(isset($_GET['id']) & !empty($_GET['id'])){
        $item=$_GET['id'];
        $_SESSION['cart'][$item]=$item;
    }

    if(!empty($_SESSION['cart'])){
        $sql="SELECT * FROM products WHERE id NOT IN (";
        foreach($_SESSION['cart'] as $element){
            $sql.=$_SESSION['cart'][$element].",";
        }
        $sql=substr($sql ,0,-1);
        $sql.=");";  
    }else{
        $sql="SELECT * FROM products;";
    }
    $result=$connection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-section">
        <?php while(($row=$result->fetch(PDO::FETCH_ASSOC))!==false): ?>
            <div class="full-section" id="<?php echo $row['id']; ?>">
                <div class="info-section">
                     <img src="<?php echo $row['image']; ?>" alt="poza">
                 </div>
                <div class="info-section">
                    <ul>
                        <li id="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></li>
                         <li id="<?php echo $row['id']; ?>"><?php echo $row['description']; ?></li>
                         <li id="<?php echo $row['id']; ?>"><?php echo $row['price']; ?></li>
                    </ul>
                </div>
                <div class="info-section">
                     <a href="index.php?id=<?php echo $row['id']; ?>">Add</a>
                </div>
            </div>
        <?php endwhile; ?>
        <div class="cart-section">
            <a href="cart.php">Go to cart</a>
        </div>
    </div>
</body>
</html>