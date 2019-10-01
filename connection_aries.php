<?php
$ariesUser = array('user' => 'AcessoSiteSenac', 'pass' => 'ursomaior01');
//$db2 = mysql_connect("ARIES2\\INTRANET", $ariesUser['user'], $ariesUser['pass']) or die ("DB error!".mysql_get_last_message());
$db2 = mysql_connect("localhost:3308", "root", "") or die ("DB error!".mysql_error());
$defaultCatalog2 = "Siap";
$catalog2 = mysql_select_db($defaultCatalog2, $db2) or die ("Erro!");
?>