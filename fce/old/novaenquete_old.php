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
        <script>
			$(document).ready(function() {
                
				$('#bt-salvar').click(function() {
					
					var id_balcao = $('#balcao').val();
					
					$.ajax({
						url: 'ajax_enquetes.php',
						//type: 'GET',
						type: 'POST',
						//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
						data: {
							unidade: $('#unidade').val(),
							enquete: $('#enquete').val(),
							pergunta: $('#pergunta').val(),
							respostas: $('#respostas').val(),
							balcao: id_balcao,
							//formacao: $('#formacao').val(),
							//artigo: conteudoArtigo,
							acao: 'inserir'
						},
						cache: false,
						dataType: "json",
						async: false,
						success: function(data) {
							alert(data);
						},
						error: function(a){//SERVER ERROR
							alert("SERVER ERROR1 "+a.responseText);
						}			
					});	
					
				});
				
				$('#unidade').change(function(e) {
        
					var id_unidade = $(this).val();
					//alert(id_unidade);
					$.ajax({
						url: 'ajax_enquetes.php',
						//type: 'GET',
						type: 'POST',
						//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
						data: {
							unidade: id_unidade,
							acao: 'busca_balcao'
						},
						cache: false,
						//dataType: "json",
						async: false,
						success: function(data) {
							//setTimeout(alert(data), 1000);
							$('#balcao').html(data)
							
						},
						error: function(a){//SERVER ERROR
							alert("SERVER ERROR3 - "+a.responseText);
						}			
					});
					
				});
				
            });
		</script>
 
    </head>
    <body>
    
    <?php
	include("../connection.php");
	?>
        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container">
            <!-- CLASSE PARA DEFINIR UMA LINHA -->
            <ul class="nav nav-pills">
            	<li class="active pull-right">
                	<a href="index.php">Voltar</a>
            	</li>
            </ul>
            <h1>
            	Enquetes
                <small>Inserir</small>
            </h1>
            <hr>
            <div class="row">
                <!-- COLUNA OCUPANDO 10 ESPAÇOS NO GRID -->
                <form>
                	<div class="well span12">
                    	<div class="row">
                        	<div class="span12">
                            	<legend>Enquete</legend>
                            </div>
                        </div>
                    	<div class="row">
                        	<div class="span6">
                                <input type="hidden" name="acao" id="acao" value="inserir" />
                                <label>Unidade:</label>
                                <!--<input type="text" placeholder="Digite algo...">-->
                                <select name="unidade" id="unidade" class="span6">
                                    <option>Selecione...</option>
                                    <?php
                                    $sql = "SELECT IdUO, Nome FROM INF_unidades WHERE AtivoSite = 1 ORDER BY Nome";
                                    $query = mssql_query($sql, $db_alpha);
                                    while($res = mssql_fetch_assoc($query)) {
                                        echo '<option value="'.$res['IdUO'].'">'.$res['Nome'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="span6">
                                <label>Título:</label>
                                <input type="text" name="enquete" id="enquete" class="span6" placeholder="Digite...">
                            </div>
                        </div>
                        <div class="row">
                        	<div class="span12">
                                <input type="hidden" name="acao" id="acao" value="inserir" />
                                <label>Balcão:</label>
                                <!--<input type="text" placeholder="Digite algo...">-->
                                <select name="balcao" id="balcao" class="span6">
                                    <option>Selecione...</option>
                                    <?php
                                    /*
									$sql = "SELECT IdUO, Nome FROM INF_unidades WHERE AtivoSite = 1 ORDER BY Nome";
                                    $query = mssql_query($sql, $db_alpha);
                                    while($res = mssql_fetch_assoc($query)) {
                                        echo '<option value="'.$res['IdUO'].'">'.$res['Nome'].'</option>';
                                    }
									*/
                                    ?>
                                </select>
                            </div>
                        </div>
                	</div>
                    <div class="well span12">
                    	<div class="row">
                        	<div class="span12">
                            	<legend>Pergunta</legend>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span12">
                                <label>Título:</label>
                                <input type="text" name="pergunta" id="pergunta" class="span12" placeholder="Digite...">
                            </div>
                        </div>
                	</div>
                    <div class="well span12">
                    	<div class="row">
                        	<div class="span12">
                            	<legend>Respostas</legend>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span12">
                                <label>Título:</label>
                                <input type="text" name="respostas" id="respostas" class="span12" placeholder="Digite...">
                            </div>
                        </div>
                	</div>
                    <div class="span12">
                    	<div class="row">
                        	<div class="span12">
                                <span><button type="button" id="bt-salvar" class="btn btn-primary span2">Salvar <i class="icon-ok icon-white"></i></button></span>
                            </div>
                        </div>
                    </div>
             </form>
            </div>
		</div>
    </body>
</html>