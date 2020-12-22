<?php
	require_once ("DBinterface.php");
	use DB\DBinterface;

	$html = file_get_contents('..'.DIRECTORY_SEPARATOR.'otherHTMLs'.DIRECTORY_SEPARATOR.'character creation.html'); //forse togliere spazio nel nome

	if (isset($_POST['submit'])) {

		$name = $_POST['cname'];	//estraggo dal post della form le informazioni contenute
		$race = $_POST['crace'];
		$class = $_POST['cclass'];
		$background = $_POST['cbackground'];
		$alignment = $_POST['calignment'];
		$traits = $_POST['ctraits'];
		$ideals = $_POST['cideals'];
		$bonds = $_POST['cbonds'];
		$flaws = $_POST['cflaws'];

		//Fare i controlli sugli input
		//Uso variabili booleane, true se la variabile che controlla passa il check, false altrimenti
		$check_name = preg_match("/\\S+/",$name);
		//$check_race = ;			//provengono da select, non possono essere sbagliati, no?
		//$check_class = ;
		//$check_background = ;
		//$check_alignment = ;
		$check_traits = strlen($traits)>10;
		$check_ideals = strlen($ideals)>10;
		$check_bonds = strlen($bonds)>10;
		$check_flaws = strlen($flaws)>10;

		if($check_name && $check_traits && $check_ideals && $check_bonds && $check_flaws){
			//se passo i controlli allora passo gli input alla costruzione di dati per il DB.
			$db = new DBinterface();
			$openConnection = $db->openConnection();

			if ($openDBConnection == true) {

				$character_data = new Character 

				$result = $db->addCharacter($character_data);

			}

		}
		else{
			//se non passo i controlli allora restituisco messaggi adeguati per informare l'utente degli errori di input.

		}

	}

?>