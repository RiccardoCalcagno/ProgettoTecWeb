<?php

//require
require_once ("DBinterface");
require_once ("report_data");

use DB/DBinterface;

//prelevo Report.html
$html = file_get_contents('../otherHTMLs/Report.html');

$dbInterface = new DBinterface();
$connection = $dbInterface->openConnection();

if($connection == false){
	//redirecting to 404
}
else{
	//di seguito tutti gli accorgimenti per stampare le parti prelevate da DB all'interno della pagina Report.html
	//Devo inserire titolo, sottotitolo, contenuto, autore, img_autore, giocatori collegati, commenti, ultima modifica.

		//prelevo il report desiderato, in base all'id contenuto in $selected_report_id
		//$report_info = get_report($selected_report_id);
	//ATTENZIONE, sopra è un alternativa, segue invece come se questa pagina ricevesse direttamente l'oggetto report, $report_info

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
        $replacer .= '</li>;'
	}
	$replacer .= '</ul>';

	str_replace("<LinkedPlayers_placeholder/>", $replacer, $html);

	//contenuto del report
	$replacer = '<h2>Descrizione della sessione</h2>';
	$replacer .= '<p>'.$report_info.get_content().'</p>';
	str_replace("<content_placeholder/>", $replacer, $html);

}