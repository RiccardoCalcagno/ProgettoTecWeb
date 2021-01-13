<?php
	 require_once("DBinterface.php");

	 $dbInterface = new DBinterface();
	 $connection = $dbInterface->openConnection();
	 echo $dbInterface->getUserId($_SESSION['username']);
	 $dbInterface->closeConnection();

?>