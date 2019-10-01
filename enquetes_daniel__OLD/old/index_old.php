<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>::: Enquetes :::</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
 
 		<script type="text/javascript" src="../jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../bootstrap/ckeditor/ckeditor.js"></script>
        <script src="scripts.js"></script>
 
    </head>
    <body>
    
    <?php
	include("../connection.php");
	//ini_set('error_reporting',E_ALL);
	?>
        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container">
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
            <div class="row">
                <!-- COLUNA OCUPANDO 10 ESPAÇOS NO GRID -->
                <div class="span12">
                    <p>
                    	<a id="bt-novaenquete" class="btn btn-info btn-large" href="novaenquete.php">Inserir enquete</a>
                    </p>
                    <div class="row">
                    	<?php
                        $busca = "SELECT id, titulo, unidade, (SELECT u.Nome FROM INF_unidades u WHERE u.IdUO = unidade) as nomeunidade, balcao, (SELECT b.Titulo FROM INF_balcoes b WHERE b.cod = balcao) as nomebalcao, ativo FROM CAD_enquete";
						//echo $busca;
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
        <div class="container">
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
                            <div class="row">
                                <div class="span10">
                                    <div class="row">
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
                                    <div class="row">
                                        <div class="span5">
                                            <label>Título:</label>
                                            <input type="text" name="titulo" id="titulo" class="span5" placeholder="Título do artigo...">
                                        </div>
                                        <div class="span5">
                                            <label>Autor:</label>
                                            <input type="text" name="autor" id="autor" class="span5" placeholder="Autor do artigo...">
                                        </div>
                                    </div>
                                    <div class="row">
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