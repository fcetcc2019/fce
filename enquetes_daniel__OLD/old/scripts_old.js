window.onload = function() {
	// Toolbar completo
	//CKEDITOR.replace( 'artigo' );

	// Toolbar reduzido/customizado
	// http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar#Toolbar_Customization
	CKEDITOR.replace( 'artigo',
	{
		toolbar :
		[
			{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','RemoveFormat' ] },
			{ name: 'paragraph', items : [ 'NumberedList','BulletedList' ] },
			{ name: 'tools', items : [ 'Maximize','-','About' ] }
		]
	});

};
$(document).ready(function() {
	
	function limpaCampos() {
		$('#modal-artigos #unidade').find('option[value="selecione"]').attr('selected',true);
		$('#modal-artigos #area').find('option[value="selecione"]').attr('selected',true);
		$('#modal-artigos #titulo').val('');
		$('#modal-artigos #autor').val('');
		$('#modal-artigos #formacao').val('');
		CKEDITOR.instances['artigo'].setData('');
		$('#modal-artigos #acao').val('');
		$('#modal-artigos #id_artigo').val('');
	}
	
	$('#modal-artigos').hide();
	
	$('#bt-novoartigo').click(function() {
		limpaCampos();
		$('#modal-artigos #acao').val('inserir');
		$('#modal-artigos .modal-title').text('Novo artigo');
		$('#modal-artigos').modal('show');
	});
	
	$('#bt-salvar').click(function() {
		
		var conteudoArtigo = CKEDITOR.instances['artigo'].getData();
		
		$.ajax({
			url: 'ajax_artigos.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				unidade: $('#unidade').val(),
				area: $('#area').val(),
				titulo: $('#titulo').val(),
				autor: $('#autor').val(),
				formacao: $('#formacao').val(),
				artigo: conteudoArtigo,
				acao: $('#acao').val(),
				id_artigo: $('#id_artigo').val()
			},
			cache: false,
			dataType: "json",
			async: false,
			success: function(data) {
				alert(data);
				$('#modal-artigos').modal('hide');
				location.reload(true);
			},
			error: function(a){//SERVER ERROR
				alert("SERVER ERROR1 - "+a.responseText);
			}			
		});	
		
	});
	
	$('.bt-ativar').click(function() {
		
		//var conteudoArtigo = CKEDITOR.instances['artigo'].getData();
		var id = $(this).attr('id');
		var ativo = $(this).attr('ativo');
		$.ajax({
			url: 'ajax_enquetes.php',
			//type: 'GET',
			type: 'POST',
			//data: "resposta="+resposta+"&unidade="+unidade+"&outros="+outros,
			data: {
				id_enquete: $(this).attr('id_enquete'),
				ativo: (ativo == 1) ? 0 : 1,
				acao: 'ativar_desativar'
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
		
	});
	
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
				$('#modal-artigos #unidade').find('option[value="'+dados.ID_Unidade+'"]').attr('selected',true);
				$('#modal-artigos #area').find('option[value="'+dados.Eixo+'"]').attr('selected',true);
				$('#modal-artigos #titulo').val(dados.Titulo);
				$('#modal-artigos #autor').val(dados.Autor);
				$('#modal-artigos #formacao').val(dados.Formacao);
				CKEDITOR.instances['artigo'].setData(dados.Artigo);
				$('#modal-artigos #acao').val('atualizar');
				$('#modal-artigos #id_artigo').val(id_artigo);
				$('#modal-artigos .modal-title').text('Editar artigo');
				
				$('#modal-artigos').modal('show');
				
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
	
	$('#unidade').change(function(e) {
        
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
		
    });
});