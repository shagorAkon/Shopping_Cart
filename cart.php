<?php 

session_start();
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
			<div class="col-lg-10">
				<div style="display: <?php if(isset($_SESSION['showAlert'])) {

						echo $_SESSION['showAlert'];

				} 

				else
				{
					echo 'none';
				}

				unset($_SESSION['showAlert']);
			?>" class="alert alert-success alert-dismissible mt-3">
					<button type="button" class="close" data-dismiss="alert">&times;</button>

					<b><?php if(isset($_SESSION['message']))
					{

						echo $_SESSION['message'];
					} 

					unset($_SESSION['showAlert']);
				?>
					
				</b>
				</div>

					<div class="table table-responsive mt-2">
						<table class="table table-bordered table-striped text-center">
							<thead>
								<tr>
									<td colspan="7">
										<h4 class="text-center text-info m-0">Products in your cart</h4>
									</td>
								</tr>

								<tr>
									<th>ID</th>
									<th>Image</th>
									<th>Product</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Total Price</th>

									<th>
										<a href="action.php?clear=all" class="badge-danger badge p-1" onclick="return confirm('Are you sure to clear the cart?');"><i class="fas fa-trash"></i>&nbsp;&nbsp;Clear Cart</a>
									</th>
								</tr>
							</thead>

							<tbody>
								<?php 

									require_once 'config.php';

									$stmt = $conn->prepare('SELECT * FROM cart');

									$stmt->execute();
									$result = $stmt->get_result();
									$grand_total = 0;

									while($row = $result->fetch_assoc()):

								?>

								<tr>
									<td>
										<?= $row['id'];?>
									</td>
									<input type="hidden" name="" class="pid" value="<?= $row['id']?>">
									<td>
										<img src="<?= $row['product_image'];?>" width="50">
									</td>

									<td><?= $row['product_name'];?></td>
									<td>
										<i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($row['product_price'],2);?>										
									</td>
									<input type="hidden" name="" class="pprice" value="<?= $row['product_price'];?>">

									<td>
										<input type="number" name="" class="form-control itemQty" value="<?= $row['qty'];?>" style="width:75px;">
									</td>

									<td>
										<i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($row['total_price'],2);?>		
									</td>
								</tr>

								<?php  $grand_total+= $row['total_price']; ?>

							<?php endwhile; ?>

							<tr>
								<td colspan="3">
									<a href="index.php" class="btn btn-success"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Continue Shopping</a>
								</td>

								<td colspan="2"><b>Grand Total:</b></td>
								<td><b><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($grand_total,2);?></b></td>
								<td>
									<a href="checkout.php" class="btn btn-info <?= ($grand_total > 1)? '': 'disable';?>"><i class="fas fa-credit-card"></i>&nbsp;&nbsp;Checkout</a>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){

			$(".itemQty").on('change', function(){

				var $el = $(this).closest('tr');

				var pid = $el.find(".pid").val();

				var pprice = $el.find(".pprice").val();

				var qty = $el.find(".itemQty").val();

				location.reload(true);

				$.ajax({

					url: 'action.php',
					method: 'POST',
					cache: false,
					data: {

							qty: qty,
							pid:pid,
							pprice:pprice
					},

					success: function(response)
					{
						console.log(response);
					}
				});
			});

			load_cart_item_number();

			function load_cart_item_number()
			{
				$.ajax({

					url: 'action.php',
					method: 'get',
					data: {

						cartItem: "cart-item"
					},

					success: function(response)
					{
						$("#cart_item").html(response);
					}
				});
			}
		});
	</script>