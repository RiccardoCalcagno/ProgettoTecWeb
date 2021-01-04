<?php
require_once("GeneralPurpose.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "Approfondimenti" . DIRECTORY_SEPARATOR . "approfondimento_Report.html");
$html = setup_clear($html);

echo $html;

?>