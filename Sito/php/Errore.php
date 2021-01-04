<?php
require_once("GeneralPurpose.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "Errore.html");
$html = setup_clear($html);

echo $html;

?>