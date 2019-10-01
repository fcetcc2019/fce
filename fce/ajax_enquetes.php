<?php
//ini_set('error_reporting',E_ALL);
//include("../connection.php");
include("../connection_alpha_homologacao.php");

if($_POST['acao']=="buscar"){
	
	$dados[] = '';
	$id_enquete = $_POST['id_enquete'];
	
	//$sql = "SELECT e.id, e.titulo, p.pergunta, r.resposta, r.id_pergunta, e.publicado FROM CAD_enquete_resposta r INNER JOIN CAD_enquete_pergunta p ON p.id = r.id_pergunta INNER JOIN CAD_enquete e ON e.id = p.id_enquete WHERE e.id = '".$id_enquete."'";
	$sql = "SELECT e.id, e.titulo, p.pergunta, r.resposta, r.id_pergunta, r.id id_resposta, e.publicado FROM CAD_enquete_resposta r INNER JOIN CAD_enquete_pergunta p ON p.id = r.id_pergunta INNER JOIN CAD_enquete e ON e.id = p.id_enquete WHERE e.id = '".$id_enquete."'";
	
	$query = mysql_query($sql, $db_alpha);
	
	while($res = mysql_fetch_assoc($query)) {
		$res['id'] = utf8_encode($res['id']);
		$res['titulo'] = utf8_encode($res['titulo']);
		$res['pergunta'] = utf8_encode($res['pergunta']);
		$res['resposta'] = utf8_encode($res['resposta']);
		$res['id_pergunta'] = utf8_encode($res['id_pergunta']);
		$res['publicado'] = utf8_encode($res['publicado']);
		$res['id_resposta'] = utf8_encode($res['id_resposta']);
		
		//echo json_encode($res);
		$dados[] = $res;
	}
	
	echo json_encode($dados);
	
}

if($_POST['acao']=="verificaEnquetesAtivas"){
	
	$dados = array();
	$id_unidade = $_POST['id_unidade'];
	$strUnidade = "'".$id_unidade."'";
	if(isset($_POST['id_balcao']) && empty($_POST['id_balcao'])) {
		$id_balcao = '';
		$strBalcao = "AND balcao IS NULL";
	} else {
		$id_balcao = $_POST['id_balcao'];
		$strBalcao = "AND balcao = '".$id_balcao."'";
	}
	
	//echo "a";
	//$sql = "SELECT e.id, e.titulo, p.pergunta, r.resposta, r.id_pergunta, e.publicado FROM CAD_enquete_resposta r INNER JOIN CAD_enquete_pergunta p ON p.id = r.id_pergunta INNER JOIN CAD_enquete e ON e.id = p.id_enquete WHERE e.id = '".$id_enquete."'";
	$sql = "SELECT * FROM CAD_enquete WHERE unidade = ".$strUnidade." ".$strBalcao." AND ativo = 1";
	//echo json_encode($sql);
	$query = mysql_query($sql, $db_alpha);
	
	$total = 0;
	while($res = mysql_fetch_assoc($query)) {
		$res['id'] = utf8_encode($res['id']);
		$res['titulo'] = utf8_encode($res['titulo']);
		
		$total++;
		
		$dados[] = $res;
	}
	
	$dados['total'] = $total;
	
	echo json_encode($dados);
	
}

if($_POST['acao']=="insereJustificativa"){
	
	$id_enquete = utf8_decode($_POST['id_enquete']);
	$justificativa = utf8_decode($_POST['justificativa']);
	$usuario = utf8_decode($_POST['usuario']);
	
	$sql = "INSERT INTO CAD_enquete_justificativa(id_enquete, justificativa, usuario, data) VALUES('".$id_enquete."', '".$justificativa."', '".$usuario."', NOW())";
	//echo $sql;
	if(mysql_query($sql, $db_alpha)) {
		echo json_encode('ok');
	} else {
		echo json_encode('ERRO ao inserir a justificativa! - '.mysql_get_last_message());
	}

}

if($_POST['acao']=="inserir"){
	
	$unidade = utf8_decode($_POST['unidade']);
	$enquete = utf8_decode($_POST['enquete']);
	$pergunta = utf8_decode($_POST['pergunta']);
	//$respostas = explode("%", utf8_decode($_POST['respostas']));
	$respostas = $_POST['respostas'];
	$demaiscampos = $_POST['demaiscampos'];
	
	if(isset($_POST['balcao']) && !empty($_POST['balcao'])) {
		//echo json_encode('aqui [1]');
		$sql = "INSERT INTO CAD_enquete(titulo, unidade, ativo, balcao) VALUES ('".$enquete."','".$unidade."', 1, '".$_POST['balcao']."')";
	} else {
		//echo json_encode('aqui [2]');
		$sql = "INSERT INTO CAD_enquete(titulo, unidade, ativo) VALUES ('".$enquete."','".$unidade."', 1)";
	}
	
	//echo json_encode($sql);
	
	if(mysql_query($sql, $db_alpha)) {
		
		if(isset($_POST['balcao']) && !empty($_POST['balcao'])) {
			$buscaEnq = "SELECT id FROM CAD_enquete WHERE balcao = '".$_POST['balcao']."' ORDER BY id DESC";
		} else {
			$buscaEnq = "SELECT id FROM CAD_enquete WHERE unidade = '".$unidade."' ORDER BY id DESC";
		}
		$queryEnq = mysql_query($buscaEnq, $db_alpha);
		
		$id_enquete = '';
		if($resEnq = mysql_fetch_assoc($queryEnq)) {
			$id_enquete = $resEnq['id'];
		}
		
		$sqlPergunta = "INSERT INTO CAD_enquete_pergunta(id_enquete, pergunta) VALUES ('".$id_enquete."','".$pergunta."')";
		if(mysql_query($sqlPergunta, $db_alpha)) {
			
			$buscaPerg = "SELECT id FROM CAD_enquete_pergunta WHERE id_enquete = '".$id_enquete."' ORDER BY id DESC";
			$queryPerg = mysql_query($buscaPerg, $db_alpha);
			
			$id_pergunta = '';
			if($resPerg = mysql_fetch_assoc($queryPerg)) {
				$id_pergunta = $resPerg['id'];
			}
			
			$ok = 0;
			
			if(is_array($respostas)) {
			
				for($i = 0; $i < count($respostas); $i++) {
					
					//$sqlResposta = "INSERT INTO CAD_enquete_resposta(id_pergunta, resposta) VALUES ('".$id_pergunta."','".trim($respostas[$i])."')";
					$sqlResposta = "INSERT INTO CAD_enquete_resposta(id_pergunta, resposta) VALUES ('".$id_pergunta."','".utf8_decode(trim($respostas[$i]))."')";
					if(!mysql_query($sqlResposta)) {
						echo json_encode('ERRO ao inserir a RESPOSTA da enquete! - '.mysql_get_last_message());
					} else {
						$ok++;
					}
				}
			
			} else {
				
				$sqlResposta = "INSERT INTO CAD_enquete_resposta(id_pergunta, resposta) VALUES ('".$id_pergunta."','".utf8_decode(trim($respostas))."')";
				if(!mysql_query($sqlResposta)) {
					echo json_encode('ERRO ao inserir a RESPOSTA da enquete! - '.mysql_get_last_message());
				} else {
					$ok++;
				}
				
			}
			
		} else {
			echo json_encode('ERRO ao inserir a PERGUNTA da enquete! - '.mysql_get_last_message());
		}

		if(is_array($demaiscampos)) {

			for($i = 0; $i < count($demaiscampos); $i++) {
				$sqlDemaisCampos = "INSERT INTO CAD_enquete_contato_demaiscampos(campo, id_enquete) VALUES ('".utf8_decode(trim($demaiscampos[$i]))."','".$id_enquete."')";

				if(mysql_query($sqlDemaisCampos)) {
					echo json_encode('tudo certo com os campos de contato - ');
				} else {
					echo json_encode('ERRO com os campos de contato - '.mysql_get_last_message());
				}
			}
			
		}
		
	} else {
		echo json_encode('ERRO ao inserir a enquete! - '.mysql_get_last_message());
	}
	
	if($ok > 0) {
		echo json_encode('Enquete inserida com sucesso!');
	} else {
		echo json_encode('ERRO GERAL ao inserir a enquete! - '.mysql_get_last_message());
	}
	
}

if($_POST['acao']=="atualizar"){
	
	$unidade = utf8_decode($_POST['unidade']);
	$area = utf8_decode($_POST['area']);
	$titulo = utf8_decode($_POST['titulo']);
	$autor = utf8_decode($_POST['autor']);
	$formacao = utf8_decode($_POST['formacao']);
	$artigo = utf8_decode($_POST['artigo']);
	
	$sql = "UPDATE INF_artigos SET ID_Unidade = '".$unidade."', Eixo = '".$area."', Titulo = '".$titulo."', Autor = '".$autor."', Formacao = '".$formacao."', Artigo = '".$artigo."' WHERE Id = '".$_POST['id_artigo']."'";
	//echo $sql;
	if(mysql_query($sql, $db_alpha)) {
		echo json_encode('Artigo atualizado com sucesso!');
	} else {
		echo json_encode('ERRO ao atualizar o artigo! - '.mysql_get_last_message());
	}

}

if($_POST['acao']=="excluir"){
	//echo 'Aqui! - '.$_POST['id'];
	$sql = "DELETE FROM INF_artigos WHERE Id = '".$_POST['id']."'";
	if(mysql_query($sql, $db_alpha)) {
		//echo json_encode('Artigo excluído com sucesso!');
		echo utf8_encode('Artigo excluído com sucesso!');
	} else {
		//echo json_encode('ERRO ao excluir o artigo! - '.mysql_get_last_message());
		echo utf8_encode('ERRO ao excluir o artigo! - '.mysql_get_last_message());
	}

}

if($_POST['acao']=="ativar_desativar"){
	
	$id_enquete = utf8_decode($_POST['id_enquete']);
	$ativo = utf8_decode($_POST['ativo']);
	
	$sql = "UPDATE CAD_enquete SET ativo = '".$ativo."' WHERE id = '".$_POST['id_enquete']."'";
	//echo $sql;
	if(mysql_query($sql, $db_alpha)) {
		echo json_encode('ok');
	} else {
		echo json_encode('erro');
	}

}

if($_POST['acao']=="altera_pergunta"){
	
	$id_pergunta = utf8_decode($_POST['id_pergunta']);
	$pergunta = utf8_decode($_POST['pergunta']);
	
	$sql = "UPDATE CAD_enquete_pergunta SET pergunta = '".trim($pergunta)."' WHERE id = '".$id_pergunta."'";
	//echo $sql;
	//echo json_encode($sql);
	//die();
	
	if(mysql_query($sql, $db_alpha)) {
		echo json_encode(1);
	} else {
		echo json_encode('ERRO altera_pergunta - '.mysql_get_last_message());
	}

}

if($_POST['acao']=="altera_resposta"){
	
	$id_resposta = utf8_decode($_POST['id_resposta']);
	$resposta = utf8_decode($_POST['resposta']);
	
	$sql = "UPDATE CAD_enquete_resposta SET resposta = '".trim($resposta)."' WHERE id = '".$id_resposta."'";
	//echo $sql;
	//echo json_encode($sql);
	//die();
	
	if(mysql_query($sql, $db_alpha)) {
		echo json_encode(1);
	} else {
		echo json_encode('ERRO altera_resposta - '.mysql_get_last_message());
	}

}

if($_POST['acao']=="publicar"){
	
	$id_enquete = utf8_decode($_POST['id_enquete']);
	//$publicado = utf8_decode($_POST['publicado']);
	
	//$sql = "UPDATE CAD_enquete SET publicado = '".$publicado."' WHERE id = '".$_POST['id_enquete']."'";
	$sql = "UPDATE CAD_enquete SET publicado = '1' WHERE id = '".$_POST['id_enquete']."'";
	//echo $sql;
	if(mysql_query($sql, $db_alpha)) {
		echo json_encode('ok');
	} else {
		echo json_encode('erro');
	}

}

if($_POST['acao']=="naopublicar"){
	
	$id_enquete = utf8_decode($_POST['id_enquete']);
	//$publicado = utf8_decode($_POST['publicado']);
	
	//$sql = "UPDATE CAD_enquete SET publicado = '".$publicado."' WHERE id = '".$_POST['id_enquete']."'";
	$sql = "UPDATE CAD_enquete SET publicado = '0' WHERE id = '".$_POST['id_enquete']."'";
	//echo $sql;
	if(mysql_query($sql, $db_alpha)) {
		echo json_encode('ok');
	} else {
		echo json_encode('erro');
	}

}

if($_POST['acao'] == 'busca_balcao') {
	$sql = "SELECT cod, Titulo FROM INF_balcoes WHERE codigounidade = '".$_POST['unidade']."' ORDER BY Titulo ASC";
	$query = mysql_query($sql, $db_alpha);
	echo '<option value="">Selecione...</option>';
	while($res = mysql_fetch_assoc($query)) {
		echo '<option value="'.trim(utf8_encode($res['cod'])).'">'.trim(utf8_encode($res['Titulo'])).'</option>';
	}
}

if($_POST['acao']=="carrega_grafico"){
	
	$dados = array();
	$id_unidade = $_POST['id_unidade'];
	$strUnidade = "'".$id_unidade."'";
	if(isset($_POST['id_balcao']) && empty($_POST['id_balcao'])) {
		$id_balcao = '';
		$strBalcao = "AND balcao IS NULL";
	} else {
		$id_balcao = $_POST['id_balcao'];
		$strBalcao = "AND balcao = '".$id_balcao."'";
	}
	
	//echo "a";
	
	//$sql = "SELECT p.pergunta, count(r.id) as total_respostas FROM cad_enquete_resposta r INNER JOIN CAD_enquete_pergunta p ON p.id = r.id_pergunta INNER JOIN CAD_enquete e ON e.id = p.id_enquete WHERE e.unidade = ".$strUnidade." GROUP BY p.pergunta ORDER BY total_respostas DESC LIMIT 0, 10";
	$sql = "SELECT p.pergunta, count(rc.id) as total_respostas FROM cad_enquete_respostacliente rc RIGHT JOIN CAD_enquete_resposta r ON r.id = rc.id_resposta RIGHT JOIN CAD_enquete_pergunta p ON p.id = r.id_pergunta RIGHT JOIN CAD_enquete e ON e.id = p.id_enquete WHERE e.unidade = ".$strUnidade." GROUP BY p.pergunta ORDER BY total_respostas DESC LIMIT 0, 10";	
	
	//echo json_encode($sql);
	$query = mysql_query($sql, $db_alpha);
	
	$total = 0;
	while($res = mysql_fetch_assoc($query)) {
		$res['pergunta'] = utf8_encode($res['pergunta']);
		$res['total_respostas'] = utf8_encode($res['total_respostas']);
		
		$total++;
		
		$dados[] = $res;
	}
	
	$dados['total'] = $total;
	
	echo json_encode($dados);
	//echo $dados;
	
}

if($_POST['acao']=="carrega_grafico2"){
	
	$dados = array();
	$id_unidade = $_POST['id_unidade'];
	$strUnidade = "'".$id_unidade."'";
	if(isset($_POST['id_balcao']) && empty($_POST['id_balcao'])) {
		$id_balcao = '';
		$strBalcao = "AND balcao IS NULL";
	} else {
		$id_balcao = $_POST['id_balcao'];
		$strBalcao = "AND balcao = '".$id_balcao."'";
	}
	
	$where = '';
	$temWhere = false;
	$unidade = '';
	$ativo = '';
	$inativo = '';
	$pergunta = '';
	$id_enquete = '';
	
	if(isset($_POST['id_unidade']) && !empty($_POST['id_unidade']) && $_POST['id_unidade'] != '0') {
		//$unidade = $_GET['unidadeBusca'];
		//$unidade = $_GET['unidadeBusca'];
		$where .= " e.unidade = '".$_POST['id_unidade']."' AND ";
		$temWhere = true;
	}
	if(isset($_POST['ativoInativo']) && $_POST['ativoInativo'] == 'ativas') {
		//$ativo = " ativo = 1 AND ";
		//$where .= " e.ativo = 1 AND ";
		$temWhere = true;
	}
	if(isset($_POST['ativoInativo']) && $_POST['ativoInativo'] == 'inativas') {
		//$ativo = " ativo = 0 AND ";
		//$where .= " e.ativo = 0 AND ";
		$temWhere = true;
	}
	if(isset($_POST['ativoInativo']) && $_POST['ativoInativo'] == 'todas') {
		$where .= "";
		//$temWhere = true;
	}
	
	
	if($temWhere) {
		$where = ' WHERE '.rtrim($where, "AND ");
	}
	
	//echo "a";
	
	//$sql = "SELECT p.pergunta, count(rc.id) as total_respostas FROM cad_enquete_respostacliente rc RIGHT JOIN CAD_enquete_resposta r ON r.id = rc.id_resposta RIGHT JOIN CAD_enquete_pergunta p ON p.id = r.id_pergunta RIGHT JOIN CAD_enquete e ON e.id = p.id_enquete WHERE e.unidade = ".$strUnidade." GROUP BY p.pergunta ORDER BY total_respostas DESC LIMIT 0, 10";
	$sql = "SELECT p.pergunta, count(rc.id) as total_respostas FROM cad_enquete_respostacliente rc RIGHT JOIN CAD_enquete_resposta r ON r.id = rc.id_resposta RIGHT JOIN CAD_enquete_pergunta p ON p.id = r.id_pergunta RIGHT JOIN CAD_enquete e ON e.id = p.id_enquete ".$where." GROUP BY p.pergunta ORDER BY total_respostas DESC LIMIT 0, 10";
	
	//echo json_encode($sql);
	$query = mysql_query($sql, $db_alpha);
	
	$total = 0;
	$totais_somados = 0;
	
	while($res = mysql_fetch_assoc($query)) {
		$res['pergunta'] = utf8_encode($res['pergunta']);
		$res['total_respostas'] = utf8_encode($res['total_respostas']);
		
		$total++;
		$totais_somados += $res['total_respostas'];
		
		$dados[] = $res;
	}
	
	$dados['total'] = $total;
	$dados['totais_somados'] = $totais_somados;
	
	echo json_encode($dados);
	//echo $dados;
	
}


?>
