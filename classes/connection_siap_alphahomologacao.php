<?php
//$ariesUser = array('user' => 'AcessoSiteSenac', 'pass' => 'ursomaior01');
$db2 = mssql_connect("ALPHA\\HOMOLOGACAO", "Writer", "Writer") or die ("DB error! ALPHA\HOMOLOGACAO - Siap --> ".mssql_get_last_message());
$defaultCatalog2 = "Siap";
$catalog2 = mssql_select_db($defaultCatalog2, $db2) or die ("Erro! --> aqui - ".mssql_get_last_message());
?>