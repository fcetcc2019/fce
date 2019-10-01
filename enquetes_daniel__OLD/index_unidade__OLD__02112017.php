<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>::: Enquetes :::</title> 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
 
 		<script type="text/javascript" src="../jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../bootstrap/ckeditor/ckeditor.js"></script>
        <script src="scripts.js"></script>
        
        <style>
        .filtro {
			background-color: #f1f5fb;
			padding: 1%;
			text-align: center;
		}
		.bt-insere-enquete {
			padding: 0.5%;
			text-align: center;
		}
		/*.input-radio {
			margin-bottom: 3px;
		}*/
		.div-interna-filtro1 {
			position: relative;
			float: left;
			width: 10%;
		}
		.div-interna-filtro2 {
			position: relative;
			float: left;
			width: 36%;
			margin: 0% 2% 0% 2%;
			text-align: left;
		}
		.div-interna-filtro3 {
			position: relative;
			float: left;
			width: 10%;
		}
		.linha:hover {
			cursor: pointer;
			color: #039;
			text-decoration: underline;
		}
        </style>
 
    </head>
    <body>
    <?php
	//ini_set('error_reporting',E_ALL);
	//include("../connection.php");
	include("../connection_aries.php");
	include("../connection_alpha_homologacao.php");
	//include("../../Intranet/connection_aries.php");
	
	/*
	$usuario = trim(strtoupper(substr($_SERVER["AUTH_USER"],8,100)));
	
	$sql20 = "SELECT * FROM stUsuario where IdUsuario = '".$usuario."'";
	$qsql20 = mssql_query($sql20, $db2);
	if($res0 = mssql_fetch_assoc($qsql20)){
		$unidade = trim($res0['IdUO']);
		$email = trim($res0['email']);
		$nomeUsusario = trim($res0['NOME']);
	}
	*/
	
	$unidade = "6";
	$unidade = trim($unidade);
	
	echo $unidade;

	
	if(!isset($_GET['ativoInativo'])) {
		$_GET['ativoInativo'] = 'ativas'; 
	}
	
	?>
        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container-fluid">
            <!-- CLASSE PARA DEFINIR UMA LINHA -->
            <ul class="nav nav-pills">
            	<li class="active pull-right">
                	<a href="http://admin_site.senacrs.com.br/index.asp">Voltar</a>
            	</li>
            </ul>
            <h1>
            	Enquetes
                <small>Listagem</small>
            </h1>
            <hr>
            <div class="row-fluid">
                <!-- COLUNA OCUPANDO 10 ESPAÇOS NO GRID -->
                <div class="span12">
                    <div class="row-fluid">
                        <form class="form-inline" action="index_unidade.php">
                        	<div class="span2 bt-insere-enquete">
                        		<a id="bt-novaenquete" class="btn btn-info btn-large" href="novaenquete.php">Inserir enquete</a>
                            </div>
                            <div class="span10 filtro">
                            	<div class="div-interna-filtro1">Filtro:</div>
                                <div class="div-interna-filtro2">
                                    <select name="unidadeBusca" id="unidadeBusca" class="span10">
                                        <option value="0">TODAS AS UNIDADES...</option>
                                        <?php
                                        $where = '';
                                        $temWhere = false;
                                        $unidade = '';
                                        $ativo = '';
                                        $inativo = '';
										$pergunta = '';
										$id_enquete = '';
                                        
                                        if(isset($_GET['unidadeBusca']) && !empty($_GET['unidadeBusca']) && $_GET['unidadeBusca'] != '0') {
                                            //$unidade = $_GET['unidadeBusca'];
                                            //$unidade = $_GET['unidadeBusca'];
                                            $where .= " unidade = '".$_GET['unidadeBusca']."' AND ";
                                            $temWhere = true;
                                        }
                                        if(isset($_GET['ativoInativo']) && $_GET['ativoInativo'] == 'ativas') {
                                            //$ativo = " ativo = 1 AND ";
                                            $where .= " ativo = 1 AND ";
                                            $temWhere = true;
                                        }
                                        if(isset($_GET['ativoInativo']) && $_GET['ativoInativo'] == 'inativas') {
                                            //$ativo = " ativo = 0 AND ";
                                            $where .= " ativo = 0 AND ";
                                            $temWhere = true;
                                        }
                                        if(isset($_GET['ativoInativo']) && $_GET['ativoInativo'] == 'todas') {
                                            $where .= "";
                                            //$temWhere = true;
                                        }
										
										if(isset($unidade) && trim($unidade) != 'AM') {
                                            //$ativo = " ativo = 0 AND ";
                                            $where .= " unidade = '".$unidade."' AND ";
                                            $temWhere = true;
                                        }                                        
										
                                        
                                        if($temWhere) {
                                            $where = ' WHERE '.rtrim($where, "AND ");
                                        }
                                        
                                        $unidades = "SELECT IdUO, Nome FROM INF_unidades WHERE AtivoSite = 1 ORDER BY Nome";
                                        $q = mysql_query($unidades, $db_alpha);
                                        while($r = mysql_fetch_assoc($q)) {
                                            //if($r['IdUO'] == $unidade) {
                                            if($r['IdUO'] == $_GET['unidadeBusca']) {
                                                echo '<option value="'.$r['IdUO'].'" selected="selected">'.$r['Nome'].'</option>';
                                            } else {
                                                echo '<option value="'.$r['IdUO'].'">'.$r['Nome'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="div-interna-filtro2">
                                    <label class="checkbox inline">
                                        <input type="radio" name="ativoInativo" id="ativas" style="margin-bottom:3px;" value="ativas"<?php if(isset($_GET['ativoInativo']) && $_GET['ativoInativo'] == 'ativas') { echo ' checked="checked"'; } ?>> Ativas
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="radio" name="ativoInativo" id="inativas" style="margin-bottom:3px;" value="inativas"<?php if(isset($_GET['ativoInativo']) && $_GET['ativoInativo'] == 'inativas') { echo ' checked="checked"'; } ?>> Inativas
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="radio" name="ativoInativo" id="todas" style="margin-bottom:3px;" value="todas"<?php if(isset($_GET['ativoInativo']) && $_GET['ativoInativo'] == 'todas') { echo ' checked="checked"'; } ?>> Todas
                                    </label>
                                </div>
                                <div class="div-interna-filtro3">
                                	<button type="submit" class="btn span12">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div><br />
                    <div class="row-fluid">
                    	<?php
						/*if(!empty($unidade)) {
							$busca = "SELECT id, titulo, unidade, (SELECT u.Nome FROM INF_unidades u WHERE u.IdUO = unidade) as nomeunidade, balcao, (SELECT b.Titulo FROM INF_balcoes b WHERE b.cod = balcao) as nomebalcao, ativo FROM CAD_enquete WHERE unidade = '".$unidade."' ORDER BY id DESC";
						} else {
                        	$busca = "SELECT id, titulo, unidade, (SELECT u.Nome FROM INF_unidades u WHERE u.IdUO = unidade) as nomeunidade, balcao, (SELECT b.Titulo FROM INF_balcoes b WHERE b.cod = balcao) as nomebalcao, ativo FROM CAD_enquete ORDER BY id DESC";
						}*/
						
						$busca = "SELECT *, (SELECT u.Nome FROM INF_unidades u WHERE u.IdUO = unidade) as nomeunidade, (SELECT b.Titulo FROM INF_balcoes b WHERE b.cod = balcao) as nomebalcao FROM CAD_enquete ".$where." ORDER BY id DESC";
						
						echo $unidade;
						
						//echo $busca;
						$query = mysql_query($busca, $db_alpha);
						echo '<table class="table"><tbody>';
						echo '<tr class="navbar-inner" style="height:45px;">';
						echo '<td>ID</td>
							  <td>TÍTULO</td>
							  <td>PERGUNTA</td>
							  <td>UNIDADE</td>
							  <td>BALCÃO</td>
							  <td>ATIVO</td>';
						echo '</tr>';
						
						$classe_btn = "";
						
						while($res = mysql_fetch_assoc($query)) {
							$id_enquete = $res['id'];
							
							if($res['ativo'] == 1) {
								$classe_btn = 'btn-primary';
							} else {
								$classe_btn = 'btn-danger';
							}
							
							$busca = "SELECT id, id_enquete, pergunta FROM CAD_enquete_pergunta WHERE id_enquete = '".$id_enquete."'";
							$query2 = mysql_query($busca, $db_alpha);
							if($res2 = mysql_fetch_assoc($query2)) {
								$pergunta = $res2['pergunta'];
							}
														
							
							echo '
								<tr class="navbar-inner" style="height:45px;">
									<td class="linha" id_enquete="'.$id_enquete.'">'.$id_enquete.'</td>
									<td class="linha" id_enquete="'.$id_enquete.'">'.$res['titulo'].'</td>
									<td class="linha" id_enquete="'.$id_enquete.'">'.$pergunta.'</td>
									<td class="linha" id_enquete="'.$id_enquete.'">'.$res['nomeunidade'].' ('.$res['unidade'].')</td>
									<td class="linha" id_enquete="'.$id_enquete.'">'.$res['nomebalcao'].' ('.$res['balcao'].')</td>
									<td><button type="button" id="bt-ativar-'.$res['id'].'" class="btn '.$classe_btn.' bt-ativar" ativo="'.$res['ativo'].'" id_enquete="'.$res['id'].'"><i class="icon-off icon-white"></i></button></td>
								</tr>
							';
						}
						echo '</tbody></table>';
						?>
                    </div>
                </div>
            </div>
		</div>
        
    </body>
</html>