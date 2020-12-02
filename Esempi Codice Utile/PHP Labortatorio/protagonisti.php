<?php
//<require>sarebbe l'include e <require_once> aggiunge il IFNOTDEFINED
require_once __DIR__ . DIRECTORY_SEPARATOR . "toFromDatabase.php";
use DB\DBAccess;        // USO DEL NAMESPACE

$dbAccess = new DBAccess();
$connessioneRiuscita = $dbAccess->openDBConnection();

if($connessioneRiuscita == false){
    die ("Errore nell'apertura");  //questo metodo NON VA BENE, VA GESTITA LA PAGINA
    //DEVO SOLLEVARE UN?ECCEZIONE INVECE DI RITORNARE UN falso o true
}else{

    $listaProtagonisti = $dbAccess->getListaPersonaggi();

    $dbAccess->closeDBConnection();  // IMPORTANTE CHIUDERLO APPENA POSSIBILE!!!!

    $definitionListProtagonisti = "";
    $paginaHTML = file_get_contents('protagonisti.html'); 

    if($listaProtagonisti != null){
        // Creo parte di pagina HTML con elenco dei protag
        
        $definitionListProtagonisti = '<dl id="charactersStory">';
        
        foreach ($listaProtagonisti as $protagonista){

            $definitionListProtagonisti .= "<dt>" . $protagonista['Nome'] . '</dl>';
            $definitionListProtagonisti .= "<dd>";          // DIRECTORY_SEPARATOR serve se non è ben chiaro quale è il path che porta al protagonista
            $definitionListProtagonisti .= '<img src="images/'. DIRECTORY_SEPARATOR . $protagonista['Immagine'] . '" alt="' . $protagonista['AltImmagine'] . '" />';
            $definitionListProtagonisti .= '<p>' . $protagonista["Descrizione"] . '</p>';
            $definitionListProtagonisti .= '<p class="aiutoTornaSu"><a href="#contentPage>Torna su </a></p>';
            $definitionListProtagonisti .=  '</dd>';
        }

        $definitionListProtagonisti = $definitionListProtagonisti . "</dl>";

        //con echo trovo il tag segnaposto per sostituirlo con la porzione di HTML creata
        echo str_replace("<listaPersonaggi />", $definitionListProtagonisti, $paginaHTML);

    }else{

        $definitionListProtagonisti = "<p>Nessun personaggio Presente</p>";

        // messaggio che ci dice che non ci sono protagonisti
    }

    echo str_replace("<listaPersonaggi />", $definitionListProtagonisti, $paginaHTML);
}


?>