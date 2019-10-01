<?php
$ariesUser = array('user' => 'AcessoSiteSenac', 'pass' => 'ursomaior01');
$db2 = mssql_connect("ARIES2\\INTRANET", $ariesUser['user'], $ariesUser['pass']) or die ("DB error!".mssql_get_last_message());
$defaultCatalog2 = "Siap";
$catalog2 = mssql_select_db($defaultCatalog2, $db2) or die ("Erro!");
?>