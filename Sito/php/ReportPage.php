<?php

//require
require_once ("DBinterface");
require_once ("report_data");

use DB\DBinterface;

//prelevo Report.html
$html = file_get_contents('../otherHTMLs/Report.html');

//prelevo l'oggetto report
session_start();
$report_info = $_SESSION["report_id"];


if(isset($_SESSION["username"]))
{
	str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
	str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
}

$dbInterface = new DBinterface();
$connection = $dbInterface->openConnection();

//faccio subito le richieste al DB per poter chiudere la connessione
$usernameArray = getALLForReport($report_info.get_id()); //si tratta di un array di username, sono i giocatori collegati al report

$userPic = array();
for ($i = 0; i < count($usernameArray);$i++){
	$userPic[$i] = getUserPic($usernameArray[$i]);
}

$commentsArray = getComments($report_info.get_id());

$commenterPic = array();
for ($i = 0; i < count($commentsArray);$i++){
	$commenterPic[$i] = getUserPic($commentsArray[$i].get_author());
}

//chiudo la connessione
$dbInterface->closeConnection();

if($connection == false){
	header("Location : 404.php");
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
	$replacer = '<h2>Giocatori presenti</h2><ul id="boxGiocatori">';
	for ($i = 0; $i < count($usernameArray);$i++){
		$replacer .= '<li>';
	    $replacer .= '<div class="badgeUtente">';
	    $replacer .= '<img src="'.$userPic[$i].'" alt="Immagine profilo" />';
	    $replacer .= '<p class="textVariable">'.$usernameArray[$i].'</p>';
	    $replacer .= '</div>';
	    $replacer .= '</li>';
	}
	/*OLD VERSION
	foreach ($usernameArray as $linked_user){
		$replacer .= '<li>';
        $replacer .= '<div class="badgeUtente">';
        $replacer .= '<img src="'.getUserPic($linked_user).'" alt="Immagine profilo" />';
        $replacer .= '<p class="textVariable">'.$linked_user.'</p>';
        $replacer .= '</div>';
        $replacer .= '</li>';
	}
	*/
	$replacer .= '</ul>';

	str_replace("<LinkedPlayers_placeholder/>", $replacer, $html);

	//contenuto del report
	$replacer = '<h2>Descrizione della sessione</h2>';
	$replacer .= '<p>'.$report_info.get_content().'</p>';
	str_replace("<content_placeholder/>", $replacer, $html);

	//aggiungi un commento/registrati per commentare
	if(isset($_SESSION["username"])) {
		$replacer = '<div id="InserimentoCommento">
                        <input type="text" placeholder="Lascia un commento.." name="contenutoCommento" />
                        <input type="submit" name="report" value="COMMENTA" class="buttonLink" />
                    </div>';
        str_replace("<InsertComment_placeholder/>", $replacer, $html);
	}
	else{
		$replacer = '<div id="InserimentoCommento">
                        <p>Registrati per lasciare un commento</p>
                    </div>';
		str_replace("<InsertComment_placeholder/>", $replacer, $html);
	}

	//lista dei commenti
	//devo mostrare il commento con tutti i suoi dati, oltre che l'immagine del giocatore (non è un dato del commento)
	$replacer = '<ul id="listaCommenti">';
	for($i = 0; i < count($commentsArray);$i++){
		$replacer .= '<li class="commento"><div class="badgeUtente">';
		$replacer .= '<img src="'.$commenterPic[$i].'" alt="Immagine profilo" />';
		$replacer .= '<p class="textVariable">'.$commentsArray[$i].get_author().'</p></div>';
		$replacer .= '<div class="testoCommento">';
		$replacer .= '<p>'.$commentsArray[$i].get_text().'</p>';
		$replacer .= '<p class="dateTimeCommento">'.$commentsArray[$i].get_date().'</p></div>';
		$replacer .= '<input title="elimina commento" type="submit" name="eliminaCommento" value="IDCommento"/></li>';
		//quest'ultimo è il tasto per eliminare il commento.
		//TODO controllare quando mostrarlo e quando no.
	}
	/*OLD VERSION
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
	*/
	$replacer .= '</ul>';

	str_replace("<comments_placeholder/>", $replacer, $html);


	//tasti footer
	////costruisco un if per controllare se l'utente logged in è l'author, se si mostro i tasti
		//ESPLORA
		//controllo che l'utente sia il creatore come prima, ma controllo anche che il report non sia già segnato come pubblico
	if($_SESSION["username"])==$report_info.get_author()){
		$replacer = '<ul id="footAction">
		        		<li>
		            		<input type="submit" name="report" value="ELIMINA" class="buttonLink"/>
		        		</li>'
		if($report_info.get_isExplorable()){
			$replacer .= '<li>
		            		<input type="submit" name="report" value="Pubblica in ESPLORA" class="buttonLink"/> 
		        		</li>';
		}
		        		
		$replacer .= 	'<li>
		            		<input type="submit" name="report" value="MODIFICA" class="buttonLink"/> 
		        		</li>
		    		</ul>';
		str_replace("<footerAction_placeholder/>", $replacer, $html);
	}
	else{
		str_replace("<footerAction_placeholder/>", "", $html);
	}

	//stampo la pagina
	echo ($html);
}

?>