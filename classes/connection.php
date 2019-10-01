<?php

  $writerUser = array('user' => 'PortalWriter', 'pass' => 'P0rt@1w81terR$');
  $readerUser = array('user' => 'PortalReader', 'pass' => 'P0rt@183@d3rR$');
  $defaultCatalog = "PortalSenacRS";
  
  
  $db = mssql_connect("ALPHA\\PORTAL", $writerUser['user'], $writerUser['pass']) or die ("DB error!".mssql_get_last_message());
  //$db = mssql_connect("ALPHA\\PORTAL", "intranet", "!ntr@net") or die ("DB error! [1]".mssql_get_last_message());
  //echo "Conectou ao banco corretamente.<br />";
  $catalog = mssql_select_db($defaultCatalog, $db) or die ("Erro!");
  //echo "Selecionou corretamente ".$defaultCatalog.".<br />";
  
  //$db = mssql_connect("ALPHA\\PORTAL", $writerUser['user'], $writerUser['pass']) or die ("DB error!".mssql_get_last_message());
  $db_intranet = mssql_connect("ALPHA\\PORTAL", "intranet", "!ntr@net") or die ("DB error! [2]".mssql_get_last_message());
  //echo "Conectou ao banco corretamente.<br />";
  $catalog_intranet = mssql_select_db("intranet", $db_intranet) or die ("Erro!");
  //echo "Selecionou corretamente ".$defaultCatalog.".<br />";

  
  //$db_alpha = mssql_connect("ALPHA\\PORTAL", "intranet", "!ntr@net") or die ("DB error!".mssql_get_last_message());
  $db_alpha = mssql_connect("ALPHA\\HOMOLOGACAO", "Writer", "Writer") or die ("DB error! [3]".mssql_get_last_message());
  
  $catalog_alpha = mssql_select_db($defaultCatalog, $db_alpha) or die ("Erro!");
  //$catalog_alpha_homologacao = mssql_select_db("PortalSenacRS_Homologacao", $db_alpha_homologacao) or die ("Erro!");
  
  $db_alpha2 = mssql_connect("Alpha2\\Portal", "UsrIntranetPortal", "UsrIntranetPortal") or die ("DB error! [4]".mssql_get_last_message());
  //$db_alpha = mssql_connect("ALPHA\\HOMOLOGACAO", "intranet", "!ntr@net") or die ("DB error!".mssql_get_last_message());
  
  $catalog_alpha2 = mssql_select_db($defaultCatalog, $db_alpha2) or die ("Erro!");
  //$catalog_alpha_homologacao = mssql_select_db("PortalSenacRS_Homologacao", $db_alpha_homologacao) or die ("Erro!");
  
  
 // $db_relatorios = mssql_connect("SRV-DESENBD3\\RELATORIOS", "UsrConsultaAMKT", "xZD>l]7.xOz>d*NU") or die ("DB error! [3]".mssql_get_last_message());
  
 // $catalog_relatorios = mssql_select_db($defaultCatalog, $db_relatorios) or die ("Erro!");

?>