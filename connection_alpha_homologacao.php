<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

//$db_alpha = new mysqli($server, $username, $password, $db);
$db_alpha = mysqli_connect($server, $username, $password, $db);

?>