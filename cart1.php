<?php
session_start();
$database_name ="product_details";
$con=mysqli_connect('localhost','root','',$database_name);
?>
<?php
if(isset($_POST['add'])){
    if(isset($_SESSION['cart'])){
        $item_array_id = array_column($_SESSION['cart'],"product_id");
        if(!in_array($_GET['id'], $item_array_id)){
            $count = count($_SESSION['cart']);
            $item_array = array(
                'product_id' => $_GET['id'],
                'item_name' => $_POST['hidden_name'],
                'product_price' => $_POST['hidden_price'],
                'item_quantity' => $_POST['quantity'],

            );
            $_SESSION['cart'][$count] = $item_array;
            echo '<script>window.location="cart1.php"</script>';
        }else{
            echo '<script>alert("Product is already Add To Cart")</script>';
            echo '<script>window.location="cart1.php"</script>';
        }
        }else{
            $item_array = array(
                'product_id' => $_GET['id'],
                'item_name' => $_POST['hidden_name'],
                'product_price' => $_POST['hidden_price'],
                'item_quantity' => $_POST['quantity'],
            );
            $_SESSION['cart'][0] = $item_array;
        }
    }
if(isset($_GET['action'])){
    if($_GET['action'] == "delete"){
        foreach($_SESSION['cart'] as $keys => $value){
            if($value['product_id'] == $_GET['id']){
                unset($_SESSION['cart'][$keys]);
                echo '<script>alert("Product has Been  Removed")</script>';
                echo '<script>window="cart1.php"</script>';
            }
        }
    }
}


?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            shopping cart
</title>
<meta charset= "UTF-8">
<meta name="viewport"
content="width=device-width, user-scalable, intial-scale=1.0,
maximun-scale=1.0, minimun-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<style>
@import url('https://fonts.googleapis.com/css?family=Titillium+Web&display=swap');
*{
    font-family: 'Titillium Web', ;
}
.product{
    border: 1px solid #eaeaec ;
    margin: -1px 10px 3px -1px;
    padding: 10px;
    text-align: center;
    background-color: #efefef;
}
table, th, tr{
    text-align: center;
}
.title2{
    text-align: center;
    color: #66afe9;
    background-color: #efefef;
    padding: 2%;
}
h2{
    text-align: center;
    color: #66afe9;
    background-color:#efefef;
    padding: 2%;

}
table th{
    background-color: #efefef;
}
</style>
</head>
<body>
    <div class="container" style="width: 65%;">
    <div class="row">
    <?php
    $sql="SELECT* FROM product ORDER BY id ASC";
    $result = mysqli_query($con,$sql);
    if(mysqli_num_rows($result) > 0){
     while ($row = mysqli_fetch_array($result)){

    ?>
    <div class="col-md-3">
<form method="post" action="cart1.php?action=add&id=<?php $row['id'] ?>">
<div calss="product">
    <img src="<?php echo $row['image']; ?>" class="img-responsive">
    <h5 class="text-info" ><?php echo $row['name']; ?> </h5>
     <h5 class="text-danger" > <?php echo $row['price']; ?> </h5> 
     <input type="text" name="quantity" class="form-control" value="1">
     <input type="hidden" name="hidden_name" value="<?php echo $row['name']; ?>">
     <input type="hidden" name="hidden_price" value="<?php echo $row['price'];?>">
      <input  type="submit" name="add" style=" margin-top:5px;" class="btn btn-success" value="Add to Cart">

</div>    
</form>
     </div>
<?php
     }
    }
    ?>
<div style="clear: both"></div>
<h3 class="title"> shopping cart details </h5>
<div class="table-responsive">
<table class="table table-bordered">
<tr>
<th width="30%"> Product name </th>
<th width="10%"> Quantity </th>
<th width="13%"> Price Details </th>
<th width="10%"> Total Price </th>
<th width="17%"> Remove Item </th>
</tr>
<?php
if(!empty($_SESSION['cart'])){
    $total = 0;
    foreach ($_SESSION['cart'] as $key => $value){
        ?>
    <tr>
    <td> <?php echo $value['item_name']; ?> </td>
    <td> <?php echo $value['item_quantity']; ?> </td>
    <td>$ <?php echo $value['product_price']; ?> </td>
    <td> $ <?php echo number_format( $value['item_quantity'] * $value['product_price'], "2"); ?> </td>
    <td><a href="cart1.php?action=delete&id=<?php echo $value['product_id'];?>"><span class="text-danger">Remove Item</sapn><a/></td>

    <tr>
    <?php
    $total = $total + ($value['item_quantity'] * $value['product_price']);
    }
    ?>
    <tr>
    <td colspan="3" align="right"> Total</td>
    <th align="right"> $ <?php echo number_format($total,"2"); ?> </th>
    <td></td>
    <tr>
    <?php
    }
    ?>
    </table>
</div>
</div>

</body>
</html>