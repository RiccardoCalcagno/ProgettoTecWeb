<?php
	require_once("DBinterface.php");
	require_once("report_data.php");

	 $dbInterface = new DBinterface();
	 $connection = $dbInterface->openConnection();

	 $lista = array();
	 array_push($lista,'Grog');
	 $rep = new ReportData(null, 'aaa', 'bbb', 'ccc', 'ShinigamiVII', false, $lista);

	 echo var_dump($dbInterface->addReport($rep));

	 $dbInterface->closeConnection();



?>