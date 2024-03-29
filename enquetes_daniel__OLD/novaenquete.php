<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>::: Enquetes :::</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <style>
		  	/*#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
		  	#sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
		  	#sortable li span { position: absolute; margin-left: -1.3em; }*/
			
			.respAdicionada {
				box-shadow: 0px 1px 3px #CCC;
				padding: 5px 0.5%;
				margin-bottom: 10px;
			}
		</style>
 
 		<script type="text/javascript" src="../jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
        <script type="text/javascript" src="../jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../bootstrap/ckeditor/ckeditor.js"></script>
        <script src="scripts.js"></script>
        
        <script>
			$(document).ready(function() {
				
				$('.respostas').hide(400);
				
				$('.row-fluid [class*="span"]').css('margin-left', '0px');
                
				$('#variasRespostas').click(function() {
					$('.respostas').show(400);
				});
				
				$('#umaResposta').click(function() {
					$('.respostas').hide(400);
				});
				
				$('#adiciona').click(function() {
					
					var resposta = $('#respostas').val();
					
					if(resposta != '') {
						//$('.adicionadas').append('<p class="respAdicionada"><span class="span8 spanResp">'+resposta+'</span><span class="remove"><i class="icon-remove"></i></span></p>');
						$('.adicionadas').append('<div class="row-fluid respAdicionada"><div class="span8 spanResp">'+resposta+'</div><div class="span2 remove pull-right"><button type="button" class="btn bt-remove-resposta pull-right"><i class="icon-trash"></i></button></div></div>');
						//$('.adicionadas').append('<li class="ui-state-default respAdicionada span8"><span class="span8 spanResp">'+resposta+'</span><input type="button" class="icon-remove" /></li>');
						
						$('#respostas').val('');
						$('.adicionadas').children().last('.icon-remove').click(function() {
							//$(this).last('.respAdicionada').remove();
							//alert(resposta);
						});
						
						$('.bt-remove-resposta').click(function() {
							$(this).parent().parent().remove();
						});
					}
					
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
				
				$(function() {
					//$( "#sortable" ).sortable();
					//$( "#sortable" ).disableSelection();
				});
				
				$('.adicionadas').sortable();
				$('.adicionadas').disableSelection();
				
            });
		</script>
 
    </head>
    <body>
    
    <?php
	//ini_set('error_reporting',E_ALL);
	//include("../connection.php");
	include("../connection_alpha_homologacao.php");
	
	//$usuario = trim(strtoupper(substr($_SERVER["AUTH_USER"],8,100)));
	$usuario = 'DJUNIOR';
	
	mysql_select_db("Siap", $db_alpha);
	
	$sql20 = "SELECT * FROM stUsuario where IdUsuario = '".$usuario."'";

	//$qsql20 = mysql_query($sql20, $db2);
	$qsql20 = mysql_query($sql20, $db_alpha);
	//echo $db2;
	if($res0 = mysql_fetch_assoc($qsql20)){
		$unidade = trim($res0['IdUO']);
		$email = trim($res0['email']);
		$nomeUsusario = trim($res0['NOME']);
	}
	
	//$unidade = "6";
	$unidade = trim($unidade);
	
	if(is_numeric($unidade)) {
		header("Location:index_unidade.php");
	}

	mysql_select_db("PortalSenacRS", $db_alpha);
	
	?>
        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container-fluid">
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
            <div class="row-fluid">
                <!-- COLUNA OCUPANDO 10 ESPA�OS NO GRID -->
                <form>
                	<div class="well span12">
                    	<div class="row-fluid">
                        	<div class="span12">
                            	<legend>Enquete</legend>
                            </div>
                        </div>
                    	<div class="row-fluid">
                        	<div class="span6">
                                <input type="hidden" name="acao" id="acao" value="inserir" />
                                <label>Unidade:</label>
                                <!--<input type="text" placeholder="Digite algo...">-->
                                <select name="unidade" id="unidade" class="span6">
                                    <option>Selecione...</option>
                                    <?php
                                    $sql = "SELECT IdUO, Nome FROM INF_unidades WHERE AtivoSite = 1 ORDER BY Nome";
                                    $query = mysql_query($sql, $db_alpha);
                                    while($res = mysql_fetch_assoc($query)) {
                                        echo '<option value="'.$res['IdUO'].'">'.$res['Nome'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="span6">
                                <label>T�tulo:</label>
                                <input type="text" name="enquete" id="enquete" class="span6" placeholder="Digite...">
                            </div>
                        </div>
                        <div class="row-fluid">
                        	<div class="span12">
                                <input type="hidden" name="acao" id="acao" value="inserir" />
                                <label>Balc�o:</label>
                                <!--<input type="text" placeholder="Digite algo...">-->
                                <select name="balcao" id="balcao" class="span6">
                                    <option>Selecione...</option>
                                    <?php
                                    /*
									$sql = "SELECT IdUO, Nome FROM INF_unidades WHERE AtivoSite = 1 ORDER BY Nome";
                                    $query = mysql_query($sql, $db_alpha);
                                    while($res = mysql_fetch_assoc($query)) {
                                        echo '<option value="'.$res['IdUO'].'">'.$res['Nome'].'</option>';
                                    }
									*/
                                    ?>
                                </select>
                            </div>
                        </div>
                	</div>
                    <div class="well span12">
                    	<div class="row-fluid">
                        	<div class="span12">
                            	<legend>Pergunta</legend>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <label>T�tulo:</label>
                                <input type="text" name="pergunta" id="pergunta" class="span12" placeholder="Digite...">
                            </div>
                        </div>
                	</div>
                    <div class="well span12">
                    	<div class="row-fluid">
                        	<div class="span12">
                            	<legend>Respostas</legend>
                            </div>
                        </div>
                        <div class="row-fluid">
                        	<div class="span12">
                            	<label class="checkbox inline">
									<input type="radio" name="tipoResposta" id="umaResposta" value="option1"> 1 Resposta (com campo aberto)
								</label>
								<label class="checkbox inline">
									<input type="radio" name="tipoResposta" id="variasRespostas" value="option2"> V�rias respostas
								</label>
                            </div>
                        </div>
                        <br />
                        <div class="row-fluid respostas">
                        	<div class="row-fluid">
                            	<label class="checkbox inline">
									<input type="checkbox" name="campoAberto" id="campoAberto" value="option1"> Incluir campo aberto ("outros")
								</label>
                            </div>
                            <div class="row-fluid input-append">
                                <input type="text" name="respostas" id="respostas" class="span8" placeholder="Digite...">
                                <button class="btn" id="adiciona" type="button"><i class="icon-plus"></i></button>
                            </div>
                            <!--<div class="span12 adicionadas">
                            </div>-->
                            <div class="span12">
                                <div class="row-fluid adicionadas">
                                    <!--<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1</li>
                                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</li>
                                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</li>
                                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</li>
                                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</li>
                                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</li>
                                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</li>-->
                                </div>
                            </div>
                        </div>
                	</div>
                    <div class="span12">
                    	<div class="row-fluid">
                        	<div class="span12">
                                <span><button type="button" id="bt-salvar" class="btn btn-primary btn-large">Salvar <i class="icon-ok icon-white"></i></button></span>
                            </div>
                        </div>
                    </div>
             </form>
            </div>
		</div>
    </body>
</html>