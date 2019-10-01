window.onload = function() {
	// Toolbar completo
	//CKEDITOR.replace( 'artigo' );

	// Toolbar reduzido/customizado
	// http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar#Toolbar_Customization
	/*CKEDITOR.replace( 'artigo',
	{
		toolbar :
		[
			{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','RemoveFormat' ] },
			{ name: 'paragraph', items : [ 'NumberedList','BulletedList' ] },
			{ name: 'tools', items : [ 'Maximize','-','About' ] }
		]
	});*/

};
$(document).ready(function() {
	
	function limpaCampos() {
		$('#modal-enquetes #unidade').find('option[value="selecione"]').attr('selected',true);
		$('#modal-enquetes #area').find('option[value="selecione"]').attr('selected',true);
		$('#modal-enquetes #titulo').val('');
		$('#modal-enquetes #autor').val('');
		$('#modal-enquetes #formacao').val('');
		CKEDITOR.instances['artigo'].setData('');
		$('#modal-enquetes #acao').val('');
		$('#modal-enquetes #id_artigo').val('');
	}
	
	//$('#modal-enquetes').hide();
	
	$('#bt-novoartigo').click(function() {
		limpaCampos();
		$('#modal-enquetes #acao').val('inserir');
		$('#modal-enquetes .modal-title').text('Novo artigo');
		$('#modal-enquetes').modal('show');
	});
	
	//function ativarDesativar(id, ativo) {
	function ativarDesativar(id_enquete, ativo) {
		
		//codEnquete = id.split("-");
		//id = codEnquete[2];
		var id = id_enquete;
		
		$.ajax({
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				//id_enquete: $(this).attr('id_enquete'),
				"id_enquete": id,
				"ativo": (ativo == 1) ? 0 : 1,
				"acao": 'ativar_desativar'
			},
			cache: false,
			dataType: "json",
			async: false,
			beforeSend: function() {
			},
			success: function(data) {
				//alert(data);
				if(data == 'ok') {
					if(ativo == 1) {
						$('#bt-ativar-'+id).removeClass('btn-primary').addClass('btn-danger');
						$('#bt-ativar-'+id).attr('ativo', 0);
					} else if(ativo == 0) {
						$('#bt-ativar-'+id).removeClass('btn-danger').addClass('btn-primary');
						$('#bt-ativar-'+id).attr('ativo', 1);
					}
				}
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR1 - "+a.responseText);
			}			
		});
		
	}
	
	$('.bt-ativar').click(function() {
		
		//var id = $(this).attr('id');
		var id = $(this).attr('id_enquete');
		var ativo = $(this).attr('ativo');
		
		//ativarDesativar(id, ativo);
		
		//var num = verificaEnquetesAtivas();
		//console.log(num.length);
		
		var id_unidade = $(this).attr('id_unidade');
		var id_balcao = $(this).attr('id_balcao');
		
		
		if(ativo == 0) {
			
			var num = verificaEnquetesAtivas(id_unidade, id_balcao);
			
			if(num.length > 0) {
				for(var i = 0; i < num.length; i++) {
					console.log('Desativando a enquete ' + num[i]);
					ativarDesativar(num[i], 1);
				}				
			}
		} 
		
		ativarDesativar(id, ativo);
		
	});
	
	$('.bt-publicar, .linha').click(function() {
		$('.modal-enquete').modal();
		buscaEnquete($(this).attr('id_enquete'));
	});
	
	function publicarNaoPublicar() {
		
		//var id = $(this).attr('id');
		var id = $('.bt-publicar').attr('id');
		//var publicado = $(this).attr('publicado');
		var publicado = $('.bt-publicar').attr('publicado');
		
		$.ajax({
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id_enquete: $(this).attr('id_enquete'),
				publicado: (publicado == 1) ? 0 : 1,
				acao: 'publicar_naopublicar'
			},
			cache: false,
			dataType: "json",
			async: false,
			success: function(data) {
				//alert(data);
				if(data == 'ok') {
					if(ativo == 1) {
						$('#'+id).removeClass('btn-primary').addClass('btn-danger');
						$('#'+id).attr('ativo', 0);
					} else if(ativo == 0) {
						$('#'+id).removeClass('btn-danger').addClass('btn-primary');
						$('#'+id).attr('ativo', 1);
					}
				}
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR1 - "+a.responseText);
			}			
		});
		
	}
	
	function publicaEnquete() {
		
		var id = $('.bt-publicaEnquete').attr('id-enquete');
		var publicado = $('.bt-publicaEnquete').attr('publicado');
		
		$.ajax({
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id_enquete: id,
				//publicado: (publicado == 1) ? 0 : 1,
				publicado: publicado,
				//acao: 'publicar_naopublicar'
				acao: 'publicar'
			},
			cache: false,
			dataType: "json",
			async: false,
			success: function(data) {
				//alert(data);
				if(data == 'ok') {
					/*if(ativo == 1) {
						$('#'+id).removeClass('btn-primary').addClass('btn-danger');
						$('#'+id).attr('ativo', 0);
					} else if(ativo == 0) {
						$('#'+id).removeClass('btn-danger').addClass('btn-primary');
						$('#'+id).attr('ativo', 1);
					}*/
					$('#'+id).removeClass('btn-danger').addClass('btn-primary');
					$('#'+id+' icon-publicado').addClass('icon-white');
					
					mudaBotaoPublicar('1');
					alert('Enquete publicada!');
					window.location.reload();
				}
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR1 - "+a.responseText);
			}			
		});
		
	}
	
	function insereJustificativa() {
		
		var id = $('.bt-naoPublicaEnquete').attr('id-enquete');
		//var publicado = $('.bt-naoPublicaEnquete').attr('publicado');
		var justificativa = $('#justificativa').val();
		var usuario = $('#usuario').val();
		var retorno = '';
		
		$.ajax({
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id_enquete: id,
				//publicado: publicado,
				justificativa: justificativa,
				usuario: usuario,
				acao: 'insereJustificativa'
			},
			cache: false,
			dataType: "json",
			async: false,
			success: function(data) {
				retorno = data;
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR (justificativa) - "+a.responseText);
			}			
		});
		
		return retorno;
		
	}
	
	function naoPublicaEnquete() {
		
		var id = $('.bt-naoPublicaEnquete').attr('id-enquete');
		var publicado = $('.bt-naoPublicaEnquete').attr('publicado');
		
		$.ajax({
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id_enquete: id,
				//publicado: (publicado == 1) ? 0 : 1,
				publicado: publicado,
				//acao: 'publicar_naopublicar'
				acao: 'naopublicar'
			},
			cache: false,
			dataType: "json",
			async: false,
			success: function(data) {
				//alert(data);
				if(data == 'ok') {
					/*if(ativo == 1) {
						$('#'+id).removeClass('btn-primary').addClass('btn-danger');
						$('#'+id).attr('ativo', 0);
					} else if(ativo == 0) {
						$('#'+id).removeClass('btn-danger').addClass('btn-primary');
						$('#'+id).attr('ativo', 1);
					}*/
					$('#'+id).removeClass('btn-danger').addClass('btn-primary');
					$('#'+id+' icon-publicado').addClass('icon-white');
					
					mudaBotaoPublicar('0');
					alert('A enquete não foi publicada!');
					window.location.reload();
				}
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR1 - "+a.responseText);
			}			
		});
		
	}
	
	$('.bt-publicaEnquete').click(function() {
		publicaEnquete();
	});
	
	$('.bt-naoPublicaEnquete').click(function() {
		$('#modal-enquete-justificativa').modal();
		//naoPublicaEnquete();
	});
	
	$('.bt-salvaJustificativa').click(function() {
		var justificativa = insereJustificativa();
		if(justificativa == 'ok') {
			naoPublicaEnquete();
			console.log('gravou');
		} else {
			console.log('NÃO gravou');
		}
	});
	
	function mudaBotaoPublicar(publicado) {
		if(publicado == '1' ) {
			//$('.bt-publicar').removeClass('btn-danger').addClass('btn-primary');
			//$('.icone-publicar').addClass('icon-white');
			//$('.bt-publicar').attr('disabled', 'disabled');
			$('.bt-publicaEnquete').attr('disabled', 'disabled');
			$('.bt-naoPublicaEnquete').removeAttr('disabled');
			$('.icone-publicar').addClass('icon-white');
		} else if(publicado == '0' ) {
			$('.bt-naoPublicaEnquete').attr('disabled', 'disabled');
			$('.bt-publicaEnquete').removeAttr('disabled');
			$('.icone-publicar').addClass('icon-white');
		} else {
			$('.bt-publicaEnquete').removeAttr('disabled');
			$('.bt-naoPublicaEnquete').removeAttr('disabled');
			//$('.icone-publicar').removeClass('icon-white');
			$('.icone-publicar').addClass('icon-white');
		}
	}
	
	function buscaEnquete(id_enquete) {
		
		$.ajax({
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id_enquete: id_enquete,
				acao: 'buscar'
			},
			cache: false,
			dataType: "json",
			async: false,
			success: function(data) {
				//alert(data);
				if(data != '') {
					$('.respostas').html('');
					for(var i = 1; i < data.length; i++) {
						$('.pergunta').html('<h4>'+data[i].pergunta+'</h4>');
						$('.respostas').append('<div class="row" style="width:97%; margin: 5px 1.5%;">'+data[i].resposta+'</div>');
						$('.bt-publicaEnquete, .bt-naoPublicaEnquete').attr('id-enquete', data[i].id);
						
						mudaBotaoPublicar(data[i].publicado);
						
					}
				}
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR1 - "+a.responseText);
			}			
		});
		
	}
	
	$('.bt-atualizar').click(function() {
		
		//var conteudoArtigo = CKEDITOR.instances['artigo'].getData();
		var id_artigo = $(this).attr('id_artigo');
		//alert(id_artigo);
		
		$.ajax({
			url: 'ajax_artigos.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id: id_artigo,
				acao: 'buscar'
			},
			cache: false,
			dataType: "json",
			async: false,
			success: function(data) {
				var dados = eval(data);
				//alert(data.Titulo);
				$('#modal-enquetes #unidade').find('option[value="'+dados.ID_Unidade+'"]').attr('selected',true);
				$('#modal-enquetes #area').find('option[value="'+dados.Eixo+'"]').attr('selected',true);
				$('#modal-enquetes #titulo').val(dados.Titulo);
				$('#modal-enquetes #autor').val(dados.Autor);
				$('#modal-enquetes #formacao').val(dados.Formacao);
				CKEDITOR.instances['artigo'].setData(dados.Artigo);
				$('#modal-enquetes #acao').val('atualizar');
				$('#modal-enquetes #id_artigo').val(id_artigo);
				$('#modal-enquetes .modal-title').text('Editar artigo');
				
				$('#modal-enquetes').modal('show');
				
			},
			error: function(a){//SERVER ERROR
				alert("SERVER ERROR2 - "+a.responseText);
			}			
		});
		
	});
	
	$('.bt-excluir').click(function() {
		
		//var conteudoArtigo = CKEDITOR.instances['artigo'].getData();
		var id_artigo = $(this).attr('id_artigo');
		if(confirm('Deseja realmente excluir o artigo selecionado?')) {
			$.ajax({
				url: 'ajax_artigos.php',
				//type: 'GET',
				type: 'POST',
				//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
				data: {
					id: id_artigo,
					acao: 'excluir'
				},
				cache: false,
				//dataType: "json",
				async: false,
				success: function(data) {
					//setTimeout(alert(data), 1000);
					alert(data);
					location.reload(true);
					
				},
				error: function(a){//SERVER ERROR
					alert("SERVER ERROR3 - "+a.responseText);
				}			
			});
		}
	});
	
	/*******************************************************************************/
	// VERIFICAR IMPACTO DESTA FUNÇÃO ABAIXO ESTAR COMENTADA
	// TALVEZ A index.php TENHA PROBLEMAS NO SELECT DE UNIDADES
	/******************************************************************************/
	
	/*$('#unidade').change(function(e) {
        
		var id_unidade = $(this).attr('id');
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
				$('#unidade').html(data);
				
			},
			error: function(a){//SERVER ERROR
				alert("SERVER ERROR3 - "+a.responseText);
			}			
		});
		
    });*/
	/******************************************************************************/
	
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
	
	function salvaEnquete() {
		
		var id_balcao = $('#balcao').val();
		//var resposta = "";
		
		if($('#umaResposta').is(':checked')) {
			var resposta = '<input type="text" name="outros" style="width:300px;" />';
		} else {
			var resposta = new Array();
			//resposta = $('#respostas').val();
			$('.respAdicionada .spanResp').each(function(index, element) {
				//alert($(this).text());
				resposta.push($(this).text());
			});
			
			if($('#campoAberto').is(':checked')) {
				resposta.push('Outros. Qual? <input type="text" name="outros" style="width:300px;" />');
			}
		}
		
		//console.log(num);
		
		$.ajax({
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				unidade: $('#unidade').val(),
				enquete: $('#enquete').val(),
				pergunta: $('#pergunta').val(),
				respostas: resposta,
				balcao: id_balcao,
				acao: 'inserir'
			},
			cache: false,
			dataType: "json",
			async: false,
			beforeSend: function() {
				//console.log(resposta);
			},
			success: function(data) {
				alert(data);
			},
			error: function(a){//SERVER ERROR
				alert("SERVER ERROR1 "+a.responseText);
			}			
		});
		
	}
	
	$('#bt-salvar').click(function() {
								   
		//console.log(verificaEnquetesAtivas());
		var unidade = $('#unidade option:selected').val();
		var balcao = $('#balcao option:selected').val();
		
		var num = verificaEnquetesAtivas(unidade, balcao);
		console.log(num.length);
		
		if(num.length > 0) {
			
			if(confirm('Há pelo menos '+ num.length +' enquete(s) ativa(s). É necessário desativar todas as enquetes anteiores antes de publicar esta.\r\nDeseja desativar todas as anteriores?')) {
				
				for(var i = 0; i < num.length; i++) {
					console.log('Desativando a enquete ' + num[i]);
					ativarDesativar(num[i], 1);
				}
				
				salvaEnquete();
				
			} else {
				
				alert('Para publicar a nova enquete, é necessário desativar as anteriores.');
				
			}
		
		} else {
			
			salvaEnquete();		
			
		}
		
		
		
	});
	
	//function verificaEnquetesAtivas() {
	function verificaEnquetesAtivas(var_unidade, var_balcao) {

		//var unidade = $('#unidade option:selected').val();
		//var balcao = $('#balcao option:selected').val();
		
		var unidade = var_unidade;
		var balcao = var_balcao;
		var numEnquetes = new Array();
		
		$.ajax({   
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id_unidade: unidade,
				id_balcao: balcao,
				acao: 'verificaEnquetesAtivas'
			},
			cache: false,
			dataType: "json",
			async: false,
			beforeSend: function() {
				//console.log(unidade);
			},
			success: function(data) {
				console.log(data.total);
				//console.log(data[0].id);
				if(data.total > 0) {
					console.log(data);
					for(var i = 0; i < data.total; i++) {
						//console.log(data[i].id);
						//alert(data[i].titulo);
						numEnquetes[i] = data[i].id;
					}
					
					//alert(data[0].titulo);
					
				} else {
					console.log('ERROR - verificaEnquetesAtivas()');
				}
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR - verificaEnquetesAtivas() - "+a.responseText);
			}
		});
		
		return numEnquetes;
	}
	
	/*
	$('#modal-enquete-justificativa').on('hidden', function() {
		$('#modal-enquete').modal('show');
		//$('.bt-vermais').click();
	});
	*/
	$('#modal-enquete-justificativa').on('show', function() {
		$('#modal-enquete').modal('hide');
		//$('.bt-vermais').click();
	});
	
});