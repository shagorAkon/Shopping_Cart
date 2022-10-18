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
	<div id="message"></div>

	<div class="row mt-2 pb-3">
		<?php 

			include "config.php";

			$stmt = $conn->prepare("SELECT * FROM product");
			$stmt->execute();
			$result= $stmt->get_result();

			while($row = $result->fetch_assoc()):
			

		?>

		<div class="col-sm-6 col-md-4 col-lg-3 mb-2">
			<div class="card-deck">
				<div class="card p-2 border-secondary mb-2">
					<img src="<?= $row['product_image']; ?>" class="card-img-top" height="180">

					<div class="card-body p-1">
						<h4 class="card-title text-center text-info"><?= $row['product_name'];?></h4>

						<h5 class="card-text text-center text-danger"><i class="fas fa-dollar-sign"></i>
								&nbsp;&nbsp;&nbsp;&nbsp;<?= number_format($row['product_price'],2); ?>/-
						</h5>
					</div>

					<div class="card-footer p-1">
						<form action="" class="form-submit">
							<div class="row p-2">
								<div class="col-md-6 py-1 pl-4">
									<b>Quantity:</b>
								</div>
								<div class="col-md-6">
									<input type="number" name="" class="form-control pqty" value="<?= $row['product_qty'];?>">
								</div>
							</div>

							<input type="hidden" name="" class="pid" value="<?= $row['id'];?>">

							<input type="hidden" name="" class="pname" value="<?= $row['product_name'];?>">

							<input type="hidden" name="" class="pprice" value="<?= $row['product_price'];?>">

							<input type="hidden" name="" class="pimage" value="<?= $row['product_image'];?>">

							<input type="hidden" name="" class="pcode" value="<?= $row['product_code'];?>">


							<button class="btn btn-info btn-block addItemBtn"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;&nbsp;Add to Cart								
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php endwhile; ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$(".addItemBtn").click(function(e){

			e.preventDefault();

			var $form=$(this).closest(".form-submit");

			var pid = $form.find(".pid").val();

			var pname = $form.find(".pname").val();

			var pprice = $form.find(".pprice").val();

			var pimage = $form.find(".pimage").val();

			var pcode = $form.find(".pcode").val();

			var pqty = $form.find(".pqty").val();

			$.ajax({

				url : "action.php",
				method: "POST",
				data: {

						pid: pid,
						pname: pname,
						pprice: pprice,
						pqty: pqty,
						pimage: pimage,
						pcode: pcode
				},
				success: function(response)
				{
					$("#message").html(response);
					window.scrollTo(0,0);
					load_cart_item_number();
				}
			});
		});

		load_cart_item_number();

		function load_cart_item_number()
		{
			$.ajax({

				url: "action.php",
				method: "get",
				data: {

						cartItem: "cart_item"
				},

				success: function(response)
				{
					$("#cart_item").html(response);
				}
			});
		}

	});
</script>
</body>
</html>