<?php

  $writerUser = array('user' => 'PortalWriter', 'pass' => 'P0rt@1w81terR$');
  $readerUser = array('user' => 'PortalReader', 'pass' => 'P0rt@183@d3rR$');
  $defaultCatalog = "PortalSenacRS";
  
  //$db_alpha = mssql_connect("ALPHA\\HOMOLOGACAO", "Writer", "Writer") or die ("DB error!".mssql_get_last_message());
  $db = mssql_connect("ALPHA\\HOMOLOGACAO", "Writer", "Writer") or die ("DB error!".mssql_get_last_message());
  //echo "Conectou ao banco corretamente.<br />";
  //$catalog_alpha = mssql_select_db("PortalSenacRS", $db_alpha) or die ("Erro!");
  $catalog = mssql_select_db("PortalSenacRS", $db) or die ("Erro!");
  //echo "Selecionou corretamente ".$defaultCatalog.".<br />";

?>