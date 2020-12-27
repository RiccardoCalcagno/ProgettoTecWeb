<?php

//require
require_once ("DBinterface");
require_once ("report_data");

use DB\DBinterface;

//prelevo Report.html
$html = file_get_contents('../otherHTMLs/Report.html');

$dbInterface = new DBinterface();
$connection = $dbInterface->openConnection();

if($connection == false){
	//redirecting to 404
	$dbInterface->closeConnection();
}
else{
	//di seguito tutti gli accorgimenti per stampare le parti prelevate da DB all'interno della pagina Report.html
	//Devo inserire titolo, sottotitolo, contenuto, autore, img_autore, giocatori collegati, commenti, ultima modifica.

		//prelevo il report desiderato, in base all'id contenuto in $selected_report_id
		//$report_info = get_report($selected_report_id);
	//ATTENZIONE, sopra è un alternativa, segue invece come se questa pagina ricevesse direttamente l'oggetto report, $report_info

	//prelevo l'oggetto report
	/*
	session_start();
	$report_info = $_SESSION[""];
	*/
	//ATTENZIONE! da inserire il nome della variabile che prelevo da session!

	//titolo e sottotitolo
	$replacer = '<h1>'.$report_info.get_title().'</h1>'.'<p>'.$report_info.get_subtitle().'</p>';
	str_replace("<TitleAndSub_placeholder/>", $replacer, $html);

	//autore e img
	$replacer = '<h2>Autore</h2>';
	$replacer .= '<div class="badgeUtente">';
	$replacer .= '<img src="'.$report_info.get_author_img().'" alt="Immagine profilo" />';
	$replacer .= '<p class="textVariable">'.$report_info.get_author().'</p>';
	$replacer .= '</div>';
	str_replace("<author_placeholder/>", $replacer, $html);
	
	//ultima modifica
	$replacer = '<h2>Ultima modifica</h2>'.'<p>'.$report_info.get_last_modified().'</p>';
	str_replace("<date_placeholder/>", $replacer, $html);

	//giocatori presenti
	//servirà prelevare le info degli utenti collegati con il report
	$usernameArray = getALLForReport($report_info.get_id());
	$replacer = '<h2>Giocatori presenti</h2><ul id="boxGiocatori">';
	foreach ($usernameArray as $linked_user){
		$replacer .= '<li>';
        $replacer .= '<div class="badgeUtente">';
        $replacer .= '<img src="'.getUserPic($linked_user).'" alt="Immagine profilo" />';
        $replacer .= '<p class="textVariable">'.$linked_user.'</p>';
        $replacer .= '</div>';
        $replacer .= '</li>';
	}
	$replacer .= '</ul>';

	str_replace("<LinkedPlayers_placeholder/>", $replacer, $html);

	//contenuto del report
	$replacer = '<h2>Descrizione della sessione</h2>';
	$replacer .= '<p>'.$report_info.get_content().'</p>';
	str_replace("<content_placeholder/>", $replacer, $html);

	//aggiungi un commento/registrati per commentare
	//TODO

	//lista dei commenti
	//devo mostrare il commento con tutti i suoi dati, oltre che l'immagine del giocatore (non è un dato del commento)
	$commentsArray = getComments($report_info.get_id());
	$replacer = '<ul id="listaCommenti">';
	foreach($commentsArray as $singleComment){
		$replacer .= '<li class="commento"><div class="badgeUtente">';
		$replacer .= '<img src="'.getUserPic($singleComment.get_author()).'" alt="Immagine profilo" />';
		$replacer .= '<p class="textVariable">'.$singleComment.get_author().'</p></div>';
		$replacer .= '<div class="testoCommento">';
		$replacer .= '<p>'.$singleComment.get_text().'</p>';
		$replacer .= '<p class="dateTimeCommento">'.$singleComment.get_date().'</p></div>';
		$replacer .= '<input title="elimina commento" type="submit" name="eliminaCommento" value="IDCommento"/></li>';
		//quest'ultimo è il tasto per eliminare il commento.
		//TODO controllare quando mostrarlo e quando no.
	}
	$replacer .= '</ul>';

	str_replace("<comments_placeholder/>", $replacer, $html);

	//chiudo la connessione
	$dbInterface->closeConnection();

	//stampo la pagina
	echo ($html);
}

?>