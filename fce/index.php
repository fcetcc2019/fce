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
        <script src="../highcharts-6.0.4/code/highcharts.js"></script>
		<script src="../highcharts-6.0.4/code/modules/exporting.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <!--<script src="../bootstrap/ckeditor/ckeditor.js"></script>-->
        <script src="js/scripts.js"></script>
        
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
		.barra-grafico {
			position: relative;
			float: left;
			background-color: #aaa;
			height: 100%;
			width: 10%;
			bottom: -100%;
			margin-left: 2%;
			margin-right: 2%;
		}
		.barra-grafico:hover {
			cursor: pointer;
			box-shadow: 0 0 6px #403f3f;
		}
        </style>
 
    </head>
    <body>
    <?php
	ini_set('error_reporting',E_ALL);
	
	include("../connection_alpha_homologacao.php");
	
	if(!isset($_GET['ativoInativo'])) {
		$_GET['ativoInativo'] = 'ativas'; 
	}
	
	?>
        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container-fluid">
            <!-- CLASSE PARA DEFINIR UMA LINHA -->
            <div class="row-fluid">
                <ul class="nav nav-tabs">
                    <li id="enquetes" class="aba active">
                        <a href="javascript:">Enquetes</a>
                    </li>
                    <li id="relatorios" class="aba">
                        <a href="graficos.php">Relat�rios</a>
                    </li>
                </ul>
            </div>
            
            <div class="row-fluid secoes enquetes">
            	<div class="row-fluid">
                	<h1>
                        Enquetes
                        <small>Listagem</small>
                    </h1>
                    <hr>
                </div>
                <!-- COLUNA OCUPANDO 12 ESPA�OS NO GRID -->
                <div class="row-fluid">
                    <div class="row-fluid">
                        <form class="form-inline" action="index.php">
                        	<div class="span2 bt-insere-enquete">
                        		<a id="bt-novaenquete" class="btn btn-info btn-large" href="novaenquete.php">Inserir enquete</a>
                                <!--<input type="hidden" name="usuario" id="usuario" value="<?php //echo $usuario; ?>" />-->
                                <input type="hidden" id="usuario" value="<?php echo $usuario; ?>" />
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
                                        
                                        
                                        if($temWhere) {
                                            $where = ' WHERE '.rtrim($where, "AND ");
                                        }
                                        
                                        
                                        
                                        $unidades = "SELECT IdUO, Nome FROM INF_unidades WHERE AtivoSite = 1 ORDER BY Nome";
                                        $q = mysqli_query($db_alpha, $unidades);
                                        while($r = mysqli_fetch_assoc($q)) {
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
						
						$busca = "SELECT id, titulo, unidade, (SELECT u.Nome FROM INF_unidades u WHERE u.IdUO = unidade) as nomeunidade, balcao, (SELECT b.Titulo FROM INF_balcoes b WHERE b.cod = balcao) as nomebalcao, publicado, ativo FROM CAD_enquete ".$where." ORDER BY id DESC";
						
						//echo $busca;
						$query = mysqli_query($busca, $db_alpha);
						echo '<table class="table table-hover">';
						//echo '<tr class="navbar-inner" style="height:45px;">';
						echo '<thead>';
						echo '<tr style="height:45px;">';
						echo '<td>ID</td>
							  <td>TÍTULO</td>
							  <td>PERGUNTA</td>
							  <td>UNIDADE</td>
							  <td>BALCÃO</td>
							  <td>PUBLICADO</td>
							  <td>ATIVO</td>';
						echo '</tr>';
						echo '</thead><tbody>';
						
						$classe_ativo = "";
						$classe_publicado = "";
						$icon_publicado = "";
						
						while($res = mysqli_fetch_assoc($query)) {
							$id_enquete = $res['id'];
							
							if($res['ativo'] == 1) {
								$classe_ativo = 'btn-primary';
							} else {
								$classe_ativo = 'btn-danger';
							}
							
							if($res['publicado'] == "1") {
								//$classe_publicado = 'btn-primary';
								//$classe_publicado = 'label-success';
								$classe_publicado = '';
								$icon_publicado = '<img src="img/check-64-icon.png" style="width:22px" title="Publicada" />';
								//$icon_publicado = "icon-ok";
							} else if($res['publicado'] == "0") {
								//$classe_publicado = 'btn-danger';
								//$classe_publicado = 'label-important';
								$classe_publicado = '';
								$icon_publicado = '<img src="img/delete-icon.png" style="width:15px" title="N�o publicada" />';
								//$icon_publicado = "icon-ban-circle";
							} else  if($res['publicado'] == ""){
								$classe_publicado = '';
								//$icon_publicado = "Nenhuma a��o tomada com a enquete";
								$icon_publicado = '<img src="img/icon-exclamation.png" style="width:22px" title="Nenhuma a��o tomada" />';
							}
							
							/*echo '<script>alert("'.$res['publicado'].'")</script>';*/
							
							$busca = "SELECT id, id_enquete, pergunta FROM CAD_enquete_pergunta WHERE id_enquete = '".$id_enquete."'";
							$query2 = mysqli_query($db_alpha, $busca);
							if($res2 = mysqli_fetch_assoc($query2)) {
								$pergunta = $res2['pergunta'];
							}
														
							
							echo '
								<tr style="height:45px;" publicado="'.$res['publicado'].'">
									<td class="linha" id_enquete="'.$id_enquete.'">'.$id_enquete.'</td>
									<td class="linha" id_enquete="'.$id_enquete.'">'.$res['titulo'].'</td>
									<td class="linha" id_enquete="'.$id_enquete.'">'.$pergunta.'</td>
									<td class="linha" id_enquete="'.$id_enquete.'">'.$res['nomeunidade'].' ('.$res['unidade'].')</td>
									<td class="linha" id_enquete="'.$id_enquete.'">'.$res['nomebalcao'].' ('.$res['balcao'].')</td>
									<td><span id="bt-publicar-'.$res['id'].'" class="'.$classe_publicado.' bt-publicar" publicado="'.$res['publicado'].'" id_enquete="'.$res['id'].'" style="padding:8px;">'.$icon_publicado.'</span></td>
									<td><button type="button" id="bt-ativar-'.$res['id'].'" class="btn '.$classe_ativo.' bt-ativar" ativo="'.$res['ativo'].'" id_enquete="'.$res['id'].'" id_unidade="'.$res['unidade'].'" id_balcao="'.$res['balcao'].'"><i class="icon-off icon-white"></i></button></td>
								</tr>
							';
						}
						echo '</tbody></table>';
						?>
                    </div>
                    
                </div>
            </div>
            
		</div>
        
        <div class="container-fluid">
            <!--<div class="modal fade modal-enquete" id="modal-enquete" style="width:850px; margin-left:-425px; display:none;">-->
            <div class="modal fade modal-enquete" id="modal-enquete" style="display:none; width:610px; margin-left:-305px;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Enquete</h4>
                        </div>
                        <div class="modal-body">
                            <div id="alert-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <span id="msg-cadastraproduto"></span>
                            </div>
                            <div class="row-fluid">
                                <!--<div class="span12 pergunta"></div>
                                <div class="respostas"></div>-->
                                <div class="row-fluid pergunta"></div>
                                <div class="row-fluid respostas"></div>
                                <div class="row-fluid extrato_respostas"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <!--<button type="button" id="bt-publicaEnquete" class="bt-publicaEnquete btn btn-primary span2" id-enquete="" disabled>Publicar <i class="icone-publicar icon-edit"></i></button>
                            <button type="button" id="bt-naoPublicaEnquete" class="bt-naoPublicaEnquete btn btn-danger span2" id-enquete="" disabled>N�o publicar <i class="icone-publicar icon-edit"></i></button>-->
                            <button type="button" id="bt-alteraEnquete" class="bt-alteraEnquete btn btn-primary span2" id-enquete="">Alterar enquete <i class="icone-publicar icon-edit"></i></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        
        <div class="container-fluid">
            <!--<div class="modal fade modal-enquete" id="modal-enquete" style="width:850px; margin-left:-425px; display:none;">-->
            <div class="modal fade modal-enquete-justificativa" id="modal-enquete-justificativa" style="display:none; width:610px; margin-left:-305px;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Justificativa</h4>
                        </div>
                        <div class="modal-body">
                            <div id="alert-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <span id="msg-cadastraproduto"></span>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">Por que a enquete n�o deve ser publicada?</div>
                                <div class="span12">
                                	<textarea name="justificativa" id="justificativa" style="width:93%; height:100px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="button" id="bt-salvaJustificativa" class="bt-salvaJustificativa btn btn-primary span2" id-enquete="">Salvar <i class="icone-publicar icon-edit"></i></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        
        <div class="container-fluid">
            <!--<div class="modal fade modal-enquete" id="modal-enquete" style="width:850px; margin-left:-425px; display:none;">-->
            <div class="modal fade modal-enquete-altera" id="modal-enquete-altera" style="display:none; width:610px; margin-left:-305px;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Alterar enquete</h4>
                        </div>
                        <div class="modal-body">
                            <div id="alert-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <span id="msg-cadastraproduto"></span>
                            </div>
                            <div class="row-fluid campos">
                                <!--<div class="span12">Pergunta:</div>
                                <div class="span12">
                                	<textarea name="justificativa" id="justificativa" style="width:93%; height:100px;"></textarea>
                                </div>-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="button" id="bt-altera-enquete" class="bt-altera-enquete btn btn-primary span2" id-enquete="">Salvar <i class="icone-publicar icon-edit"></i></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        
    </body>
</html>