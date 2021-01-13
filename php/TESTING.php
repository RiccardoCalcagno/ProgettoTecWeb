<?php
	require_once("DBinterface.php");
	require_once("report_data.php");

	 $dbInterface = new DBinterface();
	 $connection = $dbInterface->openConnection();

	 $dbInterface->ALUsimplified('7',$dbInterface->getHighestRepId());
	 echo var_dump($dbInterface->getHighestRepId());

	 $dbInterface->closeConnection();



?>