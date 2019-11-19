<?php

/*

MIME Types: https://www.iana.org/assignments/media-types/media-types.xhtml#application
* csv - text/csv
* excel - application/vnd.ms-excel
* json - application/json

*/

include("../connection_alpha_homologacao.php");

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

	echo '<pre>';

	//Todas as respostas
	var_dump(mysqli_fetch_all($query, MYSQLI_ASSOC));

	/* while($resultado = mysqli_fetch_assoc($query))
		var_dump($resultado); */

	echo '</pre>';

	die();

	// id + 20 caracteres da pergunta, com espaços trocados por underscore
	$nomeDoArquivo = $_GET["id"]."_".str_replace(" ", "_", substr($resultado[0]["pergunta"], 0, 20));

	// Resultado formatado de acordo com o formato escolhido
	$conteudo = null;

	$contentTypeHeader = "";

	switch ($_GET['formato']) {
		
		case 'csv':

			$contentTypeHeader = "text/csv";

			$extensao = ".csv";

			$conteudo = implode(",", array_keys($resultado[0]));

			foreach($resultado as $valor) {
				
				$conteudo .= implode(",", $valor)."\n";

			}

			break;

		case 'excel':
			
			$contentTypeHeader = "application/vnd.ms-excel";

			$extensao = ".xslx";

			$conteudo = '<table><tr><th>'.implode("</th><th>", array_keys($resultado[0])).'</th></tr>';

			foreach ($resultado as $valor) {
				$conteudo = '<tr><td>'.implode("</td><td>", $valor).'</td></tr>';
			}

			$conteudo .= '</table>';

			break;

		case 'json':
			
			$contentTypeHeader = "application/json";

			$extensao = ".json";

			$conteudo = json_encode($resultado);

			break;

		default:
			die("Erro: Formato não informado!");

	}

	/* // Instrui o navegador a baixar o $conteudo como um arquivo no formato escolhido
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	//header("Content-Type: ".$contentTypeHeader);
	header("Content-Type: text/plain");
	//header("Content-Disposition: attachment; filename=\"".$nomeDoArquivo.$extensao."\"" );
	header("Content-Description: Respostas de Enquete - FCE" ); */
	
	// Envia o conteúdo do arquivo
	//echo $conteudo;

}