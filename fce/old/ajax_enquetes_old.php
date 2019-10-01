<?php
include("../connection.php");
//ini_set('error_reporting',E_ALL);

if($_POST['acao']=="buscar"){
	//echo "a";
	//$sql = "SELECT IdUO, Nome FROM INF_unidades WHERE (AtivoSite = 1) ORDER BY Nome";
	$sql = "SELECT Id, ID_Unidade, Eixo, Titulo, Autor, Formacao, cast(Artigo as VARCHAR(max)) as Artigo FROM INF_artigos WHERE Id = '".trim($_POST['id'])."'";
	$query = mssql_query($sql, $db_alpha);
	while($res = mssql_fetch_assoc($query)) {
		$res['Id'] = utf8_encode($res['Id']);
		$res['ID_Unidade'] = utf8_encode($res['ID_Unidade']);
		$res['Eixo'] = utf8_encode($res['Eixo']);
		$res['Titulo'] = utf8_encode($res['Titulo']);
		$res['Autor'] = utf8_encode($res['Autor']);
		$res['Formacao'] = utf8_encode($res['Formacao']);
		$res['Artigo'] = utf8_encode($res['Artigo']);
		
		echo json_encode($res);
	}
}

if($_POST['acao']=="inserir"){
	
	//--- Para balcão
	//$balcao = "NULL";
	//if(isset($_POST['balcao']) && !empty($_POST['balcao'])) $balcao = $_POST['balcao'];
	
	$unidade = utf8_decode($_POST['unidade']);
	$enquete = utf8_decode($_POST['enquete']);
	$pergunta = utf8_decode($_POST['pergunta']);
	$respostas = explode("%", utf8_decode($_POST['respostas']));
	//$formacao = utf8_decode($_POST['formacao']);
	//$artigo = utf8_decode($_POST['artigo']);
	
	if(isset($_POST['balcao']) && !empty($_POST['balcao'])) {
		$sql = "INSERT INTO CAD_enquete(titulo, unidade, ativo, balcao) VALUES ('".$enquete."','".$unidade."', 1, '".$_POST['balcao']."')";
	} else {
		$sql = "INSERT INTO CAD_enquete(titulo, unidade, ativo) VALUES ('".$enquete."','".$unidade."', 1)";
	}
	if(mssql_query($sql, $db_alpha)) {
		//echo json_encode('Artigo inserido com sucesso!');
		
		if(isset($_POST['balcao']) && !empty($_POST['balcao'])) {
			$buscaEnq = "SELECT id FROM CAD_enquete WHERE balcao = '".$_POST['balcao']."' ORDER BY id DESC";
		} else {
			$buscaEnq = "SELECT id FROM CAD_enquete WHERE unidade = '".$unidade."' ORDER BY id DESC";
		}
		$queryEnq = mssql_query($buscaEnq, $db_alpha);
		
		$id_enquete = '';
		if($resEnq = mssql_fetch_assoc($queryEnq)) {
			$id_enquete = $resEnq['id'];
		}
		
		$sqlPergunta = "INSERT INTO CAD_enquete_pergunta(id_enquete, pergunta) VALUES ('".$id_enquete."','".$pergunta."')";
		if(mssql_query($sqlPergunta, $db_alpha)) {
			
			$buscaPerg = "SELECT id FROM CAD_enquete_pergunta WHERE id_enquete = '".$id_enquete."' ORDER BY id DESC";
			$queryPerg = mssql_query($buscaPerg, $db_alpha);
			
			$id_pergunta = '';
			if($resPerg = mssql_fetch_assoc($queryPerg)) {
				$id_pergunta = $resPerg['id'];
			}
			
			$ok = 0;
			for($i = 0; $i < count($respostas); $i++) {
				$sqlResposta = "INSERT INTO CAD_enquete_resposta(id_pergunta, resposta) VALUES ('".$id_pergunta."','".trim($respostas[$i])."')";
				if(!mssql_query($sqlResposta)) {
					echo json_encode('ERRO ao inserir a RESPOSTA da enquete! - '.mssql_get_last_message());
				} else {
					$ok++;
				}
			}
			
		} else {
			echo json_encode('ERRO ao inserir a PERGUNTA da enquete! - '.mssql_get_last_message());
		}
		
	} else {
		echo json_encode('ERRO ao inserir a enquete! - '.mssql_get_last_message());
	}
	
	if($ok > 0) {
		echo json_encode('Enquete inserida com sucesso!');
	} else {
		echo json_encode('ERRO GERAL ao inserir a enquete! - '.mssql_get_last_message());
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
	if(mssql_query($sql, $db_alpha)) {
		echo json_encode('Artigo atualizado com sucesso!');
	} else {
		echo json_encode('ERRO ao atualizar o artigo! - '.mssql_get_last_message());
	}

}

if($_POST['acao']=="excluir"){
	//echo 'Aqui! - '.$_POST['id'];
	$sql = "DELETE FROM INF_artigos WHERE Id = '".$_POST['id']."'";
	if(mssql_query($sql, $db_alpha)) {
		//echo json_encode('Artigo excluído com sucesso!');
		echo utf8_encode('Artigo excluído com sucesso!');
	} else {
		//echo json_encode('ERRO ao excluir o artigo! - '.mssql_get_last_message());
		echo utf8_encode('ERRO ao excluir o artigo! - '.mssql_get_last_message());
	}

}

if($_POST['acao']=="ativar_desativar"){
	
	$id_enquete = utf8_decode($_POST['id_enquete']);
	$ativo = utf8_decode($_POST['ativo']);
	
	$sql = "UPDATE CAD_enquete SET ativo = '".$ativo."' WHERE id = '".$_POST['id_enquete']."'";
	//echo $sql;
	if(mssql_query($sql, $db_alpha)) {
		echo json_encode('ok');
	} else {
		echo json_encode('erro');
	}

}

if($_POST['acao'] == 'busca_balcao') {
	$sql = "SELECT cod, Titulo FROM INF_balcoes WHERE codigounidade = '".$_POST['unidade']."' ORDER BY Titulo ASC";
	$query = mssql_query($sql, $db_alpha);
	echo '<option value="NULL">Selecione...</option>';
	while($res = mssql_fetch_assoc($query)) {
		echo '<option value="'.trim(utf8_encode($res['cod'])).'">'.trim(utf8_encode($res['Titulo'])).'</option>';
	}
}

?>
