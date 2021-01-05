<?php 
require_once("php" . DIRECTORY_SEPARATOR . "GeneralPurpose.php");
require_once("php" . DIRECTORY_SEPARATOR . "banners.php");

<<<<<<< Updated upstream
$html = file_get_contents("Home.html");
$html = setup_clear($html);
$html = addPossibleBanner($html, "../index.php");
=======
$html = file_get_contents(".." . DIRECTORY_SEPARATOR . "Home.html");

if(isset($_SESSION["username"]))
{
    $html = str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
    $html = str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
}


$html = addPossibleBanner($html, "index.php");
>>>>>>> Stashed changes

echo $html;

?>