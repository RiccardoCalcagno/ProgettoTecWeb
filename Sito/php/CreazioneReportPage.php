<?php
	//require
	require_once ("DBinterface.php");

	use DB\DBinterface;

	$html = file_get_contents("../otherHTMLs/creazioneReport.html");

	if(isset($_POST['submit'])){
		$titolo = $_POST['titolo'];
		$sottotitolo = $_POST['sottotitolo'];
		$contenuto = $_POST['contenuto'];
		$condividi = $_POST['condividi'];
		
	}
?>