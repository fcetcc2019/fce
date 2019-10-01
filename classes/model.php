<?php
require_once('conexao_alpha_homologacao.php');

class Model {
	
	private $conexao;
	private $idEnquete;
	
	function __construct() {
		
		$this->conexao = new ConexaoAlpha();
		$this->idEnquete = 0;
		
	}
	
	function listaResultados() {
		
		$consulta = $this->conexao->query("SELECT * FROM CUR_cursos WHERE codigosegmento = 8 AND (codigounidade = 83) AND (cursomercado = 1)");
		$dados = array();
		
		while($resultado = mssql_fetch_assoc($consulta)) {
			$dados[] = $resultado;
		}
		
		return $dados;
	}

	function listaEnqueteAtiva($id_unidade) {
		
		$consulta = $this->conexao->query("SELECT p.id_enquete, e.titulo, p.pergunta, r.id, r.resposta FROM CAD_enquete_resposta r INNER JOIN CAD_enquete_pergunta p ON r.id_pergunta = p.id INNER JOIN CAD_enquete e ON p.id_enquete = e.id WHERE e.ativo = 1 AND e.unidade = ".$id_unidade." ORDER BY e.id DESC");
		
		$dados = array();
		$id_enquete = 0;
		
		while($resultado = mssql_fetch_assoc($consulta)) {
			$dados[] = $resultado;
			$id_enquete = $resultado['id_enquete'];
		}

		$this->idEnquete = $id_enquete;
		
		return $dados;
	}

	function listaDemaisCampos() {

		if($this->idEnquete != 0)
			$consulta = $this->conexao->query("SELECT * FROM CAD_enquete_contato_demaiscampos WHERE id_enquete = ".$this->idEnquete);
			
			$dados = array();
			$id_enquete = 0;
			
			while($resultado = mssql_fetch_assoc($consulta)) {
				$dados[] = $resultado;
			}

		} else {
			$dados = $this->idEnquete;
		}
		
		return $dados;

	}
	
}

?>