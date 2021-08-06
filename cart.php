<?php 

require_once "common.php";
require_once "config.php";

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
if(isset($_POST['id'])){
    $item=$_POST['id'];
    unset($_SESSION['cart'][$item]);    
}
//prepare sql statement for query and fetch data for the cart
if(!empty($_SESSION['cart'])){
    $sql="SELECT * FROM products WHERE id IN (";

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
    $sql="SELECT * FROM products WHERE id=-1;";
    $result=$connection->query($sql);
}


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
        mail(MANAGER_EMAIL,$subject,$message,$header);
            
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
    <title><?=translateLabels("Cart Page");?></title>
    <link rel="stylesheet" href="/ProjectMS/index.css">
</head>
<body>
    <div class="main-section">
        <form action="cart.php" method="post">
            <?php for($i=0;$i<count($titleCart);++$i): ?>
                <div class="full-section">
                    <div class="info-section">
                        <img src="<?=$imageCart[$i]; ?>" alt="<?=translateLabels("image");?>">
                    </div>
                    <div class="info-section">
                        <ul>
                            <li><?=$titleCart[$i]; ?></li>
                            <li><?=$descriptionCart[$i]; ?></li>
                            <li><?=$priceCart[$i]; ?></li>
                        </ul>
                    </div>
                    <div class="info-section">
                        <button name="id" value="<?=$idCart[$i]; ?>"><?=translateLabels("Remove");?></button>
                    </div>
                </div>
            <?php endfor; ?>
        </form>
        <form action="cart.php" method="post">
            <input type="text" name="name" placeholder="<?=translateLabels("Name");?>" value="<?php echo isset($_POST["name"]) ? $_POST["name"] : ''; ?>"><br>
            <textarea name="contacts" style="resize: none; id="" cols="30" rows="2" placeholder="<?=translateLabels("Contact details");?>" ><?php echo isset($_POST["contacts"]) ? $_POST["contacts"] : ''; ?></textarea><br>
            <textarea name="comments" style="resize: none; id="" cols="30" rows="4" placeholder="<?=translateLabels("Comments");?>"><?php echo isset($_POST["comments"]) ? $_POST["comments"] : ''; ?></textarea><br>
            <a href="index.php"><?=translateLabels("Go to Index");?></a>
            <button name="checkout"><?=translateLabels("Checkout");?></button>
        </form>
    </div>
</body>
</html>