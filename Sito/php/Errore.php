<?php 

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "Errore.html");

if(isset($_SESSION["username"]))
{
    $html = str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
    $html = str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
}

$html = addPossibleBanner($html, "Errore.php");

echo $html;

?>