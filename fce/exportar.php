<?php

include("../testeSession.php");

/*

MIME Types: https://www.iana.org/assignments/media-types/media-types.xhtml#application
* csv - text/csv
* json - application/json

*/

include("../connection_alpha_homologacao.php");

// Corrigir utf8 em string - recursivo em caso de array
// https://www.php.net/manual/en/function.json-last-error.php#115980
function corrigirUTF8($d) {

	if(is_array($d)) {

		foreach($d as $k => $v) {

			$d[$k] = corrigirUTF8($v);

		}

	} else if(is_string($d)) {

		return utf8_encode($d);

	}

	return $d;
}

if(isset($_GET['id']) && !empty($_GET['id'])) {

	$sql = "SELECT e.titulo, p.pergunta, r.resposta, e.unidade, rc.data, rco.valor, rc.nome, rc.ddd, rc.telefone, rc.dddcelular, rc.celular, rc.email
			FROM CAD_enquete_respostacliente rc
			inner join CAD_enquete_resposta r on r.id = rc.id_resposta
			inner join CAD_enquete_pergunta p on p.id = r.id_pergunta
			inner join CAD_enquete e on e.id = p.id_enquete
			left join CAD_enquete_respostacliente_outros rco on rco.id_resposta = r.id and rco.id_respostacliente = rc.id
			WHERE e.id = ".$_GET['id']."
			ORDER BY rc.data desc";
			
	$query = mysqli_query($db_alpha, $sql);

	//Todas as respostas
	$resultado = mysqli_fetch_all($query, MYSQLI_ASSOC);

	$resultado = corrigirUTF8($resultado);

	// id + 20 caracteres da pergunta, com espaços trocados por underscore
	$nomeDoArquivo = $_GET["id"]."_".str_replace(" ", "_", substr($resultado[0]["pergunta"], 0, 20));

	// Resultado formatado de acordo com o formato escolhido
	$conteudo = null;

	$contentTypeHeader = "";

	switch ($_GET['formato']) {
		
		case 'csv':

			$contentTypeHeader = "text/csv";

			$extensao = ".csv";

			$conteudo = implode(",", array_keys($resultado[0]))."\n";

			foreach($resultado as $resposta) {
				
				$conteudo .= implode(",", $resposta)."\n";

			}

			break;

		case 'json':
			
			$contentTypeHeader = "application/json";

			$extensao = ".json";

			$conteudo = json_encode($resultado);

			break;

		default:
			die("Erro: Formato não informado!");

	}

	// Instrui o navegador a baixar o $conteudo como um arquivo no formato escolhido
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Content-Type: ".$contentTypeHeader."; charset=utf-8");
	header("Content-Disposition: attachment; filename=\"".$nomeDoArquivo.$extensao."\"" );
	header("Content-Description: Respostas de Enquete - FCE" );
	
	// Envia o conteúdo do arquivo
	echo $conteudo;

}
