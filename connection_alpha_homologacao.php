<?php

  $writerUser = array('user' => 'PortalWriter', 'pass' => 'P0rt@1w81terR$');
  $readerUser = array('user' => 'PortalReader', 'pass' => 'P0rt@183@d3rR$');
  $defaultCatalog = "PortalSenacRS";
  
  
  //$db = mysql_connect("ALPHA\\PORTAL", $writerUser['user'], $writerUser['pass']) or die ("DB error!".mysql_get_last_message());
  //$catalog = mysql_select_db($defaultCatalog, $db) or die ("Erro!");

  
  $db_alpha = mysql_connect("localhost:3308", "root", "") or die ("DB error!".mysql_error());
  //echo "Conectou ao banco corretamente.<br />";
  $catalog_alpha = mysql_select_db($defaultCatalog, $db_alpha) or die ("Erro!");
  //echo "Selecionou corretamente ".$defaultCatalog.".<br />";

  
  //$db_alpha_homologacao = mysql_connect("ALPHA\\HOMOLOGACAO", "intranet", "!ntr@net") or die ("DB error!".mysql_get_last_message());
  //echo "Conectou ao banco corretamente.<br />";
  //$catalog_alpha_homologacao = mysql_select_db("PortalSenacRS_Homologacao", $db_alpha_homologacao) or die ("Erro!");
  //echo "Selecionou corretamente ".$defaultCatalog.".<br />";

?>