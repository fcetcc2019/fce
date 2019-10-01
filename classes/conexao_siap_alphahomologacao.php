<?php

class ConexaoAries {
	
	private $usuario = 'Writer';
	private $senha = 'Writer';
	private $servidor = 'ALPHA\\HOMOLOGACAO';
	private $banco = '';
	private $db = 'Siap';
	private $link = '';
	
	function __construct() {
		
		$this->link = mssql_connect($this->servidor, $this->usuario, $this->senha) or die ("ERRO de conexão! ALPHA\HOMOLOGACAO - Siap -> ".mssql_get_last_message());
		$this->banco = mssql_select_db($this->db, $this->link) or die ("ERRO ao selecionar o banco! ALPHA\HOMOLOGACAO - Siap -> ".mssql_get_last_message());
		
	}
	
	function query($consulta) {
		
		if($resultado = mssql_query($consulta)) {
			return $resultado;
		} else {
			return mssql_get_last_message();
		}
		
	}
	
	function __destruct() {
		
		mssql_close($this->link);
		
	}
	
}
  

?>