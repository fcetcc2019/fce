<?php
include("../connection_aries.php");
//include("../connection.php");
include("../connection_alpha_homologacao.php");
//header('Content-type: text/html; charset=ISO-8859-1');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="../jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
	
	<script type="text/javascript" src="script.js"></script>
    <link href="estilo.css" rel="stylesheet" type="text/css" />
    <!-- TWITTER BOOTSTRAP CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
 
    <!-- TWITTER BOOTSTRAP JS -->
	<script src="js/bootstrap.min.js"></script>
    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Consulta de Enquetes</title>

<?php

//$usuario = trim(strtoupper(substr($_SERVER["AUTH_USER"],8,100)));
$usuario = 'DJUNIOR';
	
mysql_select_db("Siap", $db_alpha);

$sql20 = "SELECT * FROM stUsuario where IdUsuario = '".$usuario."'"; 
//$qsql20 = mysql_query($sql20, $db2);
$qsql20 = mysql_query($sql20, $db_alpha);
if($res0 = mysql_fetch_assoc($qsql20)){
	$unidade = trim($res0['IdUO']);
	$complemento = "where e.unidade = '$unidade'";
	if($unidade == "AM"){ $complemento = ""; }
}
?>
<style>
body{
/*	font-family:Verdana, Geneva, sans-serif; font-size:11px;*/
}
.imp{
border:none;
background-color:#333;	
}
.botao{
position:relative;
float:left;
margin: 10px;
padding: 10px;
background-color:#00C;
color:#FFF;	
}
.botao a{
color:#FFF;
text-decoration:none;	
}
.botao a:hover{
color:#FF0;
}
</style>
<script type="text/javascript" src="jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
});
</script>
</head>

<body>
<p>
<div class="navbar-inner"><span style="position:relative; float:left;"><h4>Escolha abaixo a enquete para visualizar o relatório:</h4></span></div>
<br />
<br />

<?php
//$sql2 = "SELECT ag.Evento,ag.id,ag.DataInicial,ag.Horario FROM CAD_agenda_habilita_inscricao as insc inner join CAD_agenda as ag on ag.id = insc.id_agenda inner join CAD_agenda_unidade as uni on uni.ID_Agenda = ag.id $complemento order by DataIni desc";
$sql2 = "select p.id_enquete, e.id, e.titulo, p.pergunta, e.unidade, e.balcao, e.ativo from CAD_enquete_pergunta p inner join CAD_enquete e on e.id = p.id_enquete ".$complemento." order by e.id desc"; 

//echo $sql2;

//$qsql2 = mysql_query($sql2, $db);

mysql_select_db("PortalSenacRS", $db_alpha);

$qsql2 = mysql_query($sql2, $db_alpha);
$ativo = "";

while($res = mysql_fetch_assoc($qsql2)){
	if($res['ativo'] == 1) {
		$ativo = "Ativado";
	} else {
		$ativo = "Desativado";
	}
	
	//echo "<p><div class='btn btn-info' style='margin-left:20px;'><a href='relatorio_enquete.php?id=".$res['id']."' style='color:#FFF;'><b>".utf8_encode($res['titulo'])." - ".utf8_encode($res['pergunta'])." - ".$res['unidade']." - ".$res['balcao']." - ".$ativo."</b></a></div></p>";
	echo "<p><div class='btn btn-info' style='margin-left:20px;'><a href='relatorio_enquete.php?id=".$res['id']."' style='color:#FFF;' unidade='".$res['unidade']."' balcao='".$res['balcao']."'><b>".utf8_encode($res['titulo'])." - ".utf8_encode($res['pergunta'])." - ".$ativo."</b></a></div></p>";
}

?>

</p>

</body>
</html>