<?php 

session_start();
error_reporting(0);

require_once 'config.php';

if(isset($_POST['pid']))
{
	$pid = $_POST['pid'];
	$pname = $_POST['pname'];
	$pprice = $_POST['pprice'];
	$pimage = $_POST['pimage'];
	$pcode = $_POST['pcode'];
	$pqty = $_POST['pqty'];

	$total_price = $pprice * $pqty;

	$stmt = $conn->query("SELECT * FROM cart WHERE product_code = $pcode");
	$r = $stmt->fetch_assoc();
	$result = $r['product_code'];
	// $stmt->execute();
	//$res = $stmt->get_result();
	// $r = $stmt->fetch_assoc();
	// $code =$r['product_code'];
	$output = $result;
	echo json_encode($output['cartRes']);

	if(!$result)
	{
		$query = $conn->prepare('INSERT INTO cart(product_name,product_price,product_image,qty,total_price,product_code) VALUES(?,?,?,?,?,?)');
		$query->bind_param('ssssss',$pname,$pprice,$pimage,$pqty,$total_price,$pcode);
		$query->execute();

		echo '<div class"alert alert-success alert-dismissible mt-2">
				<button type="button" class="close" data-dismiss="alert">&times;</button><b>Item added to the cart</b></div>';
	}

	else
	{
		echo '<div class"alert alert-success alert-dismissible mt-2">
				<button type="button" class="close" data-dismiss="alert">&times;</button><b>Item already added to the cart</b></div>';
	}

}

if(isset($_GET['cartItem']) && isset($_GET['cartItem'])=='cart_item')
{
	$stmt = $conn->prepare('SELECT * FROM cart');
	$stmt->execute();
	$stmt->store_result();

	$rows = $stmt->num_rows;

	echo $rows;
}

if(isset($_GET['clear']))
{
	$stmt = $conn->prepare('DELETE FROM cart');

	$stmt->execute();

	$_SESSION['showAlert']='block';
	$_SESSION['message']='All Items Removed from the Cart';
	header('location: cart.php');
}

if(isset($_POST['qty']))
{
	$qty = $_POST['qty'];
	$pid = $_POST['pid'];
	$pprice = $_POST['pprice'];

	$tprice = $qty * $pprice;

	$stmt = $conn->prepare('UPDATE cart SET qty=?, total_price=? WHERE id=?');

	$stmt->bind_param('isi',$qty,$tprice,$pid);
	$stmt->execute();
}

if(isset($_POST['action']) && isset($_POST['action']) == 'order')
{
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$product = $_POST['products'];
	$grand_total = $_POST['grand_total'];
	$adress = $_POST['address'];
	$pmode = $_POST['pmode'];

	$data = '';

	$stmt = $conn->prepare('INSERT INTO orders(name, email, phone, address, products, amount_paid) VALUES(?,?,?,?,?,?)');

	$stmt->bind_param('ssssss',$name,$email,$phone,$address,$pmode,$product,$grand_total);
	$stmt->execute();

	$stmt2 = $conn->prepare('DELETE FROM cart');
	$stmt2->execute();

	$data.= '

			<div class="text-center">
				<h1 class="display-4 mt-2 text-danger">Thank You!</h1>
				<h2 class="text-success">Your Order has been Placed Successfuly!</h2>
	<h4 class="bg-danger text-light rounded p-2">Items Purchased:'.$product.'</h4>

				<h4>Your name:'.$name.'</h4>
				<h4>Your email:'.$email.'</h4>
				<h4>Your Phone:'.$phone.'</h4>
				<h4>Total Amount paid:'.number_format($grand_total,2).'</h4>
				<h4>Payment Mode:'.$pmode.'</h4>
			</div>';

	echo $data;

}


?>