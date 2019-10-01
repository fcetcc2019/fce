<?php
//ini_set('error_reporting',E_ALL);
//include("../connection.php");
include("../connection_alpha_homologacao.php");

if($_POST['acao']=="buscar"){
	
	$dados[] = '';
	$id_enquete = $_POST['id_enquete'];
	
	//echo "a";
	//$sql = "SELECT IdUO, Nome FROM INF_unidades WHERE (AtivoSite = 1) ORDER BY Nome";
	$sql = "SELECT e.id, e.titulo, p.pergunta, r.resposta, r.id_pergunta, e.publicado FROM CAD_enquete_resposta r INNER JOIN CAD_enquete_pergunta p ON p.id = r.id_pergunta INNER JOIN CAD_enquete e ON e.id = p.id_enquete WHERE e.id = '".$id_enquete."'";
	
	$query = mysql_query($sql, $db_alpha);
	
	while($res = mysql_fetch_assoc($query)) {
		$res['id'] = utf8_encode($res['id']);
		$res['titulo'] = utf8_encode($res['titulo']);
		$res['pergunta'] = utf8_encode($res['pergunta']);
		$res['resposta'] = utf8_encode($res['resposta']);
		$res['id_pergunta'] = utf8_encode($res['id_pergunta']);
		$res['publicado'] = utf8_encode($res['publicado']);
		
		//echo json_encode($res);
		$dados[] = $res;
	}
	
	echo json_encode($dados);
	
}


?>
