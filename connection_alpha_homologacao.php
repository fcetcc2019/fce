<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$db_alpha = mysql_connect($server, $username, $password);//, $db);
mysql_select_db($db, $db_alpha) or die ("Erro!");

echo $server.'\n';
echo $username.'\n';
echo $password.'\n';

?>