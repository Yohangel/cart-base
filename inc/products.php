<?php
include("config.php"); 
class products extends connection
{
	public function productsList()
	{
		try
		{
			$db = $this->start(); 
			$products = $db->query("SELECT * FROM product");
			foreach ($products as $row) 
			{
			?>
			<div class="product" id="<?php echo $row['id']; ?>">
				<div class="product__image">
					<img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
				</div>
				<div class="product__stars">
				<?php 
				for ($i = 1; $i <= $this->promStars($row['id']); $i++)
				{
				?>
				<div class="product__stars-star" onclick="api('addStar',<?php echo $row['id']; ?>,<?php echo $i; ?>);"><img src="star2.svg" alt="star"></div>
				<?php
				}
				for ($i = 1; $i <= 5-$this->promStars($row['id']); $i++)
				{
				?>
					<div class="product__stars-star" onclick="api('addStar',<?php echo $row['id']; ?>,<?php echo $this->promStars($row['id'])+$i; ?>);"><img src="star.svg" alt="star"></div>
				<?php
				}
				?>
				</div>
				<div class="product__title"><?php echo $row['name']; ?></div>
				<div class="product__price"><?php echo $row['price']; ?>$</div>
				<div class="product__quantity"><input id="quantity<?php echo $row['id']; ?>" type="text" name="quantity" placeholder="Quantity" value="1"><br></div>
				<div class="product__button" onclick="api('add',<?php echo $row['id']; ?>,document.getElementById('quantity<?php echo $row['id']; ?>').value);">Buy</div>
			</div>
			<?php
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function cartNumber()
	{
		try
		{
			$db = $this->start(); 
			$products = $db->query("SELECT * FROM product WHERE incart > 0");
			return $products->rowCount();
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function myMoney()
	{
		try
		{
			$db = $this->start(); 
			$stmt = $db->query("SELECT * FROM me WHERE id = 1");
			$money = $stmt->fetch();
			return $money['cash'];
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function myBalance()
	{
		try
		{
			$db = $this->start(); 
			$stmt = $db->query("SELECT * FROM me WHERE id = 1");
			$money = $stmt->fetch();
			echo "Hello, you currently have ". $money['cash'] ."$, your last balance was ". $money['old_cash'] ."$, your last purchase was ". $money['payed'] ."$";
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}


	public function productsCartList()
	{
		try
		{
			$db = $this->start(); 
			$products = $db->query("SELECT * FROM product WHERE incart > 0");
			foreach ($products as $row) 
			{
			?>
			<div class="product" id="<?php echo $row['id']; ?>">
				<div class="product__image">
					<img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
				</div>
				<div class="product__stars">
				<?php 
				for ($i = 1; $i <= $this->promStars($row['id']); $i++)
				{
				?>
				<div class="product__stars-star" onclick="api('addStar',<?php echo $row['id']; ?>,<?php echo $i; ?>);"><img src="star2.svg" alt="star"></div>
				<?php
				}
				for ($i = 1; $i <= 5-$this->promStars($row['id']); $i++)
				{
				?>
					<div class="product__stars-star" onclick="api('addStar',<?php echo $row['id']; ?>,<?php echo $this->promStars($row['id'])+$i; ?>);"><img src="star.svg" alt="star"></div>
				<?php
				}
				?>
				</div>
				<div class="product__title"><?php echo $row['name']; ?> (<?php echo $row['incart']; ?>)</div>
				<div class="product__price"><?php echo $row['price']; ?>$</div>
				<div class="product__quantity"><input id="quantity<?php echo $row['id']; ?>" type="text" name="quantity" placeholder="Quantity" value="1"><br></div>
				<div class="product__button" onclick="api('remove',<?php echo $row['id']; ?>,document.getElementById('quantity<?php echo $row['id']; ?>').value);">Remove</div>
			</div>
			<?php
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
		if ($products->rowCount())
		{
		?>
			<div class="container">Please choose a type of transportation to complete the purchase</div>
			<?php
			try
			{
				$db = $this->start(); 
				$transports = $db->query("SELECT * FROM transport");
				foreach ($transports as $row) 
				{
				?>
					<?php 
					if($row['selected'] == 1) 
					{	
					?>
						<div class="product">
							<div class="product__title"><?php echo $row['name']; ?></div>
							<div class="product__price"><?php echo $row['price']; ?>$</div>
							<div class="product__button" onclick="api('deselectTransport', <?php echo $row['id']; ?>);">Selected</div>
						</div>
					<?php 
					}
					else
					{
					?>
						<div class="product">
							<div class="product__title"><?php echo $row['name']; ?></div>
							<div class="product__price"><?php echo $row['price']; ?>$</div>
							<div class="product__button" onclick="api('selectTransport', <?php echo $row['id']; ?>);">Select</div>
						</div>
		<?php
					}
				}
			}
			catch(PDOException $e) 
			{
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		?>
		<div class="container">
					<div class="product__price">Total: <?php echo $this->totalPrice(); ?>$</div>
					<div class="product__button" onclick="api('checkout');">checkout</div>
		</div>
		<?php
		}
		else
		{
		?>
			<div class="container">
					<div class="product__price">Haven't items in your cart</div>
		</div>
		<?php
		}
	}

	public function addProduct($id,$number)
	{
		try
		{
			$db = $this->start(); 
			$stmt = $db->prepare("UPDATE product SET incart=incart+".$number." WHERE id='".$id."'");  
			if ($stmt->execute()){
				echo "added";
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function removeProduct($id,$number)
	{

		try
		{
			$db = $this->start(); 
			$stmt = $db->query("SELECT * FROM product WHERE id='".$id."'");
			$product = $stmt->fetch();
			if ($product['incart'] < $number)
			{
				$number = $product['incart'];
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

		try
		{
			$db = $this->start(); 
			$stmt = $db->prepare("UPDATE product SET incart=incart-".$number." WHERE id='".$id."'");  
			if ($stmt->execute()){
				echo "removed";
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function promStars($id)
	{
		try
		{
			$db = $this->start(); 
			$star = $db->query("SELECT * FROM stars WHERE product_id = '".$id."'");
			$stars = $star->fetch();
			if ($star->rowCount()>0) 
			{
				$sum = ($stars['one']*1) + ($stars['two']*2) + ($stars['three']*3) + ($stars['four']*4) + ($stars['five']*5);
				$sum2 = $stars['one'] + $stars['two'] + $stars['three'] + $stars['four'] + $stars['five'];
				if($sum2 > 0)
				{
					$prom = round($sum/$sum2);
				} 
				else
				{
					$prom = 0;
				}
			}
			else
			{
				$prom = 0;
			}
			
			return $prom;
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function addStar($id,$number)
	{
		if ($number == 1)
		{
			$number = 'one';
		} 
		elseif ($number == 2)
		{
			$number = 'two';
		}
		elseif ($number == 3)
		{
			$number = 'three';
		}
		elseif ($number == 4)
		{
			$number = 'four';
		}
		elseif ($number == 5)
		{
			$number = 'five';
		}
		try
		{
			$db = $this->start(); 
			$stmt = $db->prepare("UPDATE stars SET ".$number."=".$number."+1 WHERE product_id='".$id."'");  
			if ($stmt->execute()){
				echo "star.added";
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function selectTransport($id)
	{
		try
		{
			$db = $this->start(); 
			$stmt = $db->prepare("UPDATE transport SET selected=0");  
			if ($stmt->execute()){
				try
				{
					$db = $this->start(); 
					$stmt = $db->prepare("UPDATE transport SET selected=1 WHERE id='".$id."'");  
					if ($stmt->execute()){
						echo "selected.transport";
					}
				}
				catch(PDOException $e) 
				{
					echo '{"error":{"text":'. $e->getMessage() .'}}';
				}
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function deselectTransport($id)
	{
		try
		{
			$db = $this->start(); 
			$stmt = $db->prepare("UPDATE transport SET selected=0");  
			if ($stmt->execute()){
				echo "deselected.transport";
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function transportSelected($id)
	{
		try
		{
			$db = $this->start(); 
			$stmt = $db->query("SELECT * FROM transport WHERE id = '".$id."'");
			$selected = $stmt->fetch();
			return $selected['selected'];
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function totalPrice()
	{
		$total = 0;
		try
		{
			$db = $this->start(); 
			$products = $db->query("SELECT * FROM product WHERE incart > 0");
			foreach ($products as $row) 
			{
				$total = $total + ($row['price']*$row['incart']);
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

		try
		{
			$db = $this->start(); 
			$transport = $db->query("SELECT * FROM transport WHERE selected = 1");
			foreach ($transport as $row) 
			{
				$total = $total + $row['price'];
			}
		}
		catch(PDOException $e) 
		{
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

		return $total;
	}

	public function checkout()
	{
		$total = $this->totalPrice();
		$myMoney = $this->myMoney();
		$rest = $myMoney - $total;
		if($myMoney>=$total)
		{
			try
			{
				$db = $this->start(); 
				$stmt = $db->query("SELECT * FROM transport WHERE selected = 1");
				if($stmt->rowCount()>0)
				{
					try
						{
							$db = $this->start(); 
							$stmt = $db->prepare("UPDATE me SET old_cash=".$myMoney."");  
							if ($stmt->execute()){
								try
								{
									$db = $this->start(); 
									$stmt = $db->prepare("UPDATE me SET payed=".$total."");  
									if ($stmt->execute()){
										try
										{
											$db = $this->start(); 
											$stmt = $db->prepare("UPDATE me SET cash=".$rest."");  
											if ($stmt->execute()){
												try
												{
													$db = $this->start(); 
													$stmt = $db->prepare("UPDATE product SET incart=0");  
													if ($stmt->execute()){
														try
														{
															$db = $this->start(); 
															$stmt = $db->prepare("UPDATE transport SET selected=0");  
															if ($stmt->execute()){
																echo "checkout.success";
															}
														}
														catch(PDOException $e) 
														{
															echo '{"error":{"text":'. $e->getMessage() .'}}';
														}
													}
												}
												catch(PDOException $e) 
												{
													echo '{"error":{"text":'. $e->getMessage() .'}}';
												}
											}
										}
										catch(PDOException $e) 
										{
											echo '{"error":{"text":'. $e->getMessage() .'}}';
										}
									}
								}
								catch(PDOException $e) 
								{
									echo '{"error":{"text":'. $e->getMessage() .'}}';
								}
							}
						}
						catch(PDOException $e) 
						{
							echo '{"error":{"text":'. $e->getMessage() .'}}';
						}
				}
				else
				{
					echo "checkout.error.transport";
				}
			}
			catch(PDOException $e) 
			{
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}
		else
		{
			echo "checkout.error";
		}
	}
}
?>