<?php

  $writerUser = array('user' => 'PortalWriter', 'pass' => 'P0rt@1w81terR$');
  $readerUser = array('user' => 'PortalReader', 'pass' => 'P0rt@183@d3rR$');
  //$defaultCatalog = "PortalSenacRS";
  $defaultCatalog = "heroku_035be66877c33ed";
    
  //$db_alpha = mysql_connect("localhost:3308", "root", "") or die ("DB error!".mysql_error());
  $db_alpha = mysql_connect("us-cdbr-iron-east-02.cleardb.net", "b418da099072c8", "919d9ec7") or die ("DB error!".mysql_error());


  //echo "Conectou ao banco corretamente.<br />";
  $catalog_alpha = mysql_select_db($defaultCatalog, $db_alpha) or die ("Erro!");
  //echo "Selecionou corretamente ".$defaultCatalog.".<br />";


?>