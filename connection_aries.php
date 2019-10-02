<?php
$ariesUser = array('user' => 'AcessoSiteSenac', 'pass' => 'ursomaior01');
//$db2 = mysql_connect("ARIES2\\INTRANET", $ariesUser['user'], $ariesUser['pass']) or die ("DB error!".mysql_get_last_message());
//$db2 = mysql_connect("localhost:3308", "root", "") or die ("DB error!".mysql_error());
$db2 = mysql_connect("us-cdbr-iron-east-02.cleardb.net", "b418da099072c8", "919d9ec7") or die ("DB error!".mysql_error());
//$defaultCatalog2 = "Siap";
$defaultCatalog = "heroku_035be66877c33ed";
$catalog2 = mysql_select_db($defaultCatalog2, $db2) or die ("Erro!");
?>