var arreglo_cif = '[]';
	var arreglo_local = localStorage.getItem("arreglo_cif");
// 	if(arreglo_local != '' && arreglo_local !== undefined && arreglo_local!=null){
// 	    arreglo_cif =   arreglo_local;
// 		cargarTablaCIF(arreglo_local,'-');
// 	}
	$("#select_categoria_cif").on('select2:select', function(e) {
		var categoria = $(this).val();
		$('#select_cif').empty();
		if(categoria!= 0){
			$.get("controller/combosback.php",{oper:'grupo_cif',categoria:categoria},function(response){
				$('#select_grupo_cif').empty();
				$('#select_grupo_cif').append(response).trigger('change');
				//$('#select_grupo_cif').select2({placeholder:'Seleccione'});
			})
		}
		//console.log('categoria:'+categoria);
		$('.nav-item, .nav-item a, .tab-pane').removeClass('active');
		$('a[href="#tablaTemporalCIF_'+categoria+'"'+']').addClass('active');
		$('a[href="#tablaTemporalCIF_'+categoria+'"'+']').parent().addClass('active');
		$('#tablaTemporalCIF_'+categoria).addClass('active show');
	});	
	
	$("#select_grupo_cif").on('select2:select', function(e) {
		var valor = $(this).val();
		if(valor!= 0 && valor !== undefined && valor !== null){
			var grupo = $(this).select2('data')[0].element.dataset.grupo;
			$.get("controller/combosback.php",{oper:'cif',grupo:grupo},function(response){
				$('#select_cif').empty();
				$('#select_cif').append(response).trigger('change');
				//$('#select_cif').select2({placeholder:'Seleccione'});
			})
		}
	});

	$('#anadir_cif').click(function(){
		if(validar_agregar_cif() != 0){
			var categoria = $('#select_categoria_cif').val();
			var categoriatxt = $('#select_categoria_cif').select2('data')[0].text;
			var grupo = $('#select_grupo_cif').val();
			var grupotxt = $('#select_grupo_cif').select2('data')[0].text;
			var cif = $('#select_cif').val();
			if(cif!=0 && cif !=null){
				var ciftxt = $('#select_cif').select2('data')[0].element.dataset.nombre;
				var codigocif = $('#select_cif').select2('data')[0].element.dataset.codigo;
				var nivel = $('#select_cif').select2('data')[0].element.dataset.nivel;	
				var id = $('#select_grupo_cif').select2('data')[0].element.dataset.id;				
			}else{
				cif = $('#select_grupo_cif').val();
				var ciftxt = $('#select_grupo_cif').select2('data')[0].element.dataset.nombre;
				var codigocif = $('#select_grupo_cif').select2('data')[0].element.dataset.codigo;
				var nivel = $('#select_grupo_cif').select2('data')[0].element.dataset.nivel;
				var id = $('#select_grupo_cif').select2('data')[0].element.dataset.id;
			}
            console.log(arreglo_cif);
			if(arreglo_cif != '[]'){
				var arreglo =  JSON.parse(arreglo_cif);
			}else {
				var arreglo = {};
			}
			
			var tipoEvaluacion = $("#iddiscapacidades").val();			
			if(tipoEvaluacion == 'F??SICA'){
				if(buscar_valor(arreglo_cif,categoria,grupo,cif,id) == 0){
					agregarItem();
				} else {
					swal('ERROR','Ya el CIF <strong>'+codigocif+'</strong> se encuentra agregado','error');
				}
			}else{
				agregarItem();
			}
			
			function agregarItem(){			
				//ID
				/* json = JSON.parse(arreglo_cif);
				if(json != null && json[categoria] !== undefined){
					var act = json[categoria].length;
					var id = act + 1;
				}else{
					var id = 1;
				} */ 
				
				//ID
				json = JSON.parse(arreglo_cif);
				if(json != null && json[categoria] !== undefined){
					var act = json[categoria].length;
					
					console.log('arr categorias',json[categoria]);
					console.log('at es',act);
					
					if(act !== 0){
						//const lastFile = json[categoria].filter(item => parseInt(item.id) === act + 1);
						let lastFile = json[categoria].at(-1);
						console.log('lastFile es',lastFile); 
						let lastId = lastFile.id;
						console.log('lastId:',lastId); 
						var id_ = parseInt(lastId) + 1;
						console.log('Id A:',id_);
					}else{
						var id_ = act + 1;
						console.log('Id B:',id_); 
					}
					
					
				}else{
					var id_ = 1;
					console.log('Id C:',id_); 
				}
				
				
				
				ciftxt = ciftxt.replace('"','\\"');
				var nuevafila = '{\
				"id": "'+id_+'", \
				"categoria": "'+categoria+'", \
				"categoriatxt": "'+categoriatxt+'",\
				"grupo": "'+grupo+'",\
				"grupotxt": "'+grupotxt+'",\
				"cif": "'+cif+'",\
				"ciftxt": "'+ciftxt+'",\
				"codigocif": "'+codigocif+'",\
				"nivel": "'+nivel+'",\
				"c1": "",\
				"c2": "",\
				"c3": ""\
				}';				
				if(arreglo[categoria] != null){
					var items = JSON.stringify(arreglo[categoria]);
				}
				
				if(items === undefined){
					items = '';
				}
				if(items != '' && items != '[]')
					items += ',';
				items = items.replace('[','');
				items = items.replace(']','');
				items += nuevafila;
				items = '['+items+']';
				arreglo[categoria] = JSON.parse(items);
				arreglo_cif = JSON.stringify(arreglo);
				cargarTablaCIF(arreglo_cif,categoria);
				$('#select_cif').val(0).trigger('change');
				mensaje();
			}
		}
	});
	
	function mensaje(){
		$("#mensaje_elemento_agregado").css( {
			    "zoom": "1",
			    "filter":"alpha(opacity=50)",
			    "opacity": "1"
		});
		setTimeout(function(){
		    $("#mensaje_elemento_agregado").css( {
			    "zoom": "1",
			    "filter":"alpha(opacity=50)",
			    "opacity": "0"
			  });

		}, 2000);
	}
	
	function validar_agregar_cif(){
		var res = 1;
		if(		$('#select_categoria_cif').val() == 0 || $('#select_categoria_cif').val() == '' || 
				$('#select_categoria_cif').val() === undefined || $('#select_categoria_cif').val() == null){
			swal('ERROR',"Debe seleccionar una categor??a",'error');
			res= 0;
		}else if($('#select_grupo_cif').val() == 0 || $('#select_grupo_cif').val() == '' ||
				 $('#select_grupo_cif').val() === undefined || $('#select_grupo_cif').val() == null){
			swal('ERROR',"Debe seleccionar un grupo",'error');
			res = 0;
		}
		return res;
	}

	function cargarTablaCIF(arreglo,cat){
	    if(arreglo_cif == '[]'){
	        arreglo_cif = arreglo;
	    }
		var registro = JSON.parse(arreglo);
		localStorage.setItem("arreglo_cif",arreglo);
		var i  = 1;
		$.map(registro,function(filas, categoria){     			
			if(filas.length >0){
				//se ordena el arreglo por codigo CIF
				filas.sort(function (b, a) {
				 	if (a.codigocif > b.codigocif) {
				    	return -1;
					}
				  	if (a.codigocif < b.codigocif) {
				    	return 1;
				  	}
				  	return 0;
				});				
				if(cat == '-'){
					cat = categoria;
				}
			    var html = '<table class="dataTable table-striped table-bordered">';
			       	switch(categoria){
			       		case "b":
			       			html +='<thead>\
			       				<th class="titulo_tabla"style="width:5%;">Acci??n</th>\
					            <th class="titulo_tabla"style="width:5%;">Nivel</th> \
					            <th class="titulo_tabla"style="width:5%;">C??digo</th> \
					            <th class="titulo_tabla"style="width:80%;">CIF</th>\
					            <th class="titulo_tabla"style="width:5%;">C</th>';
			       		break;
			       		case "s":
			       			html +='<thead> \
								<th class="titulo_tabla"style="width:5%;">Acci??n</th>  \
								<th class="titulo_tabla"style="width:5%;">Nivel</th> \
								<th class="titulo_tabla"style="width:5%;">C??digo</th> \
								<th class="titulo_tabla"style="width:70%;">CIF</th>\
								<th class="titulo_tabla"style="width:5%;">C</th>\
					   			<th class="titulo_tabla"style="width:5%;">C</th>\
				              	<th class="titulo_tabla"style="width:5%;">C</th>';
			       		break;
			       		case "d":
			       		case "e":
			       			html +='<thead> \
								<th class="titulo_tabla"style="width:5%;">Acci??n</th>  \
								<th class="titulo_tabla"style="width:5%;">Nivel</th> \
								<th class="titulo_tabla"style="width:5%;">C??digo</th> \
								<th class="titulo_tabla"style="width:37.5%;">CIF</th>\
								<th class="titulo_tabla"style="width:5%;">C</th>\
								<th class="titulo_tabla"style="width:5%;">C</th>';
			       		break;
			       	}		                 
			    html +='</thead><tbody>';			   
			    var lista_cif ='<li class="nav-item"> \
			              <a class="nav-link pestana" data-toggle="tab" href="#tablaTemporalCIF_'+categoria+'" id="tab_'+categoria+'" role="tablist" style="">\
			              <strong>'+filas[0]['categoriatxt']+'</strong>\
			              </a>\
			 					</li>';
			    var pestana  = '<div class="tab-pane" id="tablaTemporalCIF_'+categoria+'" class="tablaitems" data-id="'+categoria+'"></div>';
				var select_filtro = '<div class="p-3">\
										<label class="control-label" for="filtroitem">Filtro por CIF</label>\
										<select id="select_filtros_cif_'+categoria+'" class="form-control select_filtros"><option value="0">Todos</option>';
			    var i =1;
			  	var grupo_de_ultimo_registro ='';
			    $.map(filas,function(fila){			    	
					if (grupo_de_ultimo_registro != fila.grupo){
						html+= '<tr id="'+fila.grupo+'" id-item="'+fila.id+'" data-id="'+categoria+'_'+i+'" class="tr_tabla"  data-grupo="'+fila.grupo+'" data-categoria ="'+categoria+'" data-cif="'+fila.cif+'">\
									<td class="bg-success-light b-none"></td>\
									<td class="bg-success-light b-none"></td>\
									<td class="bg-success-light b-none"></td>\
									<td class="bg-success-light b-none">\
										<span class="btn fas fa-eye-slash bg-success text-white p-1 ver_cif" data-categoria ="'+categoria+'" id-item="'+fila.id+'" data-id="'+fila.grupo+'"data-toggle="tooltip" data-original-title="Mostrar" data-placement="right"></span>\
										<strong>'+fila.grupotxt+'</strong>\
									</td>';
						switch(fila.categoria){
							case "b":
								html+='	<td class="bg-success-light b-none"></td>';
							break;
							case "s":
								html+='	<td class="bg-success-light b-none"></td>\
										<td class="bg-success-light b-none"></td>\
										<td class="bg-success-light b-none"></td>';
							break;
							case "d":
							case "e":
								html+='	<td class="bg-success-light b-none"></td>\
										<td class="bg-success-light b-none"></td>';
							break;
						}
						html+='</tr>';
					} 
			  		grupo_de_ultimo_registro = fila.grupo;  

			    	select_filtro += '<option value="'+fila.cif+'" id-item="'+fila.id+'" data-grupo="'+fila.grupo+'" data-categoria ="'+categoria+'" >'+fila.codigocif+' | '+fila.ciftxt+'</option>';
			      	html += '<tr id="'+categoria+'_'+i+'" class="tr_tabla" id-item="'+fila.id+'" data-grupo="'+fila.grupo+'" data-categoria ="'+categoria+'" data-cif="'+fila.cif+'">';
			      	switch(fila.categoria){
			       		case "b":
			       			html +='<td class="text-center">\
						            	<span class="btn fas fa-minus bg-danger text-white p-1 btn-quitar-cif" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-id="'+categoria+'_'+i+'" data-cif ="'+fila.cif+'" data-toggle="tooltip" data-original-title="Quitar CIF" data-placement="right"></span>\
						            </td>\
						            <td class="text-center">'+fila.nivel+'</td> \
						            <td>'+fila.codigocif+'</td> \
						            <td>'+fila.ciftxt+'</td>\
						            <td class="titulo_tabla"style="width:5%;"><input class="form-control numeros_personalizado c1" maxlength="1" type="text" style="width:100%" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-cif="'+fila.cif+'" value="'+fila.c1+'"></td>';
			       		break;
			       		case "s":
			       			html +='<td class="text-center">\
						            <span class="btn fas fa-minus bg-danger text-white p-1 btn-quitar-cif" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-id="'+categoria+'_'+i+'" data-cif ="'+fila.cif+'" data-toggle="tooltip" data-original-title="Quitar CIF" data-placement="right"></span>\
						            </td> \
						            <td class="text-center">'+fila.nivel+'</td> \
						            <td>'+fila.codigocif+'</td> \
						            <td>'+fila.ciftxt+'</td>\
						            <td class="titulo_tabla"style="width:5%;"><input class="form-control numeros_personalizado c1" maxlength="1" type="text" style="width:100%" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-cif="'+fila.cif+'" value="'+fila.c1+'"></td>\
					       			<td class="titulo_tabla"style="width:5%;"><input class="form-control c2" maxlength="1" type="text" style="width:100%" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-cif="'+fila.cif+'" value="'+fila.c2+'"></td>\
				                  	<td class="titulo_tabla"style="width:5%;"><input class="form-control c3" maxlength="1" type="text" style="width:100%" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-cif="'+fila.cif+'" value="'+fila.c3+'"></td>';
			       		break;
			       		case "d":
			       		case "e":
			       			html +='<td class="text-center">\
						            <span class="btn fas fa-minus bg-danger text-white p-1 btn-quitar-cif" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-id="'+categoria+'_'+i+'" data-cif ="'+fila.cif+'" data-toggle="tooltip" data-original-title="Quitar CIF" data-placement="right"></span>\
						            </td> \
						            <td class="text-center">'+fila.nivel+'</td> \
						            <td>'+fila.codigocif+'</td> \
						            <td>'+fila.ciftxt+'</td>\
						            <td class="titulo_tabla"style="width:5%;"><input class="form-control numeros_personalizado c1" maxlength="2" type="text" style="width:100%" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-cif="'+fila.cif+'" value="'+fila.c1+'"></td>\
					       			<td class="titulo_tabla"style="width:5%;"><input class="form-control c2" maxlength="1" type="text" style="width:100%" id-item="'+fila.id+'" data-categoria="'+fila.categoria+'" data-grupo="'+fila.grupo+'" data-cif="'+fila.cif+'" value="'+fila.c2+'"></td>';
			       		break;			       		
			       	}		   		      
			      html += '</tr>';
			      i++;
			    });
			    html += '</tbody></table></br></br>';
			    select_filtro +='</select></div>'+html;
			    $('#tablaTemporalCIF_'+categoria).empty();
			    $.when($('#tablaTemporalCIF_'+categoria).html(select_filtro)).done(function(){
			    	$(".ver_cif").off();
			    	$(".btn-quitar-cif").off();
			    	$(".pestana").off();
					$("#select_filtros_cif_"+categoria).off();
					$(".numeros_personalizado").off();
					$(".c1").off();
					$(".c2").off();
					$(".c3").off();
					$("#select_filtros_cif_"+categoria).select2({placeholder:"Seleccione..."});
					$("#select_filtros_cif_"+categoria).on('change',function(){
						var cif = $(this).val();
						var grupo =$(this).select2('data')[0].element.dataset.grupo;
						var categoria = $(this).select2('data')[0].element.dataset.categoria;
						if(cif !=0){
							$(".tr_tabla[data-categoria='"+categoria+"']").hide();
							$(".tr_tabla[data-categoria='"+categoria+"']").hide();
							$(".tr_tabla[id='"+grupo+"']").show();
							$(".tr_tabla[data-cif='"+cif+"'][data-grupo='"+grupo+"'][data-categoria='"+categoria+"']").show();

						}else{
							$(".tr_tabla").show();
						}
					});
					$(".pestana").each(function(){
						$(this).on('click',function(){
							$(".select_filtros").val(0).trigger('change');
						    $(".ver_cif").removeClass('fa-eye');
							$(".ver_cif").addClass('fa-eye-slash');
						});
					});
					$('#tab_'+cat).click();
			    	$(".btn-quitar-cif").each(function(){
				        $(this).on('click',function(){
							var dataid = $(this).attr('data-id');
							var categoria = $(this).attr('data-categoria');
							var grupo = $(this).attr('data-grupo');
							var cif =$(this).attr('data-cif');
							var id =$(this).attr('id-item');
							
							$.when(eliminar_item(arreglo_cif,categoria,grupo,cif,id)).done(function (){
								$("#"+dataid).remove();
								if($("#select_filtros_cif_"+categoria).val() == cif){
									$("#select_filtros_cif_"+categoria).val(0).trigger('change');
								}
								$("#select_filtros_cif_"+categoria+" option[value='"+cif+"']").remove();
								var json = JSON.parse(arreglo_cif);
								if (json[categoria].length == 0){
									cargarTablaCIF(arreglo_cif,'-')
								}else{
									cargarTablaCIF(arreglo_cif,categoria);
								}
				        	});
				        });
				    });
				    $(".numeros_personalizado").each(function(){
						$(this).bind('keypress',function(){
						   var value = String.fromCharCode(event.which);
						   var pattern = new RegExp(/[.+0123489]/g);
						   return pattern.test(value);
						});
					});
				    $(".ver_cif").each(function(){
				    	$(this).on('click',function	(){
				    		var grupo = $(this).attr('data-id');
				    		var categoria = $(this).attr('data-categoria');
				    		//console.log("click en el boton");
				    		if($("#select_filtros_cif_"+categoria).val()==0){				    			
					    		if($(this).hasClass('fa-eye')){				    			
						    		$(this).removeClass('fa-eye');
						    		$(this).addClass('fa-eye-slash');
						    		$(".tr_tabla[id='"+grupo+"']").show();
									$(".tr_tabla[data-grupo='"+grupo+"']").show();
					    		}else{					    		
						    		$(this).removeClass('fa-eye-slash');
						    		$(this).addClass('fa-eye');
									$(".tr_tabla[data-grupo='"+grupo+"']").hide();
						    		$(".tr_tabla[id='"+grupo+"']").show();						
					    		}
				    		}
						});
				    });
					var tipoEvaluacion = $("#iddiscapacidades").val();
					$(".c1").each(function(){
						$(this).on('change',function(){
							var categoria = $(this).attr('data-categoria');
							var grupo = $(this).attr('data-grupo');
							var cif =$(this).attr('data-cif');
							var id =$(this).attr('id-item');
							var valor = $(this).val();
							var campo ="c1";
							$.when(reemplazar_valor(arreglo_cif,categoria,grupo,cif,campo,valor,id)).done(function(){
								//console.log('C1: '+arreglo_cif);
								var res = $('#resultado_CalcularFormula').html();
								//console.log('res:'+res);
								if(res != '' && res != undefined){
									if(tipoEvaluacion == 'AUDITIVA'){
										CalcularAuditiva(arreglo_cif, 'AUDITIVA');
									}else if(tipoEvaluacion == 'F??SICA'){
										CalcularFisica(arreglo_cif, 'F??SICA');
									}else if(tipoEvaluacion == 'INTELECTUAL'){
										CalcularMENTAINTELECTUAL(arreglo_cif, 'INTELECTUAL');
									}else if(tipoEvaluacion == 'MENTAL'){
										CalcularMENTAINTELECTUAL(arreglo_cif, 'MENTAL');
									}else if(tipoEvaluacion == 'VISCERAL'){
										CalcularVisceral(arreglo_cif, 'VISCERAL');
									}else if(tipoEvaluacion == 'VISUAL'){
										CalcularVisual(arreglo_cif, 'VISUAL');
									}
								}								
							});
						});
					});
					$(".c2").each(function(){
						$(this).on('change',function(){
							var categoria = $(this).attr('data-categoria');
							var grupo = $(this).attr('data-grupo');
							var cif =$(this).attr('data-cif');
							var id =$(this).attr('id-item');
							var valor = $(this).val();
							var campo ="c2";
							$.when(reemplazar_valor(arreglo_cif,categoria,grupo,cif,campo,valor,id)).done(function(){
								//console.log('C2: '+arreglo_cif);
								var res = $('#resultado_CalcularFormula').html();
								//console.log('res:'+res);
								if(res != '' && res != undefined){
									if(tipoEvaluacion == 'AUDITIVA'){
										CalcularAuditiva(arreglo_cif, 'AUDITIVA');
									}else if(tipoEvaluacion == 'F??SICA'){
										CalcularFisica(arreglo_cif, 'F??SICA');
									}else if(tipoEvaluacion == 'INTELECTUAL'){
										CalcularMENTAINTELECTUAL(arreglo_cif, 'INTELECTUAL');
									}else if(tipoEvaluacion == 'MENTAL'){
										CalcularMENTAINTELECTUAL(arreglo_cif, 'MENTAL');
									}else if(tipoEvaluacion == 'VISCERAL'){
										CalcularVisceral(arreglo_cif, 'VISCERAL');
									}else if(tipoEvaluacion == 'VISUAL'){
										CalcularVisual(arreglo_cif, 'VISUAL');
									}
								}
							});
						});
					});
					$(".c3").each(function(){
						$(this).on('change',function(){
							var categoria = $(this).attr('data-categoria');
							var grupo = $(this).attr('data-grupo');
							var cif =$(this).attr('data-cif');
							var id =$(this).attr('id-item');
							var valor = $(this).val();
							var campo ="c3";
							$.when(reemplazar_valor(arreglo_cif,categoria,grupo,cif,campo,valor,id)).done(function(){
								//console.log('C3: '+arreglo_cif);
								var res = $('#resultado_CalcularFormula').html();
								//console.log('res:'+res);
								if(res != '' && res != undefined){
									if(tipoEvaluacion == 'AUDITIVA'){
										CalcularAuditiva(arreglo_cif, 'AUDITIVA');
									}else if(tipoEvaluacion == 'F??SICA'){
										CalcularFisica(arreglo_cif, 'F??SICA');
									}else if(tipoEvaluacion == 'INTELECTUAL'){
										CalcularMENTAINTELECTUAL(arreglo_cif, 'INTELECTUAL');
									}else if(tipoEvaluacion == 'MENTAL'){
										CalcularMENTAINTELECTUAL(arreglo_cif, 'MENTAL');
									}else if(tipoEvaluacion == 'VISCERAL'){
										CalcularVisceral(arreglo_cif, 'VISCERAL');
									}else if(tipoEvaluacion == 'VISUAL'){
										CalcularVisual(arreglo_cif, 'VISUAL');
									}
								}
							});
						});
					});
			    });
			}       
		});
	}
	
	$('#evaluacion-calcular').click(function(){
		calcularEvaluacion();
	});
	
	function calcularEvaluacion(){
		//arreglo_cif = '{"b":[{"id":"1","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"2","grupotxt":"b2 | Funciones sensoriales y dolor","cif":"65","ciftxt":"Funci??n propioceptiva","codigocif":"b260","nivel":"2","c1":"3","c2":"","c3":""},{"id":"2","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"2","grupotxt":"b2 | Funciones sensoriales y dolor","cif":"1538","ciftxt":"Dolor en una extremidad inferior","codigocif":"b28015","nivel":"4","c1":"3","c2":"","c3":""},{"id":"3","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"130","ciftxt":"Funciones relacionadas con los reflejos de movimiento involuntario ","codigocif":"b755","nivel":"2","c1":"3","c2":"","c3":""},{"id":"4","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"134","ciftxt":"Funciones relacionadas con el patr??n de la marcha","codigocif":"b770","nivel":"2","c1":"3","c2":"","c3":""}],"s":[{"id":"1","categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"15","grupotxt":"s7 | Estructuras relacionadas con el movimiento","cif":"192","ciftxt":"Estructura de la extremidad superior","codigocif":"s730","nivel":"2","c1":"3","c2":"8","c3":"8"},{"id":"2","categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"15","grupotxt":"s7 | Estructuras relacionadas con el movimiento","cif":"195","ciftxt":"Estructura del tronco","codigocif":"s760","nivel":"2","c1":"3","c2":"8","c3":"8"},{"id":"3","categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"15","grupotxt":"s7 | Estructuras relacionadas con el movimiento","cif":"196","ciftxt":"Estructuras musculoesquel??ticas adicionales relacionadas con el movimiento","codigocif":"s770","nivel":"2","c1":"3","c2":"8","c3":"8"}],"d":[{"id":"1","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"225","ciftxt":"Escribir","codigocif":"d170","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"2","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"991","ciftxt":"Llevar a cabo una tarea sencilla","codigocif":"d2100","nivel":"3","c1":"2","c2":"2","c3":""},{"id":"3","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"233","ciftxt":"Llevar a cabo m??ltiples tareas","codigocif":"d220","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"4","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"258","ciftxt":"Mantener la posici??n del cuerpo","codigocif":"d415","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"5","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"267","ciftxt":"Andar","codigocif":"d450","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"6","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"22","grupotxt":"d6 | Vida dom??stica","cif":"292","ciftxt":"Realizar los quehaceres de la casa","codigocif":"d640","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"7","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"304","ciftxt":"Relaciones sociales informales","codigocif":"d750","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"8","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"25","grupotxt":"d9 | Vida comunitaria, social y c??vica","cif":"330","ciftxt":"Vida comunitaria","codigocif":"d910","nivel":"2","c1":"1","c2":"1","c3":""},{"id":"9","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"25","grupotxt":"d9 | Vida comunitaria, social y c??vica","cif":"331","ciftxt":"Tiempo libre y ocio","codigocif":"d920","nivel":"2","c1":"1","c2":"1","c3":""},{"id":"10","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"257","ciftxt":"Cambiar las posturas corporales b??sicas","codigocif":"d410","nivel":"2","c1":"2","c2":"3","c3":""}]}';
		//console.log('['+arreglo_cif+']');
		
		var tipoEvaluacion = $("#iddiscapacidades").val();
		//console.log('tipoEvaluacion: '+tipoEvaluacion);
		if(tipoEvaluacion == 0){
			swal('ERROR','Debe seleccionar un tipo de discapacidad para poder realizar la evaluaci??n','error');
			$('#iddiscapacidades').focus();
		}else if (validar_evaluacion(arreglo_cif) == 0){
			if(tipoEvaluacion == 'AUDITIVA'){
				CalcularAuditiva(arreglo_cif, 'AUDITIVA');
				//console.log('CalcularAuditiva');
			}else if(tipoEvaluacion == 'F??SICA'){
				CalcularFisica(arreglo_cif, 'F??SICA');
				//console.log('CalcularFisica');
			}else if(tipoEvaluacion == 'INTELECTUAL'){
				CalcularMENTAINTELECTUAL(arreglo_cif, 'INTELECTUAL');
				//console.log('CalcularMENTAINTELECTUAL');
			}else if(tipoEvaluacion == 'MENTAL'){
				CalcularMENTAINTELECTUAL(arreglo_cif, 'MENTAL');
				//console.log('CalcularMENTAINTELECTUAL');
			}else if(tipoEvaluacion == 'VISCERAL'){
				CalcularVisceral(arreglo_cif, 'VISCERAL');
				//console.log('CalcularVisceral');
			}else if(tipoEvaluacion == 'VISUAL'){
				CalcularVisual(arreglo_cif, 'VISUAL');
				//console.log('CalcularVisual');
			}else{
				//console.log('ninguna');
			}
		}else{
			swal('ERROR','Debe llenar todos los calificadores para poder realizar la evaluaci??n','error');
		}
	}
	
	//F??SICA
	function CalcularFisica(arregloc_cif, discapacidad){		
		var arregloc_cif = JSON.parse('['+arregloc_cif+']');
		//console.log('CalcularFisica: '+arregloc_cif);
		//arreglo_cif = [{"b":[{"categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"714","ciftxt":"Fuerza de los m??sculos de un lado del cuerpo","codigocif":"b7302","nivel":"3","c1":"2","c2":"","c3":""},{"categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"723","ciftxt":"Tono de los m??sculos de un lado del cuerpo","codigocif":"b7352","nivel":"3","c1":"2","c2":"","c3":""},{"categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"127","ciftxt":"Funciones relacionadas con la resistencia muscular","codigocif":"b740","nivel":"2","c1":"2","c2":"","c3":""},{"categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"134","ciftxt":"Funciones relacionadas con el patr??n de la marcha","codigocif":"b770","nivel":"2","c1":"2","c2":"","c3":""}],"s":[{"categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"9","grupotxt":"s1 | Estructuras del sistema nervioso","cif":"149","ciftxt":"Estructura del cerebro","codigocif":"s110","nivel":"2","c1":"8","c2":"8","c3":"8"}],"d":[{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"257","ciftxt":"Cambiar las posturas corporales b??sicas","codigocif":"d410","nivel":"2","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"258","ciftxt":"Mantener la posici??n del cuerpo","codigocif":"d415","nivel":"2","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"259","ciftxt":"Transferir el propio cuerpo","codigocif":"d420","nivel":"2","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"261","ciftxt":"Levantar y llevar objetos","codigocif":"d430","nivel":"2","c1":"1","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"278","ciftxt":"Lavarse","codigocif":"d510","nivel":"2","c1":"1","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"279","ciftxt":"Cuidado de partes del cuerpo","codigocif":"d520","nivel":"2","c1":"1","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"280","ciftxt":"Higiene personal relacionada con los procesos de excreci??n","codigocif":"d530","nivel":"2","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"22","grupotxt":"d6 | Vida dom??stica","cif":"291","ciftxt":"Preparar comidas","codigocif":"d630","nivel":"2","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"22","grupotxt":"d6 | Vida dom??stica","cif":"292","ciftxt":"Realizar los quehaceres de la casa","codigocif":"d640","nivel":"2","c1":"0","c2":"1","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"306","ciftxt":"Relaciones ??ntimas","codigocif":"d770","nivel":"2","c1":"0","c2":"0","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"320","ciftxt":"Trabajo remunerado","codigocif":"d850","nivel":"2","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"25","grupotxt":"d9 | Vida comunitaria, social y c??vica","cif":"1313","ciftxt":"Socializaci??n","codigocif":"d9205","nivel":"3","c1":"0","c2":"0","c3":""}]}];
		//arreglo_cif= [{"categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"714","ciftxt":"Fuerza de los m??sculos de un lado del cuerpo","codigocif":"b7302","nivel":"3","c1":"2","c2":"","c3":""}];
		//arregloc_cif = [{"b":[{"id":"1","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"707","ciftxt":"Movilidad de la pelvis","codigocif":"b7201","nivel":"3","c1":"3","c2":"","c3":""},{"id":"2","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"7","grupotxt":"b7 | Funciones neuromusculoesquel??ticas y relacionadas con el movimiento","cif":"134","ciftxt":"Funciones relacionadas con el patr??n de la marcha","codigocif":"b770","nivel":"2","c1":"2","c2":"","c3":""}],"s":[{"id":"1","categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"15","grupotxt":"s7 | Estructuras relacionadas con el movimiento","cif":"1636","ciftxt":"Articulaci??n de la cadera","codigocif":"s75001","nivel":"4","c1":"3","c2":"7","c3":"2"}],"d":[{"id":"1","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"257","ciftxt":"Cambiar las posturas corporales b??sicas","codigocif":"d410","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"2","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1072","ciftxt":"Permanecer sentado","codigocif":"d4153","nivel":"3","c1":"1","c2":"2","c3":""},{"id":"3","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1073","ciftxt":"Permanecer de pie","codigocif":"d4154","nivel":"3","c1":"1","c2":"2","c3":""},{"id":"4","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"278","ciftxt":"Lavarse","codigocif":"d510","nivel":"2","c1":"1","c2":"2","c3":""},{"id":"5","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"1147","ciftxt":"Cuidado de las u??as de los pies","codigocif":"d5204","nivel":"3","c1":"1","c2":"2","c3":""},{"id":"6","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"1156","ciftxt":"Ponerse la ropa","codigocif":"d5400","nivel":"3","c1":"2","c2":"3","c3":""},{"id":"7","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"22","grupotxt":"d6 | Vida dom??stica","cif":"1193","ciftxt":"Limpieza de la vivienda","codigocif":"d6402","nivel":"3","c1":"2","c2":"3","c3":""},{"id":"8","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"22","grupotxt":"d6 | Vida dom??stica","cif":"1211","ciftxt":"Ayudar a los dem??s a desplazarse","codigocif":"d6601","nivel":"3","c1":"1","c2":"2","c3":""},{"id":"9","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"1255","ciftxt":"Relaciones sexuales","codigocif":"d7702","nivel":"3","c1":"2","c2":"2","c3":""},{"id":"10","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"320","ciftxt":"Trabajo remunerado","codigocif":"d850","nivel":"2","c1":"0","c2":"0","c3":""},{"id":"11","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1071","ciftxt":"Permanecer de rodillas","codigocif":"d4152","nivel":"3","c1":"2","c2":"3","c3":""},{"id":"12","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"261","ciftxt":"Levantar y llevar objetos","codigocif":"d430","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"13","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"262","ciftxt":"Mover objetos con las extremidades inferiores","codigocif":"d435","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"14","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"267","ciftxt":"Andar","codigocif":"d450","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"15","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1114","ciftxt":"Trepar","codigocif":"d4551","nivel":"3","c1":"2","c2":"3","c3":""},{"id":"16","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1129","ciftxt":"Utilizaci??n de transporte p??blico con motor","codigocif":"d4702","nivel":"3","c1":"2","c2":"3","c3":""}]}];
		//console.log('CalcularFisica: '+arregloc_cif);
		for(i=0;i<arregloc_cif.length;i++){
			var arrb = arregloc_cif[i]['b'];
			var arrs = arregloc_cif[i]['s'];
			var arrd = arregloc_cif[i]['d'];
			//C1 - B		
			var totalbc1 = arrb.length;
			var dosb = 0;
			var unob = 0;
			var tresb = 0;
			var cuatrob = 0;
			var valorC1 = '';
			for(i = 0; i < arrb.length; i++){
				valorC1 = arrb[i]['c1'];
				valorC1 = valorC1.replace('.', '');
				valorC1 = valorC1.replace('+', '');
				
				//console.log('valorC1: '+valorC1);
				
				if(valorC1 == 1){
					unob++;
				}else if(valorC1 == 2){
					dosb++;
				}else if(valorC1 == 3){
					tresb++;
				}else if(valorC1 == 4){
					cuatrob++;
				}
			}	
			//C2 - S	
			var totalsc1 = arrs.length;
			var doss = 0;
			var ochos = 0;
			var cuatros = 0;
			var valorC2 = '';
			for(i = 0; i < arrs.length; i++){
				valorC2 = arrs[i]['c1'];
				valorC2 = valorC2.replace('.', '');
				valorC2 = valorC2.replace('+', '');
				if(valorC2 == 2){
					doss++;
				}else if(valorC2 == 8){
					ochos++;
				}
			}
			//C3 - D	
			var totaldc2 = arrd.length;
			var dosd = 0;
			var tresd = 0;
			var cuatrod = 0;
			var valorC3 = '';
			for(i = 0; i < arrd.length; i++){
				valorC3 = arrd[i]['c2'];
				valorC3 = valorC3.replace('.', '');
				valorC3 = valorC3.replace('+', '');
				if(valorC3 == 2){
					dosd++;
				}else if(valorC3 == 3){
					tresd++;
				}else if(valorC3 == 4){
					cuatrod++;
				}
			}
		}
		//REGLAS
		var nregla = '';
		var regla = 0;
		var regla1 = 0;
		var regla2 = 0;
		var regla3 = 0;
		var regla4 = 0;
		var regla5 = 0;
		var regla6 = 0;
		var regla7 = 0;
		var tc = tresb + cuatrob;
		
		//console.log('dosb: '+dosb+', doss: '+doss+', unob: '+unob);
		//console.log('totalbc1: '+totalbc1+', tc: '+tc);
		
		//REGLA 1 - Valor 2 en al menos uno de los categorizadores de FC ?? EC		
		if(dosb >= 1 || doss >= 1){
			regla1++;
			nregla = 'Valor 2 en al menos uno de los categorizadores de FC ?? EC';
			//console.log('regla1');
		}
		//REGLA 2 - Valor 3 y 4 en en todos los categorizadores de FC
		if(totalbc1 ==  tc){
			regla2++;
			nregla = 'Valor 3 y 4 en en todos los categorizadores de FC';
		}
		//REGLA 3 - Valor 1 en al menos uno de los categorizadores de FC
		if(unob >= 1){
			regla3++;
			nregla = 'Valor 1 en al menos uno de los categorizadores de FC';
		}
		//REGLA 4 - Valor 1 en al menos uno de los categorizadores de FC y 8 en EC
		if(unob > 1 && ochos > 1){
			regla4++;
			nregla = 'Valor 1 en al menos uno de los categorizadores de FC y 8 en EC';
		}
		//REGLA 5 - Valores de 3 y 4 en todos los categorizadores FC y 2 en EC
		/*
		if(totalbc1 ==  tc && doss > 0){
			regla5++;
			nregla = 'Valores de 3 y 4 en todos los categorizadores FC y 2 en EC';
		}
		*/
		//REGLA 6 - Valores de 3 y 4 en todos loscategorizadores de FC y 8 en EC
		if(totalbc1 == tc && ochos > 0){
			regla6++;
			nregla = 'Valores de 3 y 4 en todos loscategorizadores de FC y 8 en EC';
		}
		//REGLA 7 - Valores 2 en al menos uno de los categorizadores de FC y 8 en EC
		if(dosb > 1 && ochos > 0){
			regla7++;
			nregla = 'Valores 2 en al menos uno de los categorizadores de FC y 8 en EC';
		}
		
		var total = 0;	var aprobado = ''; var certificado = '';  var criterio = '';
		console.log('regla1: '+regla1+', regla2: '+regla2+', regla3: '+regla3);
		if(regla7 > 0){
			regla = '7';
			var tresycuatro = tresd + cuatrod;
			total = (tresycuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '2';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}else if(regla6 > 0){
			regla = '6';
			var dosacuatro = dosd + tresd + cuatrod;
			total = (dosacuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '1';
			}
		}else if(regla5 > 0){
			regla = '5';
			var dosacuatro = dosd + tresd + cuatrod;
			total = (dosacuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '1';
			}
		}else if(regla4 > 0){
			regla = '4';
			aprobado = 'Certifica';
			certificado = 'NO';
			criterio = 'No aplica';
		}else if(regla3 > 0){
			regla = '3';
			/*
			var tresycuatro = dosd + cuatrod;
			total = (tresycuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				aprobado = 'Certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
			*/
			aprobado = 'Certifica';
			certificado = 'NO';
			criterio = 'No aplica';
		}else if(regla1 > 0){
			regla = '1';
			var tresycuatro = tresd + cuatrod;
			console.log('tresycuatro: '+tresycuatro+', totaldc2: '+totaldc2);
			total = (tresycuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '2';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}	
		}else if(regla2 > 0){
			regla = '2';
			var dosacuatro = dosd + tresd + cuatrod;
			total = (dosacuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '1';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}
		var nombrep 	 = $('#nombre').val();
		var apellidop 	 = $('#apellido').val();
		console.log('regla: '+regla);
		$('#porcentaje1').val(total);
		$('#porcentaje2').val('');
		$('#regla').val(regla);
		$('#criterio').val(criterio);
		$('#certifica').val(certificado);
		console.log('certifica FIS: '+certificado);
		if(regla != 3 && regla != 4){
			if(total != 0){				
				$.get("controller/evaluacionback.php",{oper:'buscarobservacion',id:'1',total:total,criterio:criterio,certificado:certificado,discapacidad:discapacidad},function(response){
					$('#observaciones').val('');
					$('#observaciones').val(response);
				});
				//$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br><b>Regla: </b>'+regla+' - '+nregla+'<br>');
				$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');			
				marcarEstado(certificado);
			}else{
				//$('#certifica').val('No');
				marcarEstado('NO');
				$('#resultado_CalcularFormula').html('<b>NO APLICA</b>');
			}
		}else{
			$('#observaciones').val('');
			$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');
			marcarEstado(certificado);
		}
	}
	
	//VISCERAL
	function CalcularVisceral(arregloc_cif, discapacidad){		
		var arregloc_cif = JSON.parse('['+arregloc_cif+']');
		for(i=0;i<arregloc_cif.length;i++){
			var arrb = arregloc_cif[i]['b'];
			var arrs = arregloc_cif[i]['s'];
			var arrd = arregloc_cif[i]['d'];
			//C1 - B - FC
			var totalbc1 = arrb.length;
			var dosb = 0;
			var unob = 0;
			var tresb = 0;
			var cuatrob = 0;
			var valorC1 = '';
			for(i = 0; i < arrb.length; i++){
				valorC1 = arrb[i]['c1'];
				valorC1 = valorC1.replace('.', '');
				valorC1 = valorC1.replace('+', '');
				
				if(valorC1 == 1){
					unob++;
				} else if(valorC1 == 2){
					dosb++;
				}else if(valorC1 == 3){
					tresb++;
				}else if(valorC1 == 4){
					cuatrob++;
				}
			}
			//C2 - S - EC	
			var totalsc1 = arrs.length;
			var unos = 0;
			var doss = 0;
			var tress = 0;
			var cuatros = 0;
			var ochos = 0;
			var nueves = 0;
			var valorC2 = '';
			for(i = 0; i < arrs.length; i++){
				valorC2 = arrs[i]['c1'];
				valorC2 = valorC2.replace('.', '');
				valorC2 = valorC2.replace('+', '');
				if(valorC2 == 1){
					unos++;
				}else if(valorC2 == 2){
					doss++;
				}else if(valorC2 == 3){
					tress++;
				}else if(valorC2 == 4){
					cuatros++;
				}else if(valorC2 == 8){
					ochos++;
				}else if(valorC2 == 9){
					nueves++;
				}
			}
			//C3 - D - AyP
			var totaldc2 = arrd.length;
			var tresd = 0;
			var cuatrod = 0;
			var valorC3 = '';
			for(i = 0; i < arrd.length; i++){
				valorC3 = arrd[i]['c2'];
				valorC3 = valorC3.replace('.', '');
				valorC3 = valorC3.replace('+', '');
				if(valorC3 == 3){
					tresd++;
				}else if(valorC3 == 4){
					cuatrod++;
				}
			}
		}
		//REGLAS
		var nregla = '';
		var regla = 0;
		var regla1 = 0;
		var regla2 = 0;
		var regla3 = 0;
		var totaltcfc = tresb + cuatrob; //CUENTA LA CANTIDAD DE TRES Y CUATRO EN FC
		var totaltcec = tress + cuatros; //CUENTA LA CANTIDAD DE TRES Y CUATRO EN EC
		var totaltc	  = totaltcfc + totaltcec; //TOAL TRES Y CUATRO EN FC y EC
		var totalfcec = totalbc1 + totalsc1;

		//REGLA 1 - Valor 3 ?? 4 en en todos los categorizadores de FC y 8 o 9 en EC
		if(totalbc1 ==  totaltcfc && (ochos > 0 || nueves > 0) ){
			regla1++;
			nregla = 'Valor 3 ?? 4 en en todos los categorizadores de FC y 8 o 9 en EC';
		}
		//REGLA 2 - Valor 3 ?? 4 en en todos los categorizadores de FC y EC
		if(totaltc ==  totalfcec){
			regla2++;
			nregla = 'Valor 3 ?? 4 en en todos los categorizadores de FC y EC';
		}
		//REGLA 3 - Valor 1 o 2 en al menos uno de los categorizadores de FC o EC
		if(unob > 0 || dosb > 0 || unos > 0 || doss > 0){
			regla3++;
			nregla = 'Valor 1 o 2 en al menos uno de los categorizadores de FC o EC';
		}

		var total = 0;	var aprobado = ''; var certificado = ''; var criterio = '';

		if(regla3 > 0){
			regla = '3';
			aprobado = 'No certifica';
			certificado = 'NO';
			criterio = 'No aplica';
		}else if(regla2 > 0){
			regla = '2';
			var tresycuatro = tresd + cuatrod;
			total = (tresycuatro * 100) / totaldc2;
			if(total < 37){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '1';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}else if(regla1 > 0){
			regla = '1';
			var tresycuatro = tresd + cuatrod;
			total = (tresycuatro * 100) / totaldc2;
			if(total < 37){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '2';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}
		var nombrep 	 = $('#nombre').val();
		var apellidop 	 = $('#apellido').val();
		$('#porcentaje1').val(total);
		$('#porcentaje2').val('');
		$('#regla').val(regla);
		$('#criterio').val(criterio);
		$('#certifica').val(certificado);
		console.log('certifica VISCERAL: '+certificado);
		if(total != 0){
			//$('#certifica').val('Si');
			$.get("controller/evaluacionback.php",{oper:'buscarobservacion',id:'1',total:total,criterio:criterio,certificado:certificado,discapacidad:discapacidad},function(response){
				$('#observaciones').val('');
				$('#observaciones').val(response);
			});
			//$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br><b>Regla: </b>'+regla+' - '+nregla+'<br>');
			$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');
			marcarEstado(certificado);
		}else{
			//$('#certifica').val('No');
			marcarEstado('NO');
			$('#resultado_CalcularFormula').html('<b>No Certifica</b>');
		}
	}
	
	//VISUAL
	function CalcularVisual(arregloc_cif, discapacidad){		
		var arregloc_cif = JSON.parse('['+arregloc_cif+']');
		//var arregloc_cif = JSON.parse('[{"b":[{"categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"2","grupotxt":"b2 | Funciones sensoriales y dolor","cif":"55","ciftxt":"Funciones visuales","codigocif":"b210","nivel":"2","c1":"3","c2":"","c3":""}],"s":[{"categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"10","grupotxt":"s2 | El ojo, el o??do y estructuras relacionadas","cif":"157","ciftxt":"Estructura del globo ocular ","codigocif":"s220","nivel":"2","c1":"4","c2":"1","c3":"1"},{"categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"10","grupotxt":"s2 | El ojo, el o??do y estructuras relacionadas","cif":"157","ciftxt":"Estructura del globo ocular ","codigocif":"s220","nivel":"2","c1":"4","c2":"1","c3":"1"}],"d":[{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"205","ciftxt":"Mirar","codigocif":"d110","nivel":"2","c1":"2","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"992","ciftxt":"Llevar a cabo una tarea compleja","codigocif":"d2101","nivel":"3","c1":"3","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"19","grupotxt":"d3 | Comunicaci??n","cif":"242","ciftxt":"Comunicaci??n-recepci??n de mensajes escritos","codigocif":"d325","nivel":"2","c1":"3","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"224","ciftxt":"Leer","codigocif":"d166","nivel":"2","c1":"2","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"235","ciftxt":"Manejo del estr??s y otras demandas psicol??gicas","codigocif":"d240","nivel":"2","c1":"2","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1110","ciftxt":"Andar sorteando obst??culos","codigocif":"d4503","nivel":"3","c1":"2","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"20","ciftxt":"Movilidad","codigocif":"d4","nivel":"1","c1":"3","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1124","ciftxt":"Desplazarse fuera del hogar y de otros edificios","codigocif":"d4602","nivel":"3","c1":"3","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"1160","ciftxt":"Elecci??n de vestimenta adecuada","codigocif":"d5404","nivel":"3","c1":"3","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"299","ciftxt":"Interacciones interpersonales b??sicas","codigocif":"d710","nivel":"2","c1":"3","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"320","ciftxt":"Trabajo remunerado","codigocif":"d850","nivel":"2","c1":"3","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"323","ciftxt":"Transacciones econ??micas b??sicas","codigocif":"d860","nivel":"2","c1":"3","c2":"3","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1129","ciftxt":"Utilizaci??n de transporte p??blico con motor","codigocif":"d4702","nivel":"3","c1":"2","c2":"3","c3":""}]}]');
		//var arregloc_cif = JSON.parse('[{"b":[{"id":"1","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"2","grupotxt":"b2 | Funciones sensoriales y dolor","cif":"55","ciftxt":"Funciones visuales","codigocif":"b210","nivel":"2","c1":"3","c2":"","c3":""}],"s":[{"id":"1","categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"10","grupotxt":"s2 | El ojo, el o??do y estructuras relacionadas","cif":"157","ciftxt":"Estructura del globo ocular ","codigocif":"s220","nivel":"2","c1":"2","c2":"7","c3":"2"},{"id":"2","categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"10","grupotxt":"s2 | El ojo, el o??do y estructuras relacionadas","cif":"157","ciftxt":"Estructura del globo ocular ","codigocif":"s220","nivel":"2","c1":"4","c2":"1","c3":"1"}],"d":[{"id":"1","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"205","ciftxt":"Mirar","codigocif":"d110","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"2","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"224","ciftxt":"Leer","codigocif":"d166","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"3","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"992","ciftxt":"Llevar a cabo una tarea compleja","codigocif":"d2101","nivel":"3","c1":"3","c2":"3","c3":""},{"id":"4","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"19","grupotxt":"d3 | Comunicaci??n","cif":"242","ciftxt":"Comunicaci??n-recepci??n de mensajes escritos","codigocif":"d325","nivel":"2","c1":"3","c2":"3","c3":""},{"id":"5","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"235","ciftxt":"Manejo del estr??s y otras demandas psicol??gicas","codigocif":"d240","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"6","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1110","ciftxt":"Andar sorteando obst??culos","codigocif":"d4503","nivel":"3","c1":"3","c2":"3","c3":""},{"id":"7","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1122","ciftxt":"Desplazarse dentro de la casa","codigocif":"d4600","nivel":"3","c1":"2","c2":"3","c3":""},{"id":"8","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1124","ciftxt":"Desplazarse fuera del hogar y de otros edificios","codigocif":"d4602","nivel":"3","c1":"3","c2":"3","c3":""},{"id":"9","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"272","ciftxt":"Utilizaci??n de medios de transporte","codigocif":"d470","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"10","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"1160","ciftxt":"Elecci??n de vestimenta adecuada","codigocif":"d5404","nivel":"3","c1":"3","c2":"3","c3":""},{"id":"11","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"299","ciftxt":"Interacciones interpersonales b??sicas","codigocif":"d710","nivel":"2","c1":"3","c2":"3","c3":""},{"id":"12","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"320","ciftxt":"Trabajo remunerado","codigocif":"d850","nivel":"2","c1":"3","c2":"3","c3":""},{"id":"13","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"323","ciftxt":"Transacciones econ??micas b??sicas","codigocif":"d860","nivel":"2","c1":"3","c2":"3","c3":""}]}]');
		for(i=0;i<arregloc_cif.length;i++){
			var arrb = arregloc_cif[i]['b'];
			var arrs = arregloc_cif[i]['s'];
			var arrd = arregloc_cif[i]['d'];
			//C1 - B - FC
			var totalbc1 = arrb.length;
			var dosb = 0;
			var unob = 0;
			var tresb = 0;
			var cuatrob = 0;
			var valorC1 = '';
			for(i = 0; i < arrb.length; i++){
				valorC1 = arrb[i]['c1'];
				valorC1 = valorC1.replace('.', '');
				valorC1 = valorC1.replace('+', '');
				
				if(valorC1 == 1){
					unob++;
				} else if(valorC1 == 2){
					dosb++;
				}else if(valorC1 == 3){
					tresb++;
				}else if(valorC1 == 4){
					cuatrob++;
				}
			}
			//C2 - S - EC	
			var totalsc1 = arrs.length;
			var unos = 0;
			var doss = 0;
			var tress = 0;
			var cuatros = 0;
			var ochos = 0;
			var valorC2 = '';
			for(i = 0; i < arrs.length; i++){
				valorC2 = arrs[i]['c1'];
				valorC2 = valorC2.replace('.', '');
				valorC2 = valorC2.replace('+', '');				
				if(valorC2 == 1){
					unos++;
				}else if(valorC2 == 2){
					doss++;
				}else if(valorC2 == 3){
					tress++;
				}else if(valorC2 == 4){
					cuatros++;
				}else if(valorC2 == 8){
					ochos++;
				}				
			}
			
			//C3 - D - AyP
			var totaldc2 = arrd.length;
			var tresd = 0;
			var cuatrod = 0;
			var valorC3 = '';
			for(i = 0; i < arrd.length; i++){
				valorC3 = arrd[i]['c1'];
				valorC3 = valorC3.replace('.', '');
				valorC3 = valorC3.replace('+', '');
				if(valorC3 == 3){
					tresd++;
				}else if(valorC3 == 4){
					cuatrod++;
				}
			}
		}
		//REGLAS
		var nregla = '';
		var regla = 0;
		var regla1 = 0;
		var regla2 = 0;
		var regla3 = 0;
		var regla4 = 0;
		var totaltcfc = tresb + cuatrob; //CUENTA LA CANTIDAD DE TRES Y CUATRO EN FC
		var totaltcec = tress + cuatros; //CUENTA LA CANTIDAD DE TRES Y CUATRO EN EC
		var totaltc	  = totaltcfc + totaltcec; //TOAL TRES Y CUATRO EN FC y EC
		var totalfcec = totalbc1 + totalsc1;

		//REGLA 1 - Valor 2 en al menos uno de los categorizadores de FC ?? EC
		if(dosb > 0 || doss > 0){
			regla1++;
			nregla = 'Valor 2 en al menos uno de los categorizadores de FC y EC';
		}
		//REGLA 2 - Valor 3 ?? 4 en todos los categorizadores de FC y EC
		if(totaltc == totalfcec){
			regla2++;
			nregla = 'Valor 3 ?? 4 en todos los categorizadores de FC y EC';
		}
		//REGLA 3 - Valor 1 en al menos uno de los categorizadores de FC ?? EC
		if(unob > 0 || unos > 0 ){
			regla3++;
			nregla = 'Valor 1 en al menos uno de los categorizadores de FC y EC';
		}
		//REGLA 4 - Valores 2 en al menos uno de los categorizadores de FC y 8 en EC
		if(dosb > 0 && ochos > 0 ){
			regla4++;
			nregla = 'Valores 2 en al menos uno de los categorizadores de FC y 8 en EC';
		}

		var total = 0;	var aprobado = ''; var certificado = ''; var criterio = '';

		if(regla4 > 0){
			regla = '4';
			var tresycuatro = tresd + cuatrod;
			total = (tresycuatro * 100) / totaldc2;
			if(total < 50){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = '2';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '2';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}else if(regla3 > 0){
			regla = '3';
			aprobado = 'No certifica';
			certificado = 'NO';
			criterio = 'No aplica';
		}else if(regla2 > 0){
			regla = '2';
			aprobado = 'Certifica';
			certificado = 'SI';
			criterio = '1';
			total = 100;
		}else if(regla1 > 0){
			regla = '1';
			var tresycuatro = tresd + cuatrod;
			total = (tresycuatro * 100) / totaldc2;
			if(total < 50){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = '2';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '2';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}
		console.log('regla: '+regla+', tresycuatro: '+tresycuatro+', totaldc2:'+totaldc2+', total:'+total);
		var nombrep 	 = $('#nombre').val();
		var apellidop 	 = $('#apellido').val();
		var idobservacion = '';
		if (criterio == '1') {
			idobservacion = '3';
		}else if (criterio == '2') {
			idobservacion = '4';
		}
		$('#porcentaje1').val(total);
		$('#porcentaje2').val('');
		$('#regla').val(regla);
		$('#criterio').val(criterio);
		$('#certifica').val(certificado);
		console.log('certifica VISUAL: '+certificado);
		if(total != 0){
			if(regla == 2){
				//$('#certifica').val('Si');
				$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');
				$.get("controller/evaluacionback.php",{oper:'buscarobservacion',id:idobservacion,certificado:certificado,discapacidad:discapacidad},function(response){
					$('#observaciones').val('');
					$('#observaciones').val(response);
				});
				marcarEstado(certificado);
			}else{
				//$('#certifica').val('No');
				$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');
				$.get("controller/evaluacionback.php",{oper:'buscarobservacion',id:idobservacion,total:total,certificado:certificado,discapacidad:discapacidad},function(response){
					$('#observaciones').val('');
					$('#observaciones').val(response);
				});
				marcarEstado(certificado);
			}			
		}else{
			marcarEstado('NO');
			$('#resultado_CalcularFormula').html('<b>No Certifica</b>');
		}
	}
	
	//AUDITIVA
	function CalcularAuditiva(arregloc_cif, discapacidad){		
		var arregloc_cif = JSON.parse('['+arregloc_cif+']');
		//var arregloc_cif = JSON.parse('[{"b":[{"id":"1","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"1","grupotxt":"b1 | Funciones mentales ","cif":"479","ciftxt":"Percepci??n auditiva","codigocif":"b1560","nivel":"3","c1":"3","c2":"","c3":""},{"id":"2","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"2","grupotxt":"b2 | Funciones sensoriales y dolor","cif":"59","ciftxt":"Funciones auditivas","codigocif":"b230","nivel":"2","c1":"3","c2":"","c3":""},{"id":"3","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"3","grupotxt":"b3 | Funciones de la voz y el habla","cif":"75","ciftxt":"Funciones relacionadas con la fluidez y el ritmo del habla","codigocif":"b330","nivel":"2","c1":"1","c2":"","c3":""}],"d":[{"id":"1","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"206","ciftxt":"Escuchar","codigocif":"d115","nivel":"2","c1":"1","c2":"3","c3":""},{"id":"2","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"975","ciftxt":"Comprensi??n del lenguaje escrito","codigocif":"d1661","nivel":"3","c1":"1","c2":"2","c3":""},{"id":"3","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"209","ciftxt":"Copiar","codigocif":"d130","nivel":"2","c1":"1","c2":"1","c3":""},{"id":"4","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"19","grupotxt":"d3 | Comunicaci??n","cif":"1047","ciftxt":"Conversar con muchas personas","codigocif":"d3504","nivel":"3","c1":"2","c2":"3","c3":""},{"id":"5","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"19","grupotxt":"d3 | Comunicaci??n","cif":"1046","ciftxt":"Conversar con una sola persona","codigocif":"d3503","nivel":"3","c1":"2","c2":"3","c3":""},{"id":"6","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"305","ciftxt":"Relaciones familiares","codigocif":"d760","nivel":"2","c1":"0","c2":"0","c3":""},{"id":"7","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"325","ciftxt":"Autosuficiencia econ??mica","codigocif":"d870","nivel":"2","c1":"0","c2":"0","c3":""}],"s":[{"id":"1","categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"10","grupotxt":"s2 | El ojo, el o??do y estructuras relacionadas","cif":"161","ciftxt":"Estructura del o??do interno","codigocif":"s260","nivel":"2","c1":"3","c2":"8","c3":"3"}]}]');
		//console.log('CalcularAuditiva: '+arregloc_cif);
		for(i=0;i<arregloc_cif.length;i++){
			var arrb = arregloc_cif[i]['b'];
			var arrs = arregloc_cif[i]['s'];
			var arrd = arregloc_cif[i]['d'];
			//C1 - B - FC
			var totalbc1 = arrb.length;
			var cerob = 0;
			var unob = 0;
			var dosb = 0;
			var tresb = 0;
			var cuatrob = 0;
			var valorC1 = '';
			for(i = 0; i < arrb.length; i++){
				valorC1 = arrb[i]['c1'];
				valorC1 = valorC1.replace('.', '');
				valorC1 = valorC1.replace('+', '');
				
				if(valorC1 == 0){
					cerob++;
				} else if(valorC1 == 1){
					unob++;
				} else if(valorC1 == 2){
					dosb++;
				}else if(valorC1 == 3){
					tresb++;
				}else if(valorC1 == 4){
					cuatrob++;
				}
			}
			//C2 - S - EC	
			var totalsc1 = arrs.length;
			var ceros = 0;
			var unos = 0;
			var doss = 0;
			var tress = 0;
			var cuatros = 0;
			var ochos = 0;
			var valorC2 = '';
			for(i = 0; i < arrs.length; i++){
				valorC2 = arrs[i]['c1'];
				valorC2 = valorC2.replace('.', '');
				valorC2 = valorC2.replace('+', '');
				if(valorC2 == 0){
					ceros++;
				}else if(valorC2 == 1){
					unos++;
				}else if(valorC2 == 2){
					doss++;
				}else if(valorC2 == 3){
					tress++;
				}else if(valorC2 == 4){
					cuatros++;
				}else if(valorC2 == 8){
					ochos++;
				}
			}
			//C3 - D - AyP
			var totaldc2 = arrd.length;
			var dosd = 0;
			var tresd = 0;
			var cuatrod = 0;
			var valorC3 = '';
			for(i = 0; i < arrd.length; i++){
				valorC3 = arrd[i]['c2'];
				valorC3 = valorC3.replace('.', '');
				valorC3 = valorC3.replace('+', '');
				if(valorC3 == 2){
					dosd++;
				}else if(valorC3 == 3){
					tresd++;
				}else if(valorC3 == 4){
					cuatrod++;
				}
			}
		}
		//REGLAS
		var nregla = '';
		var regla = 0;
		var regla1 = 0;
		var regla2 = 0;
		var regla3 = 0;
		var regla4 = 0;
		var totalfcec = totalbc1 + totalsc1;
		
		var total24fc = cerob + unob + dosb + tresb + cuatrob; //CUENTA LA CANTIDAD DE CERO A CUATRO EN FC
		var total24ec = ceros + unos + doss + tress + cuatros; //CUENTA LA CANTIDAD DE CERO A CUATRO EN EC
		var total24 	= total24fc + total24ec; //TOTAL CERO A CUATRO EN FC y EC
		
		var totaldtcfc = dosb + tresb + cuatrob; //CUENTA LA CANTIDAD DE DOS A CUATRO EN FC
		var totaldtcec = doss + tress + cuatros; //CUENTA LA CANTIDAD DE DOS A CUATRO EN EC
		var totaldtc 	= totaldtcfc + totaldtcec; //TOTAL DOS A CUATRO EN FC y EC
		
		
		console.log('totaldtc: '+totaldtc+', totalfcec: '+totalfcec+', cerob: '+cerob+', ceros: '+ceros);
		//REGLA 1 - Valor 0 a 4 en FC y EC - ANTES: Valor 2 a 4 en FC y EC
		if(total24 ==  totalfcec){
			regla1++;
			nregla = 'Valor 0 a 4 en FC y EC';
		}
		//REGLA 2 - Valor 1 en al menos uno de los categorizadores de FC y EC
		/*
		if(unob > 0 && unos > 0 ){
			regla2++;
			nregla = 'Valor 1 en al menos uno de los categorizadores de FC y EC';
		}
		*/
		//REGLA 3 - Valor 8 en EC - ANTES: Valor 2 a 4 en FC y 8 en EC
		if( ochos > 0 ){ //totalbc1 == totaldtcfc && ochos > 0
			regla3++;
			nregla = 'Valor 8 en EC';
		}
		//REGLA 4 - Valor 1 en FC y de 2 a 4 en EC
		if( unob > 0 &&  totalsc1 == totaldtcec){ //unob(Si existe un valor 1 en FC) = 1, totalsc1(Cantidad de registros en EC) = 2, totaldtcec(Suma de la primera columna de 0 a 4) = 2
			regla4++;
			nregla = 'Valor 1 en FC y de 2 a 4 en EC';
		}
		//console.log("unob: "+unob+" - totalsc1: "+totalsc1+" - totaldtcec: "+totaldtcec);

		var total = 0;	var total1 = 0;	var total2 = 0;	var aprobado = ''; var certificado = ''; var criterio = '';

		if(regla4 > 0){
			regla = '4';
			total1 = (totaldtc * 100) / totalfcec; //totaldtc =2, totalfcec = 3, 2*100/3 = 66,6
			if(total1 < 37){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = 'No aplica';
			}else{
				var dosacuatro = dosd + tresd + cuatrod;
				total2 = (dosacuatro * 100) / totaldc2; //62,5%
				if(total2 < 39){
					aprobado = 'No certifica';
					certificado = 'NO';
					criterio = '1';
				}else{
					aprobado = 'Certifica';
					certificado = 'SI';
					criterio = '1';
				}
			}
			if (total1 % 1 != 0) {
				total1 = total1.toFixed(1);
			}
			if (total2 % 1 != 0) {
				total2 = total2.toFixed(1);
			}
		}else if(regla3 > 0){
			regla = '3';
			var dosacuatro = dosd + tresd + cuatrod;
			total = (dosacuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = '3';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '2';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}else if(regla2 > 0){
			regla = '2';
			aprobado = 'No certifica';
			criterio = 'No aplica';			
		}else if(regla1 > 0){
			regla = '1';
			total1 = (totaldtc * 100) / totalfcec;
			if(total1 < 37){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = '1';
				//total1 = 0;
			}else{
				var dosacuatro = dosd + tresd + cuatrod;
				total2 = (dosacuatro * 100) / totaldc2;
				if(total2 < 39){
					aprobado = 'No certifica';
					certificado = 'NO';
					criterio = '1';
				}else{
					aprobado = 'Certifica';
					certificado = 'SI';
					criterio = '1';
				}
			}
			if (total1 % 1 != 0) {
				total1 = total1.toFixed(1);
			}
			if (total2 % 1 != 0) {
				total2 = total2.toFixed(1);
			}
		}
		console.log('total: '+total+', total1: '+total1+', total2: '+total2+', regla: '+regla+', nregla: '+nregla);
		var nombrep 	 = $('#nombre').val();
		var apellidop 	 = $('#apellido').val();
		$('#porcentaje1').val(total1);
		$('#porcentaje2').val(total2);
		$('#regla').val(regla);
		$('#criterio').val(criterio);
		$('#certifica').val(certificado);
		console.log('certifica AUD: '+certificado);
		if(total != 0 || total1 != 0 || total2 != 0){
			//$('#certifica').val('Si');
			if(regla == 1 && total2 == 0){
				//
			}else{
				$.get("controller/evaluacionback.php",{oper:'buscarobservacion',id:'2',total:total,total1:total2,criterio:criterio,certificado:certificado,discapacidad:discapacidad},function(response){
					$('#observaciones').val('');
					$('#observaciones').val(response);
				});
			}
			
			if(regla == 1 || regla == 4){
				$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total2+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');
			}else{
				$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');
			}	
			marcarEstado(certificado);			
		}else{
			//$('#certifica').val('No');
			marcarEstado('NO');
			$('#resultado_CalcularFormula').html('<b>No Certifica</b>');
		}
	}
	
	//MENTAL / INTELECTUAL
	function CalcularMENTAINTELECTUAL(arregloc_cif, discapacidad){
		var menor = $("#menor_de_edad").val();
		if(menor == 0){
			CalcularMIN(arregloc_cif, discapacidad);
		}else{
			CalcularMIA(arregloc_cif, discapacidad);
		}
	}
	
	//MENTAL - INTELECTUAL NI??OS
	function CalcularMIN(arregloc_cif, discapacidad){		
		var arregloc_cif = JSON.parse('['+arregloc_cif+']');
		//var arregloc_cif = JSON.parse('[{"b":[{"categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"2","grupotxt":"b2 | Funciones sensoriales y dolor","cif":"55","ciftxt":"Funciones visuales","codigocif":"b210","nivel":"2","c1":"2","c2":"","c3":""}],"s":[{"categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"10","grupotxt":"s2 | El ojo, el o??do y estructuras relacionadas","cif":"157","ciftxt":"Estructura del globo ocular ","codigocif":"s220","nivel":"2","c1":"2","c2":"7","c3":"3"}],"d":[{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"205","ciftxt":"Mirar","codigocif":"d110","nivel":"2","c1":"2","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"224","ciftxt":"Leer","codigocif":"d166","nivel":"2","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"991","ciftxt":"Llevar a cabo una tarea sencilla","codigocif":"d2100","nivel":"3","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1122","ciftxt":"Desplazarse dentro de la casa","codigocif":"d4600","nivel":"3","c1":"0","c2":"1","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1124","ciftxt":"Desplazarse fuera del hogar y de otros edificios","codigocif":"d4602","nivel":"3","c1":"0","c2":"1","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"20","grupotxt":"d4 | Movilidad","cif":"1129","ciftxt":"Utilizaci??n de transporte p??blico con motor","codigocif":"d4702","nivel":"3","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"1160","ciftxt":"Elecci??n de vestimenta adecuada","codigocif":"d5404","nivel":"3","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"299","ciftxt":"Interacciones interpersonales b??sicas","codigocif":"d710","nivel":"2","c1":"1","c2":"2","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"320","ciftxt":"Trabajo remunerado","codigocif":"d850","nivel":"2","c1":"4","c2":"4","c3":""},{"categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"25","grupotxt":"d9 | Vida comunitaria, social y c??vica","cif":"1313","ciftxt":"Socializaci??n","codigocif":"d9205","nivel":"3","c1":"0","c2":"1","c3":""}]}]');
		for(i=0;i<arregloc_cif.length;i++){
			var arrb = arregloc_cif[i]['b'];
			var arrs = arregloc_cif[i]['s'];
			var arrd = arregloc_cif[i]['d'];
			//C1 - B - FC
			var totalbc1 = arrb.length;
			var unob = 0;
			var dosb = 0;			
			var tresb = 0;
			var cuatrob = 0;
			var valorC1 = '';
			for(i = 0; i < arrb.length; i++){
				valorC1 = arrb[i]['c1'];
				valorC1 = valorC1.replace('.', '');
				valorC1 = valorC1.replace('+', '');
				
				if(valorC1 == 1){
					unob++;
				} else if(valorC1 == 2){
					dosb++;
				}else if(valorC1 == 3){
					tresb++;
				}else if(valorC1 == 4){
					cuatrob++;
				}
			}
			//C2 - S - EC	
			var totalsc1 = arrs.length;
			var unos = 0;
			var doss = 0;
			var tress = 0;
			var cuatros = 0;
			var ochos = 0;
			var valorC2 = '';
			for(i = 0; i < arrs.length; i++){
				valorC2 = arrs[i]['c1'];
				valorC2 = valorC2.replace('.', '');
				valorC2 = valorC2.replace('+', '');
				if(valorC2 == 1){
					unos++;
				}else if(valorC2 == 2){
					doss++;
				}else if(valorC2 == 3){
					tress++;
				}else if(valorC2 == 4){
					cuatros++;
				}else if(valorC2 == 8){
					ochos++;
				}
			}
			//C3 - D - AyP
			var totaldc2 = arrd.length;
			var dosd = 0;
			var tresd = 0;
			var cuatrod = 0;
			var valorC3 = '';
			for(i = 0; i < arrd.length; i++){
				valorC3 = arrd[i]['c2'];
				valorC3 = valorC3.replace('.', '');
				valorC3 = valorC3.replace('+', '');
				if(valorC3 == 2){
					dosd++;
				}else if(valorC3 == 3){
					tresd++;
				}else if(valorC3 == 4){
					cuatrod++;
				}
			}
		}
		//REGLAS
		var nregla = '';
		var regla = 0;
		var regla1 = 0;
		var regla2 = 0;
		
		var totaludtcoec = unos + doss + tress + cuatros + ochos; //CUENTA LA CANTIDAD DE UNO A CUATRO Y OCHO EN EC
		
		var totalfcec = totalbc1 + totalsc1;		
		var totaldtcfc = dosb + tresb + cuatrob; //CUENTA LA CANTIDAD DE DOS A CUATRO EN FC
		var totaldtcoec = doss + tress + cuatros + ochos; //CUENTA LA CANTIDAD DE DOS A CUATRO Y OCHO EN EC
		var totalregla2 = totaldtcfc + totaldtcoec;

		//REGLA 1 - Valor 1 en FC y de 1 a 4 incluyendo 8 en EC
		if(unob > 0 && totalsc1 == totaludtcoec){
			regla1++;
			nregla = 'Valor 1 en FC y de 1 a 4 incluyendo 8 en EC';
		}
		//REGLA 2 - Valor 2 a 4 en  FC y EC y acepta 8 en EC
		if(totalfcec == totalregla2){
			regla2++;
			nregla = 'Valor 2 a 4 en  FC y EC y acepta 8 en EC';
		}

		var total = 0; var aprobado = ''; var certificado = ''; var criterio = '';

		if(regla2 > 0){
			regla = '2';
			var dosacuatro = dosd + tresd + cuatrod;
			total = (dosacuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = '2';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '2';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}else if(regla1 > 0){
			regla = '1';
			var dosacuatro = dosd + tresd + cuatrod;
			total = (dosacuatro * 100) / totaldc2;
			if(total < 50){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = '1';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '1';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}
		$('#porcentaje1').val(total);
		$('#porcentaje2').val('');
		$('#regla').val(regla);
		$('#criterio').val(criterio);
		$('#certifica').val(certificado);
		console.log('certifica MIN: '+certificado);
		console.log('total: '+total+', regla: '+regla+', nregla: '+nregla);
		var nombrep 	 = $('#nombre').val();
		var apellidop 	 = $('#apellido').val();
		if(total != 0){
			//$('#certifica').val('Si');
			$.get("controller/evaluacionback.php",{oper:'buscarobservacion',id:'5', total:total,criterio:criterio,certificado:certificado,discapacidad:discapacidad},function(response){
				$('#observaciones').val('');
				$('#observaciones').val(response);
			});
			$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');			
			marcarEstado(certificado);
		}else{
			//$('#certifica').val('No');
			marcarEstado('NO');
			$('#resultado_CalcularFormula').html('<b>No Certifica</b>');
		}
	}
	
	//MENTAL - INTELECTUAL ADULTOS
	function CalcularMIA(arregloc_cif, discapacidad){		
		var arregloc_cif = JSON.parse('['+arregloc_cif+']');
		//var arregloc_cif = JSON.parse('[{"b":[{"id":"1","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"1","grupotxt":"b1 | Funciones mentales ","cif":"33","ciftxt":"Funciones intelectuales","codigocif":"b117","nivel":"2","c1":"1","c2":"","c3":""},{"id":"2","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"1","grupotxt":"b1 | Funciones mentales ","cif":"34","ciftxt":"Funciones psicosociales globales","codigocif":"b122","nivel":"2","c1":"2","c2":"","c3":""},{"id":"3","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"1","grupotxt":"b1 | Funciones mentales ","cif":"429","ciftxt":"Abordabilidad","codigocif":"b1255","nivel":"3","c1":"2","c2":"","c3":""},{"id":"4","categoria":"b","categoriatxt":"FUNCIONES CORPORALES","grupo":"1","grupotxt":"b1 | Funciones mentales ","cif":"46","ciftxt":"Funciones cognitivas b??sicas","codigocif":"b163","nivel":"2","c1":"2","c2":"","c3":""}],"s":[{"id":"1","categoria":"s","categoriatxt":"ESTRUCTURAS CORPORALES","grupo":"9","grupotxt":"s1 | Estructuras del sistema nervioso","cif":"149","ciftxt":"Estructura del cerebro","codigocif":"s110","nivel":"2","c1":"8","c2":"8","c3":"8"}],"d":[{"id":"1","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"224","ciftxt":"Leer","codigocif":"d166","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"2","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"225","ciftxt":"Escribir","codigocif":"d170","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"3","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"226","ciftxt":"Calcular","codigocif":"d172","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"4","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"227","ciftxt":"Resolver problemas","codigocif":"d175","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"5","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"17","grupotxt":"d1 | Aprendizaje y aplicaci??n del conocimiento","cif":"228","ciftxt":"Tomar decisiones","codigocif":"d177","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"6","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"236","ciftxt":"Manejo del comportamiento propio","codigocif":"d250","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"7","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"991","ciftxt":"Llevar a cabo una tarea sencilla","codigocif":"d2100","nivel":"3","c1":"1","c2":"2","c3":""},{"id":"8","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"999","ciftxt":"Realizar m??ltiples tareas","codigocif":"d2200","nivel":"3","c1":"2","c2":"2","c3":""},{"id":"9","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"18","grupotxt":"d2 | Tareas y demandas generales","cif":"235","ciftxt":"Manejo del estr??s y otras demandas psicol??gicas","codigocif":"d240","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"10","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"19","grupotxt":"d3 | Comunicaci??n","cif":"251","ciftxt":"Conversaci??n","codigocif":"d350","nivel":"2","c1":"2","c2":"2","c3":""},{"id":"11","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"278","ciftxt":"Lavarse","codigocif":"d510","nivel":"2","c1":"0","c2":"1","c3":""},{"id":"12","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"281","ciftxt":"Vestirse","codigocif":"d540","nivel":"2","c1":"0","c2":"1","c3":""},{"id":"13","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"280","ciftxt":"Higiene personal relacionada con los procesos de excreci??n","codigocif":"d530","nivel":"2","c1":"0","c2":"1","c3":""},{"id":"14","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"284","ciftxt":"Cuidado de la propia salud","codigocif":"d570","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"15","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"21","grupotxt":"d5 | Autocuidado","cif":"285","ciftxt":"Cuidado de la propia seguridad","codigocif":"d571","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"16","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"299","ciftxt":"Interacciones interpersonales b??sicas","codigocif":"d710","nivel":"2","c1":"2","c2":"3","c3":""},{"id":"17","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"23","grupotxt":"d7 | Interacciones y relaciones interpersonales","cif":"305","ciftxt":"Relaciones familiares","codigocif":"d760","nivel":"2","c1":"0","c2":"0","c3":""},{"id":"18","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"24","grupotxt":"d8 | ??reas principales de la vida","cif":"1268","ciftxt":"Educaci??n escolar, otra especificada","codigocif":"d8208","nivel":"3","c1":"2","c2":"2","c3":""},{"id":"19","categoria":"d","categoriatxt":"ACTIVIDAD Y PARTICIPACI??N (Discapacidad - Desventaja)","grupo":"25","grupotxt":"d9 | Vida comunitaria, social y c??vica","cif":"1313","ciftxt":"Socializaci??n","codigocif":"d9205","nivel":"3","c1":"2","c2":"3","c3":""}]}]');
		for(i=0;i<arregloc_cif.length;i++){
			var arrb = arregloc_cif[i]['b'];
			var arrs = arregloc_cif[i]['s'];
			var arrd = arregloc_cif[i]['d'];
			//C1 - B - FC
			var totalbc1 = arrb.length;
			var unob = 0;
			var dosb = 0;			
			var tresb = 0;
			var cuatrob = 0;
			var valorC1 = '';
			for(i = 0; i < arrb.length; i++){
				valorC1 = arrb[i]['c1'];
				valorC1 = valorC1.replace('.', '');
				valorC1 = valorC1.replace('+', '');
				
				if(valorC1 == 1){
					unob++;
				} else if(valorC1 == 2){
					dosb++;
				}else if(valorC1 == 3){
					tresb++;
				}else if(valorC1 == 4){
					cuatrob++;
				}
			}
			//C2 - S - EC	
			var totalsc1 = arrs.length;
			var unos = 0;
			var doss = 0;
			var tress = 0;
			var cuatros = 0;
			var ochos = 0;
			var valorC2 = '';
			for(i = 0; i < arrs.length; i++){
				valorC2 = arrs[i]['c1'];
				valorC2 = valorC2.replace('.', '');
				valorC2 = valorC2.replace('+', '');
				if(valorC2 == 1){
					unos++;
				}else if(valorC2 == 2){
					doss++;
				}else if(valorC2 == 3){
					tress++;
				}else if(valorC2 == 4){
					cuatros++;
				}else if(valorC2 == 8){
					ochos++;
				}
			}
			//C3 - D - AyP
			var totaldc2 = arrd.length;
			var dosd = 0;
			var tresd = 0;
			var cuatrod = 0;
			var valorC3 = '';
			for(i = 0; i < arrd.length; i++){
				valorC3 = arrd[i]['c2'];
				valorC3 = valorC3.replace('.', '');
				valorC3 = valorC3.replace('+', '');
				if(valorC3 == 2){
					dosd++;
				}else if(valorC3 == 3){
					tresd++;
				}else if(valorC3 == 4){
					cuatrod++;
				}
			}
		}
		//REGLAS
		var nregla = '';
		var regla = 0;
		var regla1 = 0;
		var regla2 = 0;
		
		var totaludtcoec = unos + doss + tress + cuatros + ochos; //CUENTA LA CANTIDAD DE UNO A CUATRO Y OCHO EN ECz
		
		var totalfcec = totalbc1 + totalsc1;		
		var totaldtcfc = dosb + tresb + cuatrob; //CUENTA LA CANTIDAD DE DOS A CUATRO EN FC
		var totaldtcoec = doss + tress + cuatros + ochos; //CUENTA LA CANTIDAD DE DOS A CUATRO Y OCHO EN EC
		var totalregla2 = totaldtcfc + totaldtcoec;

		//REGLA 1 - Valor 1 en FC y de 1 a 4 incluyendo 8 en EC
		if(unob > 0 && totalsc1 == totaludtcoec){
			regla1++;
			nregla = 'Valor 1 en FC y de 1 a 4 incluyendo 8 en EC';
		}
		//REGLA 2 - Valor 2 a 4 en  FC y EC y acepta 8 en EC
		if(totalfcec == totalregla2){
			regla2++;
			nregla = 'Valor 2 a 4 en  FC y EC y acepta 8 en EC';
		}

		var total = 0; var aprobado = ''; var certificado = ''; var criterio = '';

		if(regla2 > 0){
			regla = '2';
			var tresycuatro = tresd + cuatrod;
			total = (tresycuatro * 100) / totaldc2;
			if(total < 39){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = '2';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '2';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}else if(regla1 > 0){
			regla = '1';
			var tresycuatro = tresd + cuatrod;
			total = (tresycuatro * 100) / totaldc2;
			if(total < 50){
				aprobado = 'No certifica';
				certificado = 'NO';
				criterio = '1';
			}else{
				aprobado = 'Certifica';
				certificado = 'SI';
				criterio = '1';
			}
			if (total % 1 != 0) {
				total = total.toFixed(1);
			}
		}
		//console.log('total: '+total+', regla: '+regla+', nregla: '+nregla);
		var nombrep 	 = $('#nombre').val();
		var apellidop 	 = $('#apellido').val();
		$('#porcentaje1').val(total);
		$('#porcentaje2').val('');
		$('#regla').val(regla);
		$('#criterio').val(criterio);
		$('#certifica').val(certificado);
		console.log('certifica: '+certificado);
		if(total != 0){
			//$('#certifica').val('Si');
			$.get("controller/evaluacionback.php",{oper:'buscarobservacion',id:'5', total:total,criterio:criterio,certificado:certificado,discapacidad:discapacidad},function(response){
				$('#observaciones').val('');
				$('#observaciones').val(response);
			});
			$('#resultado_CalcularFormula').html('El usuario <b>'+nombrep+' '+apellidop+' '+certificado+' ha sido Certificado</b> para la discapacidad <b>'+discapacidad+'</b> con un porcentaje de <b>'+total+'%</b><br><b>Cr??terio aplicado: </b>'+criterio+'<br>');			
			marcarEstado(certificado);
		}else{
			//$('#certifica').val('No');
			marcarEstado('NO');
			$('#resultado_CalcularFormula').html('<b>No Certifica</b>');
		}
	}
	
	$("#reiniciar-calculo").click(function(){
		reiniciarCalculo();
	});
	
	function reiniciarCalculo(){
		swal({
			title: "Confirmar",
			text: `Al reiniciar el c??lculo, se eliminar??n los c??digos de la evaluaci??n, est?? seguro ?`,
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm.value === true) {
					$("#tablaTemporalCIF_b, #tablaTemporalCIF_s, #tablaTemporalCIF_d, #tablaTemporalCIF_e, #resultado_CalcularFormula").html('');
					$('#select_categoria_cif, #select_grupo_cif, #select_cif').val('0').trigger('change');
					arreglo_cif = '[]';
					arreglo_cif.length = 0;
				} 				
			}
		);
		
	}
	
	function marcarEstado(certificado){
		if(certificado == 'SI'){
			estados(3);
		}else if(certificado == 'NO'){
			estados(4);
		}
	}
	
	function buscar_id(json,categoria,grupo,cif,id){
		var encontrado = 0;
		json = JSON.parse(json);
		if(json[categoria] !== undefined){
			for(var i = 0; i < json[categoria].length; i++){
				if(json[categoria][i]['grupo'] == grupo && json[categoria][i]['cif'] == cif && json[categoria][i]['id'] == id){    
					encontrado = 1;
				}
			}
		}
		return encontrado;
	}
	
	function buscar_valor(json,categoria,grupo,cif,id){
		console.log('buscar_valor -- json: '+json+'. categoria: '+categoria+'. grupo: '+grupo+'. cif: '+cif+'. id: '+id);
		var encontrado = 0;
		json = JSON.parse(json);
		if(json != null && json[categoria] !== undefined){
			for(var i = 0; i < json[categoria].length; i++){
				if(json[categoria][i]['grupo'] == grupo && json[categoria][i]['cif'] == cif && json[categoria][i]['id'] == id){    
					encontrado = 1;
				}
			}
		}
		return encontrado;
	}
 
	function validar_evaluacion(json){
		var campo_vacio = 0;
		json = JSON.parse(json);
		if(json['b'] !== undefined){
			for(var i = 0; i < json['b'].length; i++){
				if(json['b'][i]['c1'] == ''){    
					campo_vacio = 1;
				}
			}
		}
		if(json['s'] !== undefined){
			for(var i = 0; i < json['s'].length; i++){
				if(json['s'][i]['c1'] == '' || json['s'][i]['c2'] == '' || json['s'][i]['c3'] == '' ){    
					campo_vacio = 1;
				}
			}
		}
		if(json['d'] !== undefined){
			for(var i = 0; i < json['d'].length; i++){
				if(json['d'][i]['c1'] == '' || json['d'][i]['c2'] == '' ){    
					campo_vacio = 1;
				}
			}
		}

		if(json['e'] !== undefined){
			for(var i = 0; i < json['e'].length; i++){
				if(json['e'][i]['c1'] == '' || json['e'][i]['c2'] == '' ){    
					campo_vacio = 1;
				}
			}
		}
		return campo_vacio;
	}
	    
	function eliminar_item(json,categoria,grupo,cif,id){
		console.log('eliminar_item -- json: '+json+'. categoria: '+categoria+'. grupo: '+grupo+'. cif: '+cif+'. id: '+id);
		json = JSON.parse(json);
		for(var i = 0; i < json[categoria].length; i++){
			if(json[categoria][i].grupo == grupo && json[categoria][i].cif == cif && json[categoria][i].id == id){ 
				json[categoria].splice(i, 1);
			}
		}
		arreglo_cif = JSON.stringify(json);
		localStorage.setItem('arreglo_cif',arreglo_cif);
	}

	function reemplazar_valor(json,categoria,grupo,cif,campo,valor,id){
		//console.log('reemplazar_valor -- json: '+json+'. categoria: '+categoria+'. grupo: '+grupo+'. cif: '+cif+'. id: '+id);
		json = JSON.parse(json);
		for(var i = 0; i < json[categoria].length; i++){
			if(json[categoria][i]['grupo'] == grupo && json[categoria][i]['cif'] == cif && json[categoria][i]['id'] == id){    
				json[categoria][i][campo] = valor;
			}
		}
		arreglo_cif = JSON.stringify(json);
		localStorage.setItem('arreglo_cif',arreglo_cif);
	}

	function guardarCif(idevaluacion,tipo){
		console.log(`guardarCif es ${idevaluacion} - tipo es ${tipo}`);
		$.ajax({
		type: 'post',
		url: 'controller/evaluacionback.php',
		data: { 
			'oper'	: 'guardarCif',
			'cif' 	: arreglo_cif,
			'id'	: idevaluacion
		},
		success: function (response) {
			$('#overlay').css('display','none');
			if(response == 1){
				if (tipo == '1') {
					$.when(swal("Buen trabajo","Evaluaci??n creada satisfactoriamente","success")).done(function(){
					    location.href='solicitudes.php';
					});
				}else{
					$.when(swal("Buen trabajo","Evaluaci??n modificada satisfactoriamente","success")).done(function(){
					    location.href='solicitudes.php';
					});
				}
			}else{
				swal('ERROR','Ocurri?? un error al guardar el C??digo Cif','error');	
			}
		},
		error: function () {
			swal('ERROR','Ha ocurrido un error al guardar, por favor intente m??s tarde','error');	
			$('#overlay').css('display','none');
		}
	});
	}