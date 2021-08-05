<?php 
    session_start();
    require_once('common.php');
    require_once('config.php');

    //prepare string HTML with products for email
    function loadHTML($image,$title,$description,$price){
        $message="<html>
                    <head>
                        <title>Order</title>
                    </head>
                    <body>";
        for($i=0;$i<count($title);++$i){
            $message.="<br><b>Product $i :</b><br>
                            <img src='{$image[$i]}' alt='poza'><br>
                            <b>Product description:</b>
                            <ol>
                                <li>{$title[$i]}</li>
                                <li>{$description[$i]}</li>
                                <li>{$price[$i]}$</li>
                            </ol>";
        }
        $message.="</body></html>";
        return  $message;            
    }
    //when remove items
    if(isset($_GET['id']) & !empty($_GET['id'])){
        $item=$_GET['id'];
        unset($_SESSION['cart'][$item]);    
    }
    //prepare sql statement for query and fetch data for the cart
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

    $imageCart=[];
    $titleCart=[];
    $descriptionCart=[];
    $priceCart=[];
    $idCart=[];

    while(($row=$result->fetch(PDO::FETCH_ASSOC))!==false){
        array_push($imageCart,$row['image']);
        array_push($titleCart,$row['title']);
        array_push($descriptionCart,$row['description']);
        array_push($priceCart,$row['price']);
        array_push($idCart,$row['id']);
    }
    if(isset($_POST['checkout'])){

        $nameClient=$_POST['name'];
        $nameClient=strip_tags($nameClient);
        $addressClient=$_POST['contacts'];
        $addressClient=strip_tags($addressClient);
        $commentClient=$_POST['comments'];
        $commentClient=strip_tags($commentClient);

        if(!empty($nameClient)&&!empty($addressClient)){

            $header="From: <demo@stefan.me>\r\n";
            $header.="MIME-VERSION: 1.0\r\n";
            $header.="Content-Type: text/html; charset=ISO-8859-1\r\n";

            $subject="Order for ".$nameClient;

            $message=" The order will be delivered to \t";
            $message.= $addressClient." and the command is :";
            $message.=loadHTML($imageCart,$titleCart,$descriptionCart,$priceCart);

            if(!empty($commentClient)){
                $message.=" <b>Comments:</b>\n".$commentClient;
            }
            if(!mail(managerEmail,$subject,$message,$header)){
                echo "Error!";
            }
        }else{
            echo "<script>alert('You must enter your name and contacts!')</script>";
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
        <?php for($i=0;$i<count($titleCart);++$i): ?>
            <div class="full-section">
                <div class="info-section">
                    <img src="<?php echo $imageCart[$i]; ?>" alt="poza">
                </div>
                <div class="info-section">
                    <ul>
                        <li><?php echo $titleCart[$i]; ?></li>
                        <li><?php echo $descriptionCart[$i]; ?></li>
                        <li><?php echo $priceCart[$i]; ?></li>
                    </ul>
                </div>
                <div class="info-section">
                    <a href="cart.php?id=<?php echo $idCart[$i]; ?>">Remove</a>
                </div>
            </div>
        <?php endfor; ?>
        <form action="cart.php" method="post">
            <input type="text" name="name" placeholder="Name" value="<?php echo isset($_POST["name"]) ? $_POST["name"] : ''; ?>"><br>
            <textarea name="contacts" style="resize: none; id="" cols="30" rows="2" placeholder="Contact details" ><?php echo isset($_POST["contacts"]) ? $_POST["contacts"] : ''; ?></textarea><br>
            <textarea name="comments" style="resize: none; id="" cols="30" rows="4" placeholder="Comments"><?php echo isset($_POST["comments"]) ? $_POST["comments"] : ''; ?></textarea><br>
            <a href="index.php">Go to Index</a>
            <button name="checkout">Checkout</button>
        </form>
    </div>
</body>
</html>