<?php 
    session_start();
    require_once("common.php");
   
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
    
    if(isset($_GET['id'])){
        $sqlDelete="DELETE FROM products WHERE id=?";
        $result=$connection->prepare($sqlDelete);
        if($result->execute([$_GET['id']])){
            echo 'Product ' . $_GET['id']. ' was deleted successfully.';
        }
    }
    $sql="SELECT * FROM products;";
    $result=$connection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products page!</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="main-section">
        <?php while(($row=$result->fetch(PDO::FETCH_ASSOC))!==false): ?>
            <div class="full-section-products" >
                <div class="info-section">
                    <img src="<?php echo $row['image'] ?>" alt="poza">
                </div>
                <div class="info-section">
                    <ul>
                        <li><?php echo $row['title'] ?></li>
                        <li><?php echo $row['description'] ?></li>
                        <li><?php echo $row['price'] ?>$</li>
                    </ul>
                </div>
                <div class="info-section">
                    <a href="products.php?id=">Edit</a>
                </div>
                <div class="info-section">
                    <a href="products.php?id=<?php echo $row['id']; ?>">Delete</a>
                </div>
            </div> 
        <?php endwhile; ?>
        <div class="cart-section-products">
            <a href="products.php">Add</a>
        </div>
        <div class="cart-section-products">
            <a href="products.php?logout=Logout">Logout</a>
        </div>
    </div>
</body>
</html>