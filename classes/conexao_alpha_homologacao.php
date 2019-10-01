<?php
  
class ConexaoAlpha {
	
	//private $usuario = 'Writer';
	private $usuario = 'root';
	//private $senha = 'Writer';
	private $senha = '';
	//private $servidor = 'ALPHA\\HOMOLOGACAO';
	private $servidor = 'localhost:3308';
	private $banco = '';
	private $db = 'PortalSenacRS';
	private $link = '';
	
	function __construct() {
		
		$this->link = mysql_connect($this->servidor, $this->usuario, $this->senha) or die ("ERRO de conexão! -> ".mysql_error());
		$this->banco = mysql_select_db($this->db, $this->link) or die ("ERRO ao selecionar o banco! -> ".mysql_error());
		
	}
	
	function query($consulta) {
		
		if($resultado = mysql_query($consulta, $this->link)) {
			return $resultado;
		} else {
			return mysql_error();
		}
		
	}
	
	function __destruct() {
		
		mysql_close($this->link);
		
	}
	
}
  

?>