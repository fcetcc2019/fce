<?php
ini_set("display_errors", 1);
//include("../connection.php");
require_once('../../classes/enqueteModel.php');
//die('Aqui 1');
$enquete = new Enquete();
/*
$lista = $enquete->listaEnquetes();

echo '<pre>';
var_dump($lista);
echo '</pre>';
*/



if($_SERVER['REQUEST_METHOD'] === 'GET') {
	
	if($_GET['acao'] == 'carrega_grafico_unidade'){
		
		$id_unidade = $_GET['id_unidade'];
		$enquetes_unidade = $enquete->listaEnquetesDaUnidade($id_unidade);
		echo json_encode($enquetes_unidade);
		
	}
	
	if($_GET['acao'] == 'total_unidade_total_geral'){
		
		$id_unidade = $_GET['id_unidade'];
		//echo $id_unidade;
		//exit;

		//$enquetes_unidade = $enquete->listaEnquetesDaUnidade($id_unidade);
		$enquetes_unidade = $enquete->totalUnidadeSobreTotalGeral($id_unidade);
		//die($enquetes_unidade);

		settype($enquetes_unidade[0]['total_unidade'], 'float');
		settype($enquetes_unidade[0]['total_geral'], 'float');

		$total_unidade = $enquetes_unidade[0]['total_unidade'];
		$total_geral = $enquetes_unidade[0]['total_geral'];

		//echo json_encode($total_unidade);
		//echo json_encode($total_geral);

		$porcentagem_unidade = ($total_unidade * 100.0) / $total_geral;
		$enquetes_unidade[0]['porcentagem_unidade'] = round($porcentagem_unidade, 2);

		//$enquetes_unidade[0]['porcentagem_unidade'] = utf8_encode($enquetes_unidade[0]['porcentagem_unidade']);
		//$enquetes_unidade[0]['total_unidade'] = utf8_encode($enquetes_unidade[0]['total_unidade']);
		//$enquetes_unidade[0]['total_geral'] = utf8_encode($enquetes_unidade[0]['total_geral']);

		//echo json_encode($porcentagem_unidade);
		echo json_encode($enquetes_unidade);
		//echo $enquetes_unidade;
		
	}
	
} else if($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	if($_POST['acao'] == 'salvaresposta') {

		var_dump($_POST);

		$salvaresposta = $enquete->SalvaRespostas($_POST);

		echo $salvaresposta;

	}
	
}
?>
