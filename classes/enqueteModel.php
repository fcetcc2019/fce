<?php
//ini_set("display_errors", 1);
require_once('conexao_alpha_homologacao.php');

class Enquete {
	
	private $conexao;
	private $idEnquete;
	
	function __construct() {
		
		$this->conexao = new ConexaoAlpha();
		$this->idEnquete = 0;
		
	}
	
	function listaEnquetes() {
		
		$consulta = $this->conexao->query("SELECT * FROM CAD_enquete");
		$dados = array();
		
		while($resultado = mysqli_fetch_assoc($consulta)) {
			$dados[] = $resultado;
		}
		
		return $dados;
	}
	
	function listaEnquetesDaUnidade($iduo) {
		
		$consulta = $this->conexao->query("SELECT * FROM CAD_enquete WHERE unidade = '".$iduo."'");
		$dados = array();
		
		while($resultado = mysqli_fetch_assoc($consulta)) {
			$dados[] = $resultado;
		}
		
		return $dados;
	}
	
	function totalUnidadeSobreTotalGeral($iduo) {
		
		$consulta = $this->conexao->query("SELECT (SELECT COUNT(*) total FROM CAD_enquete WHERE unidade = '".$iduo."') AS total_unidade, (
SELECT COUNT(*) total FROM CAD_enquete) AS total_geral");
		$dados = array();
		
		while($resultado = mysqli_fetch_assoc($consulta)) {
			//$resultado['total_unidade'] = utf8_encode($resultado['total_unidade']);
			//$resultado['total_geral'] = utf8_encode($resultado['total_geral']);

			$dados[] = $resultado;
		}
		
		return $dados;
	}

	function listaEnqueteAtiva($id_unidade) {
		
		//$consulta = $this->conexao->query("SELECT p.id_enquete, e.titulo, p.pergunta, r.id, r.resposta FROM CAD_enquete_resposta r INNER JOIN CAD_enquete_pergunta p ON r.id_pergunta = p.id INNER JOIN CAD_enquete e ON p.id_enquete = e.id WHERE e.ativo = 1 AND e.unidade = ".$id_unidade." ORDER BY e.id DESC");
		$consulta = $this->conexao->query("SELECT p.id_enquete, e.titulo, p.pergunta, r.id, r.resposta FROM CAD_enquete_resposta r INNER JOIN CAD_enquete_pergunta p ON r.id_pergunta = p.id INNER JOIN CAD_enquete e ON p.id_enquete = e.id WHERE e.ativo = 1 AND e.unidade = ".$id_unidade." ORDER BY r.id");
		
		$dados = array();
		$id_enquete = 0;
		
		while($resultado = mysqli_fetch_assoc($consulta)) { //---> Retorno para array
		//while($resultado = mysql_fetch_object($consulta)) { //---> Retorno para objeto
			$dados[] = $resultado;
			$id_enquete = $resultado['id_enquete']; //---> Forma de array
			//$id_enquete = $resultado->id_enquete; //---> Forma de objeto
		}

		$this->idEnquete = $id_enquete;
		
		return $dados;
	}

	function listaDemaisCampos() {

		if($this->idEnquete != 0) {
			$consulta = $this->conexao->query("SELECT * FROM CAD_enquete_contato_demaiscampos WHERE id_enquete = ".$this->idEnquete);
			
			$dados = array();
			$id_enquete = 0;
			
			while($resultado = mysqli_fetch_assoc($consulta)) {
				$dados[] = $resultado;
			}

		} else {
			$dados = $this->idEnquete;
		}
		
		return $dados;

	}
	
}

?>