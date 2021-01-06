<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "approfondimento_Report.html");
$html = setup_clear($html);
$html = addPossibleBanner($html, "Approf_report.php");

echo $html;

?>