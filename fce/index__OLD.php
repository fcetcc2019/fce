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
        </style>
 
    </head>
    <body>
    
    <?php
	//ini_set('error_reporting',E_ALL);
	//include("../connection.php");
	include("../connection_alpha_homologacao.php");
	
	
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
                        <form class="form-inline" action="index.php">
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
                                        $q = mssql_query($unidades, $db_alpha);
                                        while($r = mssql_fetch_assoc($q)) {
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
						
						$busca = "SELECT id, titulo, unidade, (SELECT p.pergunta FROM CAD_enquete_pergunta p WHERE p.id_enquete = id) as pergunta, (SELECT u.Nome FROM INF_unidades u WHERE u.IdUO = unidade) as nomeunidade, balcao, (SELECT b.Titulo FROM INF_balcoes b WHERE b.cod = balcao) as nomebalcao, ativo FROM CAD_enquete ".$where." ORDER BY id DESC";
						
						echo $busca;
						$query = mssql_query($busca, $db_alpha);
						echo '<table class="table"><tbody>';
						echo '<tr class="navbar-inner" style="height:45px;">';
						echo '<td>ID</td>
							  <td>TÍTULO</td>
							  <td>UNIDADE</td>
							  <td>BALCÃO</td>
							  <td>ATIVO</td>';
						echo '</tr>';
						
						$classe_btn = "";
						
						while($res = mssql_fetch_assoc($query)) {
							if($res['ativo'] == 1) {
								$classe_btn = 'btn-primary';
							} else {
								$classe_btn = 'btn-danger';
							}
							echo '
								<tr class="navbar-inner" style="height:45px;">
									<td>'.$res['id'].'</td>
									<td>'.$res['titulo'].'</td>
									<td>'.$res['nomeunidade'].' ('.$res['unidade'].')</td>
									<td>'.$res['nomebalcao'].' ('.$res['balcao'].')</td>
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
        <div class="container-fluid">
            <div class="modal fade modal-artigos" id="modal-artigos" style="width:850px; margin-left:-425px;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Novo produto</h4>
                        </div>
                        <div class="modal-body">
                            <!--<div class="alert alert-success alert-dismissable" id="sucesso-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Produto cadastrado com sucesso!
                            </div>
                            <div class="alert alert-danger alert-dismissable" id="erro-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>-->
                            <div id="alert-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <span id="msg-cadastraproduto"></span>
                            </div>
                            <div class="row-fluid">
                                <div class="span10">
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <input type="hidden" name="acao" id="acao" value="" />
                                            <input type="hidden" name="id_artigo" id="id_artigo" value="" />
                                            <label>Unidade:</label>
                                            <!--<input type="text" placeholder="Digite algo...">-->
                                            <select name="unidade" id="unidade" class="span5">
                                                <option value="selecione">Selecione...</option>
                                                <?php
                                                $sql = "SELECT IdUO, Nome FROM INF_unidades WHERE AtivoSite = 1";
                                                $query = mssql_query($sql, $db_alpha);
                                                while($res = mssql_fetch_assoc($query)) {
                                                    echo '<option value="'.$res['IdUO'].'">'.$res['Nome'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="span5">
                                            <label>Área:</label>
                                            <select name="area" id="area" class="span5">
                                                <option value="selecione">Selecione...</option>
                                                <?php
                                                $sqlAreas = "SELECT DISTINCT codigosegmento, descricaosegmento FROM dbo.CUR_cursos WHERE (descricaosegmento IS NOT NULL) ORDER BY descricaosegmento";
                                                $queryAreas = mssql_query($sqlAreas, $db_alpha);
                                                while($resAreas = mssql_fetch_assoc($queryAreas)) {
                                                    echo '<option value="'.$resAreas['descricaosegmento'].'">'.$resAreas['descricaosegmento'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <label>Título:</label>
                                            <input type="text" name="titulo" id="titulo" class="span5" placeholder="Título do artigo...">
                                        </div>
                                        <div class="span5">
                                            <label>Autor:</label>
                                            <input type="text" name="autor" id="autor" class="span5" placeholder="Autor do artigo...">
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span10">
                                            <label>Formação:</label>
                                            <input type="text" name="formacao" id="formacao" class="span5" placeholder="Formação do autor...">
                                            <label>Artigo:</label>
                                            <textarea name="artigo" id="artigo"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="button" id="bt-salvar" class="btn btn-primary span2">Salvar <i class="icon-ok icon-white"></i></button>
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