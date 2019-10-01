<?php
require_once('conexao_siap_alphahomologacao.php');

class Usuario {
	
	private $conexao;
	private $usuario;
	
	function __construct() {
		
		$this->conexao = new ConexaoAries();
		$this->usuario = trim(strtoupper(substr($_SERVER["AUTH_USER"],8,100)));
		
	}
	
	function verificaUnidadeUsuario() {
		
		$consulta = $this->conexao->query("SELECT * FROM stUsuario WHERE IdUsuario = '".$this->usuario."'");
		//$dados = array();
		
		if($resultado = mssql_fetch_assoc($consulta)) {
			$unidade = trim(utf8_encode($resultado['IdUO']));
		}
		
		return $unidade;
	}
	
}

?>