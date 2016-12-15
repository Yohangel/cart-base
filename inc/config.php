<?php 
class connection 
{
	private $host = "mysql.cba.pl"; 
	private $user = "thedbtest"; 
	private $password = "test123!"; 
	private $db = "yohangel"; 
	public function start()
	{ 
	    try 
	    {
			$dbConnection = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password);
			$dbConnection->exec("set names utf8");
			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $dbConnection;
		}
		
		catch (PDOException $e) 
		{
			echo 'Connection failed: ' . $e->getMessage();
		}
	}
}
?>