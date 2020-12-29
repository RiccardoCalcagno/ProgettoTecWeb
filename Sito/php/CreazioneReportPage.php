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

		//controlli
		if(strlen($titolo) != 0 && strlen($sottotitolo) != 0 && strlen($contenuto) != 0) {

			$dbInterface = new DBinterface();
			$connection = $dbInterface->openConnection();

			if($connection){
				//creo l'oggetto report
				$rep = new ReportData(getLatestRep()+1,$title,$subtitle,$content,/*author*/,$condividi,date("Y-m-d"));
				//aggiungo il report nel database
				$insertionResult = $dbInterface->addReport($rep);

				if ($insertionResult){
					//costruisco un messaggio da mostrare vicino alla form
					$message = '';

					//azzero la form
					$titolo = '';
					$sottotitolo = '';
					$contenuto = '';
					$condividi = '';
				}
				else{
					//messaggi di errore lato server, non errori di compilazione
					$message = '';
				}
			}
		}
		//altrimenti ci sono stati errori di inserimento
		else{

		}
		
	}

	//il contenuto delle textarea viene settato qui
	$html = str_replace('<valueTitle/>',$title,$html);
	$html = str_replace('<valueSubtitle/>',$subtitle,$html);
	$html = str_replace('<valueContent/>',$content,$html);

	echo $html;

?>