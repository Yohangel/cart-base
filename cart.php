<?php 
include("inc/products.php");
$products = new products;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Shop test: cart</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<header>
		<div class="logo">MyShop: Cart list</div>
		<div class="cart" onclick="window.location.href = 'index.php'">Back to the shop</div>
	</header>
	<div class="container">
		<div class="container">
			<div class="product__title"><?php $products->myBalance(); ?></div>
		</div>
	<?php 
		$products->productsCartList();
	?>
	</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integr crossorigin="anonymous"></script>
<script src="functions.js"></script>
</body>