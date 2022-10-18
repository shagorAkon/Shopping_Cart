<?php 
error_reporting(0);
require_once 'config.php';

$grand_total = 0;

$allItems = '';
$items=[];

$sql = "SELECT CONCAT(product_name,'(',qty,')') AS ItemQty, total_price FROM cart";

$stmt=$conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc())
{
	$grand_total+=$row['total_price'];
	$items[]=$row['ItemQty'];
}

$allItems = implode(',',$items);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>All Products</title>

	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
</head>
<body>

	<nav class="navbar navbar-expand-md bg-dark navbar-dark">
		<a href="index.php" class="navbar-brand">
			<i class="fa fa-mobile-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mobile Shop
		</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="collapsibleNavbar">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a href="index.php" class="nav-link active"><i class="fa fa-mobile-alt mr-2"></i>Products</a>
				</li>

				<li class="nav-item">
					<a href="#" class="nav-link active"><i class="fa fa-th-list mr-2"></i>Categories</a>
				</li>

				<li class="nav-item">
					<a href="checkout.php" class="nav-link active"><i class="fa fath-list mr-2"></i>Checkout</a>
				</li>

				<li class="nav-item">
					<a href="cart.php" class="nav-link"><i class="fa fa-shopping-cart mr-2"></i><span id="cart-item" class="badge badge-danger"></span></a>
				</li>
			</ul>
		</div>
	</nav>
	
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6 pb-4" id="order">
				<h4 class="text-center text-info p-2">Complete Your Order</h4>
			</div>
			<div class="jumbortron p-3 mb-2 text-center">
				<h6 class="lead"><b>Product(s):</b><?= $allItems; ?></h6>
				<h6 class="lead"><b>Delivery Charge:</b>Free</h6>
				<h5 class="lead"><b>Total Payable Amount:</b><?= number_format($grand_total,2);?>/-</h5>
			</div>

			<form action="" method="post" id="placeOrder">
				<input type="hidden" name="products" value="<?= $allItems; ?>">

				<input type="hidden" name="grand_total" value="<?= $grand_total;?>">

				<div class="form-group">
					<input type="text" name="name" class="form-control" placeholder="Enter name">
				</div>

				<div class="form-group">
					<input type="email" name="email" class="form-control" placeholder="Enter Email Address">
				</div>

				<div class="form-group">
					<input type="tel" name="phone" class="form-control" placeholder="Enter Phone Number">
				</div>
				<div class="form-group">
					<textarea name="address" class="form-control" rows="3" cols="10" placeholder="Enter Delivery Address Here..."></textarea>
				</div>

				<h6 class="text-center lead">Selectr payment Option:</h6>
				<div class="form-group">
					<select name="pmode" class="form-control">
						<option value="" selected disable>Select Payment Mode</option>
						<option value="cod">Cash on Delivery</option>
						<option value="netbanking">Net Banking</option>
						<option value="cards">Debit/Credit Card</option>
					</select>
				</div>

				<div class="form-group">
					<input type="submit" name="submit" value="Place Your Order" class="btn btn-danger btn-block">
				</div>

			</form>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){

			$("#placeOrder").submit(function(e){

				e.preventDefault();

				$.ajax({

					url: 'action.php',
					method: 'post',
					data: $('form').serialize() + "&action=order",

					success: function(response)
					{

						$("#order").html(response);
					}
				});
			});

			load_cart_item_number();

			function load_cart_item_number()
			{
				$.ajax({

					url:'action.php',
					method:'get',
					data:{cartItem: "cart_item"},

					success:function(response)
					{
						console.log(response.cartRes);
						$("#cart_item").html(response);
					}
				});
			}
		});
	</script>