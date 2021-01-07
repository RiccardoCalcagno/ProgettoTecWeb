<?php
require_once("CharPagesUtil.php");

$toEdit = false;

if ( isset($_GET['charAction']) && $_GET['charAction'] == 'MODIFICA' ) {
    $toEdit =  true;
    
}

echo Char_Form($toEdit);  

?>