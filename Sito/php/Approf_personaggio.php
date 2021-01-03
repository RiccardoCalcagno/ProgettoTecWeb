<?php 

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "Approfondimenti" . DIRECTORY_SEPARATOR . "approfondimento_scheda_giocatore.html");

if(isset($_SESSION["username"]))
{
    $html = str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
    $html = str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
}

$html = addPossibleBanner($html, "Approf_personaggio.php");

echo $html;

?>