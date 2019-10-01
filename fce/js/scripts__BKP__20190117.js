$(document).ready(function() {
	
	$('.relatorios').hide();
	
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
	tituloEnquete();
	
	$('#bt-novoartigo').click(function() {
		limpaCampos();
		$('#modal-enquetes #acao').val('inserir');
		$('#modal-enquetes .modal-title').text('Novo artigo');
		$('#modal-enquetes').modal('show');
	});
	
	function mudaAba(p) {
		var id = p;//$(this).attr('id');
		$('.aba').removeClass('active');
		$('#'+id).addClass('active');
		$('.secoes').hide();
		$('.'+id).show();
	}
	
	$('.aba').click(function() {
		var id = $(this).attr('id');
		mudaAba(id);
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
					alert('A enquete n�o foi publicada!');
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
			console.log('N�O gravou');
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
						$('.respostas').append('<div class="row-fluid resposta" id_resposta="'+data[i].id_resposta+'" style="width:97%; margin: 5px 1.5%;">'+data[i].resposta+'</div>');
						$('.extrato_respostas').html('<a href="relatorio_enquete.php?id='+data[i].id+'">Clique aqui e veja as respostas desta enquete</a>');
						$('.bt-publicaEnquete, .bt-naoPublicaEnquete').attr('id-enquete', data[i].id);
						
						mudaBotaoPublicar(data[i].publicado);
						
					}
				}
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR1 - "+a.responseText);
			}			
		});
		
		$('.resposta').mouseover(function() {
			
			$(this).css({'outline':'1px solid #000'});
			
		});
		
		$('.resposta').mouseout(function() {
										 
			$(this).css({'outline':'none'});
			
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
	// VERIFICAR IMPACTO DESTA FUN��O ABAIXO ESTAR COMENTADA
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
			
			if(confirm('H� pelo menos '+ num.length +' enquete(s) ativa(s). � necess�rio desativar todas as enquetes anteiores antes de publicar esta.\r\nDeseja desativar todas as anteriores?')) {
				
				for(var i = 0; i < num.length; i++) {
					console.log('Desativando a enquete ' + num[i]);
					ativarDesativar(num[i], 1);
				}
				
				salvaEnquete();
				
			} else {
				
				alert('Para publicar a nova enquete, � necess�rio desativar as anteriores.');
				
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
	
	function tituloEnquete() {
		
		var value = $('#unidade option:selected').val();
		console.log(value);
		
		if($('#unidade option:selected').val() != '') {
			
			$('#enquete').val(value);
		
		} else {
			
			$('#enquete').val('');
			
		}
		
	}
	
	$('#unidade').change(function() {
		
		tituloEnquete();
		
	});
	
	$('.bt-graficoescola').click(function() {
		
		var iduo = $(this).attr('iduo');
		carregaGrafico(iduo);
		carregaGrafico2(iduo);
		
	});
	
	function carregaGrafico(iduo, idbalcao) {
		
		var unidade = iduo;
		var balcao = idbalcao;
		var numEnquetes = new Array();
		var dados_grafico = new Array();
		var dados_grafico2 = new Array();
		
		$.ajax({   
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id_unidade: unidade,
				id_balcao: balcao,
				acao: 'carrega_grafico'
			},
			cache: false,
			dataType: "json",
			async: false,
			beforeSend: function() {
				//console.log(unidade);
			},
			success: function(data) {
				//console.log(data['total']);
				//console.log(data);
				
				for(var j=0; j < data.length; j++) {
					//console.log(data[j]);
				}
				
				if(data.total > 0) {
					console.log(data);
					for(var i = 0; i < data.total; i++) {
						//console.log(data[i].id);
						//alert(data[i].titulo);
						//numEnquetes[i] = data[i].id;
						//dados_grafico[data[i].pergunta] = data[i].total_respostas;
						dados_grafico.push(data[i]);
					}
					
					//alert(data[0].titulo);
					console.log(dados_grafico);
					console.log(dados_grafico[1]);
					
					
					/*************   ESTA FUN��O CONVERTE UM ARRAY DE OBJETOS EM UM ARRAY DE ARRAYS  ************/
					dados_grafico2 = dados_grafico.map(function(obj) {
						return Object.keys(obj).map(function(chave) {
							return obj[chave];
						});
					});
					
					console.log(dados_grafico2);
					//console.log(dados_grafico2[0]);
					//return dados_grafico2;	
					
				} else {
					console.log('ERROR - verificaEnquetesAtivas()');
				}
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR - verificaEnquetesAtivas() - "+a.responseText);
			}
		});
		
		//return numEnquetes;
		
		//console.log(dados_grafico2[0]);
		
		var dados_grafico3 = new Array();
		
		for(valor in dados_grafico2) {
			//console.log(dados_grafico2[valor]);
			/*
			for(valor2 in valor) {
				console.log(valor2);
			}
			*/
			var teste = dados_grafico2[valor].slice();
			//console.log(teste[0]+' - '+teste[1]);
			var vlr_convertido = parseFloat(teste[1]);
			
			dados_grafico_aux = [teste[0], vlr_convertido];
			
			dados_grafico3.push(dados_grafico_aux);
		}
		
		//console.log(dados_grafico3[0]);
		
		//$('.container').html();
		
		/*var dados_teste = [['Shanghai', 23.7],
					['Lagos', 16.1],
					['Istanbul', 14.2],
					['Karachi', 14.0],
					['Mumbai', 12.5],
					['Moscow', 12.1],
					['S�o Paulo', 11.8],
					['Beijing', 11.7],
					['Guangzhou', 11.1],
					['Delhi', 11.1],
					['Shenzhen', 10.5],
					['Seoul', 10.4],
					['Jakarta', 10.0],
					['Kinshasa', 9.3],
					['Tianjin', 9.3],
					['Tokyo', 9.0],
					['Cairo', 8.9],
					['Dhaka', 8.9],
					['Mexico City', 8.9],
					['Lima', 8.9]];*/
		
		//console.log(dados_teste[0]);
				
		Highcharts.chart('container', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Top 10 escolas com mais enquetes publicadas'
			},
			subtitle: {
				text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Population (millions)'
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
			},
			series: [{
				name: 'Population',
				data: dados_grafico3,/***********  AQUI S�O SETADOS OS VALORES... DEVE SER UM ARRAY!!!  ***********/
				dataLabels: {
					enabled: true,
					rotation: -90,
					color: '#FFFFFF',
					align: 'right',
					format: '{point.y:.1f}', // one decimal
					y: 10, // 10 pixels down from the top
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});
		
	}
	
	
	function carregaGrafico2(iduo, idbalcao) {
		
		var unidade = iduo;
		var balcao = idbalcao;
		var ativoInativo = $('input[name=ativoInativo]:checked').val();
		var numEnquetes = new Array();
		var dados_grafico = new Array();
		var dados_grafico2 = new Array();
		var total_resp = 0;
		
		$.ajax({   
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				'id_unidade': unidade,
				'id_balcao': balcao,
				'ativoInativo': ativoInativo,
				'acao': 'carrega_grafico2'
			},
			cache: false,
			dataType: "json",
			async: false,
			beforeSend: function() {
				console.log(unidade);
			},
			success: function(data) {
				//console.log(data.totais_somados);
				console.log(data);
				var height = 0;
				var html = '';
				html = '<div style="position:relative; background-color:#eee; height:200px; overflow-y:hidden;">';
				
				if(data.total > 0) {
				
					for(var i=0; i < data.total; i++) {
						console.log(data[i].total_respostas);
						console.log(data.length);
						//total_resp = total_resp + parseFloat(data[i].total_respostas);
						
						height = 100 - parseFloat(data[i].total_respostas);
													   
						html += '<div class="barra-grafico" style="height:100%; bottom:-'+height+'%;"></div>';
						
					}
					
					html += '</div>';
					console.log(html);
				
				}
				
				//var total_grafico = data.totais_somados;
				
				$('.graficos').html(html);
				
				
			},
			error: function(a){//SERVER ERROR
				console.log("SERVER ERROR - verificaEnquetesAtivas() - "+a.responseText);
			}
		});
		
		
	}
	
});