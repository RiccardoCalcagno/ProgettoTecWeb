<?php
	require_once("DBinterface.php");
	require_once("report_data.php");

	 $dbInterface = new DBinterface();
	 $connection = $dbInterface->openConnection();

	 
	 echo var_dump($dbInterface->getHighestRepId());

	 $dbInterface->closeConnection();



?>