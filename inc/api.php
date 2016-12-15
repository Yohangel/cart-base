<?php
include("products.php"); 
$product = new products;
if (isset($_GET['method']))
	{
		$method = $_GET['method']; 
	}
if (isset($_GET['id'])) 
	{
		$id = $_GET['id'];
	}
if (isset($_GET['number'])) 
	{
		$number = $_GET['number'];
	}
if (!empty($method))
{

	if ($method=='add')
	{
		$product->addProduct($id,$number);
	}

	elseif ($method=='remove')
	{
		$product->removeProduct($id,$number);
	}

	elseif ($method=='addStar')
	{
		$product->addStar($id,$number);
	}

	elseif ($method=='selectTransport')
	{
		$product->selectTransport($id);
	}

	elseif ($method=='deselectTransport')
	{
		$product->deselectTransport($id);
	}

	elseif ($method=='checkout')
	{
		$product->checkout();
	}
}
?>