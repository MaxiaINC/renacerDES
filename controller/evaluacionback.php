<?php
    include("conexion.php");
    sessionrestore();
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargar": 
			  cargar();
			  break;
		case "consulta": 
			  consulta();
			  break;
		case "get": //
			  get();
			  break;
		case "getnombreacompanante": 
			  getnombreacompanante();
			  break;
		case "evaluacionesporsolicitudes": 
			  evaluacionesporsolicitudes();
			  break;
		case "guardar": 
			  guardar();
			  break;
		case "update": 
			  update();
			  break;
		case "guardarCif": 
			  guardarCif();
			  break;
		case "buscarobservacion": 
			  buscarobservacion();
			  break;
		case "delete": 
			  delete();
			  break;
		case "eliminar_evaluacion":
		      eliminar_evaluacion();
		      break;	  
		case "existe":
			  existe();
			  break;
	    case "checkpacienteseguro":
			  checkpacienteseguro();
			  break;
		case "getEstadoSolicitud":
			  getEstadoSolicitud();
			  break;
		/* case "guardarEstudiosComplementarios":
			  guardarEstudiosComplementarios();
			  break; */ 
		default:
			  echo "{failure:true}";
			  break;
	}	
	
	function cargar(){
		global $mysqli;
		$query = " SELECT m.id,m.cedula,m.nombre,m.apellido, e.nombre as especialidad,m.telefonocelular,m.correo FROM medicos m
					LEFT JOIN especialidades e ON e.id = m.especialidad";
		$result = $mysqli->query($query);
		$resultado = '';
		while($row = $result->fetch_assoc()){
			$editar ="<span class='icon-col blue fa fa-pencil boton-editar' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Editar Médico' data-placement='right'></span>";
			$eliminar = "<span class='icon-col red fa fa-trash boton-eliminar' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar Médico' data-placement='right'></span>";
			$resultado['data'][] = array(
				'id' 						=>	$row['id'],
				'acciones' 					=>	"<div style='float:left;margin-left:0px;' class='ui-pg-div ui-inline-custom'>
													$eliminar
												</div>",
				'nombre' 					=>	$row['nombre']." ".$row['apellido'],
				'cedula'	 				=>	$row['cedula'],
				'especialidad' 				=>	$row['especialidad'],
				'telefonocelular'	 		=>	$row['telefonocelular'],
				'correo'	 				=>	$row['correo']
			);
		}
		
		echo json_encode($resultado);
	}	

	function consulta(){
		global $mysqli;
		
		$id 	= $_REQUEST['idpaciente'];
		$query = " 	SELECT e.id, e.tipodiscapacidad, e.fechaemision,es.descripcion as estatus, e.idsolicitud
				   	FROM evaluacion e
				   	INNER JOIN pacientes p ON p.id = e.idpaciente
				   	LEFT JOIN estados es ON es.id = e.estatus
					WHERE p.id = '$id' AND e.idsolicitud is not false ";
		$result = $mysqli->query($query);
		$resultado 	 = array();
		while($row = $result->fetch_assoc()){
		    $accion_evaluacion = "	<div><span class='icon-col blue fa fa-list boton-evaluacion' data-id='".$row['id']."' data-toggle='tooltip' data     -original-title='Ir a evaluación' data-placement='right'></span>
		    
		    						<span class='icon-col blue fa fa-folder boton-imprimirprotocolo' data-id='".$row['idsolicitud'].'-'.$row['id']."' data-toggle='tooltip' data-original-title='Imprimir protocolo' data-placement='right'></span>
		    						
		    						<span class='icon-col red fa fa-trash boton-eliminar_evaluacion' data-id='".$row['id']."' data-toggle='tooltip' data-original-title='Eliminar evaluación' data-placement='right'></span></div>";
		   
			$resultado['data'][] = array(
				'id' 			=>	$row['id'],
				'acciones'      =>  $accion_evaluacion,
				'fecha'	 		=>	$row['fechaemision'],
				'discapacidad' 	=>	$row['tipodiscapacidad'],
				'estatus' 		=>	$row['estatus'],
			);
		}
		if(empty($resultado)){
    		$resultado['data'][] = array(				
    			'id' => "",'acciones'=>"", 'fecha' => "", 'discapacidad' => "", 'estatus' => ""
    		);
    	}		
		echo json_encode($resultado);
	}	
	
	function get(){
		global $mysqli;
		$id 	= (!empty($_REQUEST['idevaluacion']) ? $_REQUEST['idevaluacion'] : '');
		$query 	= "	SELECT e.tipodiscapacidad, e.tiposolicitud, e.horainicio, e.horafinal, e.documentos, e.diagnostico, 
					e.codigojunta, e.fechainiciodano, e.ayudatecnica, e.ayudatecnicaotro, e.alfabetismo, e.niveleducacional, 
					e.niveleducacionalcompletado, e.niveleducacionalincompleto, e.concurrenciaeducacionalcompletado, 
					e.tipoeducacion, e.concurrenciatipoeducacion, e.convivencia, e.tipovivienda, e.viviendaadaptada, 
					e.mediotransporte, e.estadocalles, e.vinculos, e.etnia, e.religion, e.ingresomensual, e.ingresomensualotro, 
					e.acompanante, e.nombreacompanante, e.observaciones, e.duracion, e.tipoduracion, e.fechavencimiento, e.fechaemision, 
					e.ciudad, s.estatus, p.nombre, CONCAT(p.apellidopaterno,' ',p.apellidomaterno) as apellido, p.cedula,
					p.fecha_nac as fecha_nac, e.cif, e.adecuacion,e.idsolicitud,e.cantidadhabitaciones,e.porcentaje1, e.porcentaje2,
					e.criterio,e.regla,e.certifica, e.resultadoFormula, DATE_FORMAT(s.fecha_cita, '%Y-%m-%d') AS fecha_cita,
					e.concurrircon, e.estudioscomplementarios, e.modalidad
					FROM evaluacion e
					INNER JOIN solicitudes s ON e.idsolicitud = s.id
					inner join pacientes p on p.id = e.idpaciente
					WHERE e.id = '$id' ";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$vardiagnostico = $row['diagnostico'];
			$arrdiagnostico = explode(',',$vardiagnostico);
			$uniqdiagnostico = array_filter(array_unique($arrdiagnostico), "strlen");
			$diagnostico = implode(',',$uniqdiagnostico);
			
			$resultado = array(
				'nombre' 				=>	$row['nombre'],
				'apellido' 				=>	$row['apellido'],			
				'cedula' 				=>	$row['cedula'],		
				'fecha_nac' 			=>	$row['fecha_nac'],		
				'tipodiscapacidad' 		=>	$row['tipodiscapacidad'],
				'tiposolicitud' 		=>	$row['tiposolicitud'],
				'horainicio' 			=>	$row['horainicio'],
				'horafinal' 			=>	$row['horafinal'],
				'documentos'			=> 	$row['documentos'],
				'diagnostico'			=> 	$diagnostico,
				'codigojunta' 			=>	$row['codigojunta'],
				'fechainiciodano' 		=>	$row['fechainiciodano'],
				'ayudatecnica' 			=>	$row['ayudatecnica'],
				'ayudatecnicaotro' 		=>	$row['ayudatecnicaotro'],
				'alfabetismo' 			=>	$row['alfabetismo'],
				'niveleducacional' 				=>	$row['niveleducacional'],
				'niveleducacionalcompletado' 	=>	$row['niveleducacionalcompletado'],
				'niveleducacionalincompleto'	=> 	$row['niveleducacionalincompleto'],
				'concurrenciaeducacionalcompletado'	=> 	$row['concurrenciaeducacionalcompletado'],
				'tipoeducacion' 				=>	$row['tipoeducacion'],
				'concurrenciatipoeducacion' 	=>	$row['concurrenciatipoeducacion'],
				'convivencia' 			=>	$row['convivencia'],
				'tipovivienda' 			=>	$row['tipovivienda'],
				'viviendaadaptada' 		=>	$row['viviendaadaptada'],
				'mediotransporte' 		=>	$row['mediotransporte'],
				'estadocalles' 			=>	$row['estadocalles'],
				'vinculos'				=> 	$row['vinculos'],
				'etnia'					=> 	$row['etnia'],
				'religion' 				=>	$row['religion'],
				'ingresomensual' 		=>	$row['ingresomensual'],
				'ingresomensualotro' 	=>	$row['ingresomensualotro'],
				'acompanante' 			=>	$row['acompanante'],
				'nombreacompanante' 	=>	$row['nombreacompanante'],
				'observaciones' 		=>	$row['observaciones'],
				'duracion' 				=>	$row['duracion'],
				'tipoduracion' 			=>	$row['tipoduracion'],
				'fechavencimiento' 		=>	$row['fechavencimiento'],
				'fechaemision'			=> 	$row['fechaemision'],
				'fecha_cita'			=> 	$row['fecha_cita'],
				'ciudad'				=> 	$row['ciudad'],
				'idestados'				=> 	$row['estatus'],
				'cif' 					=> 	$row['cif'],
				'adecuacion'			=> 	$row['adecuacion'],
				'idsolicitud' 			=> 	$row['idsolicitud'],
				'cantidadhabitaciones' 	=> 	$row['cantidadhabitaciones'],
				'porcentaje1' 			=> 	$row['porcentaje1'],
				'porcentaje2' 			=> 	$row['porcentaje2'],
				'criterio' 				=> 	$row['criterio'],
				'regla' 				=> 	$row['regla'],
				'certifica' 			=> 	$row['certifica'],
				'resultadoFormula' 		=> 	$row['resultadoFormula'],
				'concurrircon' 			=> 	$row['concurrircon'],
				'estudioscomplementarios' 	=> 	$row['estudioscomplementarios'],
				'modalidad' 			=> 	$row['modalidad']
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	

	function getnombreacompanante(){
		global $mysqli;
		$id 	= $_REQUEST['idpaciente'];
		$query 	= "	SELECT concat(a.nombre,' ',a.apellido) as nombre from acompanantes a
						inner join pacientes p on a.id = p.idacompanante
						inner join evaluacion e on e.idpaciente = p.id
						where p.id ='$id'";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			
			$resultado = array(
				'nombre' 				=>	$row['nombre'],
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	

	function evaluacionesporsolicitudes(){
		global $mysqli;
		$idsolicitud = $_REQUEST['idsolicitud'];
		$query = "	SELECT e.id
					FROM evaluacion e
					WHERE e.idsolicitud = '$idsolicitud' LIMIT 1;";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}
	
	function delete(){
		global $mysqli;
		
		$id 	= $_REQUEST['id'];
		$query 	= "DELETE FROM medicos WHERE id = '$id'";
		$result = $mysqli->query($query);
		
		echo 1;
	}
	
	function eliminar_evaluacion(){
		global $mysqli;
		
		$id 	= $_REQUEST['id'];
		$query 	= "DELETE FROM evaluacion WHERE id = '$id'";
		$result = $mysqli->query($query);
		
		echo 1;
	}
	
	function update(){
		global $mysqli;
		$id 									= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$tipodiscapacidad 						= (!empty($_REQUEST['tipodiscapacidad']) ? $_REQUEST['tipodiscapacidad'] : '');
		$tiposolicitud 							= (!empty($_REQUEST['tiposolicitud']) ? $_REQUEST['tiposolicitud'] : '0');
		$horainicio 							= (!empty($_REQUEST['horainicio']) ? $_REQUEST['horainicio'] : '');
		$horafinal 								= (!empty($_REQUEST['horafinal']) ? $_REQUEST['horafinal'] : '');
		$documentos 							= (!empty($_REQUEST['documentos']) ? $_REQUEST['documentos'] : '');
		$diagnosticos 							= (!empty($_REQUEST['diagnosticos']) ? $_REQUEST['diagnosticos'] : '');
		$codigojunta 							= (!empty($_REQUEST['codigojunta']) ? $_REQUEST['codigojunta'] : '');
		$fechainiciodano 						= (!empty($_REQUEST['fechainiciodano']) ? $_REQUEST['fechainiciodano'] : '');
		$ayudatecnica 							= (!empty($_REQUEST['ayudatecnica']) ? $_REQUEST['ayudatecnica'] : '0');
		$ayudatecnicaotro 						= (!empty($_REQUEST['ayudatecnicaotro']) ? $_REQUEST['ayudatecnicaotro'] : '');
		$alfabetismo 							= (!empty($_REQUEST['alfabetismo']) ? $_REQUEST['alfabetismo'] : '0');
		$niveleducacional 						= (!empty($_REQUEST['niveleducacional']) ? $_REQUEST['niveleducacional'] : '0');
		$niveleducacionalcompletado 			= (!empty($_REQUEST['niveleducacionalcompletado']) ? $_REQUEST['niveleducacionalcompletado'] : '0');
		$niveleducacionalincompleto 			= (!empty($_REQUEST['niveleducacionalincompleto']) ? $_REQUEST['niveleducacionalincompleto'] : '0');
		$concurrenciaeducacionalcompletado 		= (!empty($_REQUEST['concurrenciaeducacionalcompletado']) ? $_REQUEST['concurrenciaeducacionalcompletado'] : '0');
		$tipoeducacion 							= (!empty($_REQUEST['tipoeducacion']) ? $_REQUEST['tipoeducacion'] : '0');
		$concurrenciatipoeducacion 				= (!empty($_REQUEST['concurrenciatipoeducacion']) ? $_REQUEST['concurrenciatipoeducacion'] : '0');
		$convivencia 							= (!empty($_REQUEST['convivencia']) ? $_REQUEST['convivencia'] : '0');
		$tipovivienda 							= (!empty($_REQUEST['tipovivienda']) ? $_REQUEST['tipovivienda'] : '0');
		$viviendaadaptada 						= (!empty($_REQUEST['viviendaadaptada']) ? $_REQUEST['viviendaadaptada'] : '0');
		$mediotransporte 						= (!empty($_REQUEST['mediotransporte']) ? $_REQUEST['mediotransporte'] : '0');
		$estadocalles 							= (!empty($_REQUEST['estadocalles']) ? $_REQUEST['estadocalles'] : '0');
		$vinculos 								= (!empty($_REQUEST['vinculos']) ? $_REQUEST['vinculos'] : '');
		$etnia 									= (!empty($_REQUEST['etnia']) ? $_REQUEST['etnia'] : '');
		$religion 								= (!empty($_REQUEST['religion']) ? $_REQUEST['religion'] : '');
		$ingresomensual 						= (!empty($_REQUEST['ingresomensual']) ? $_REQUEST['ingresomensual'] : '0');
		$ingresomensualotro 					= (!empty($_REQUEST['ingresomensualotro']) ? $_REQUEST['ingresomensualotro'] : '');
		$acompanante 							= (!empty($_REQUEST['acompanante']) ? $_REQUEST['acompanante'] : '0');
		$nombreacompanante 						= (!empty($_REQUEST['nombreacompanante']) ? $_REQUEST['nombreacompanante'] : '');
		$observaciones 							= (!empty($_REQUEST['observaciones']) ? $_REQUEST['observaciones'] : '');
		$fechavencimiento 						= (!empty($_REQUEST['fechavencimiento']) ? $_REQUEST['fechavencimiento'] : '');
		$cantidadvencimiento 					= (!empty($_REQUEST['cantidadvencimiento']) ? $_REQUEST['cantidadvencimiento'] : 0);
		$tipovencimiento 						= (!empty($_REQUEST['tipovencimiento']) ? $_REQUEST['tipovencimiento'] : '');
		$fechaemision 							= (!empty($_REQUEST['fechaemision']) ? $_REQUEST['fechaemision'] : '');
		$ciudad 								= (!empty($_REQUEST['ciudad']) ? $_REQUEST['ciudad'] : '');
		$idestados 								= (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : '');
		$adecuacion 							= (!empty($_REQUEST['adecuacion']) ? $_REQUEST['adecuacion'] : '');
		$cantidadhabitaciones					= (!empty($_REQUEST['cantidadhabitaciones']) ? $_REQUEST['cantidadhabitaciones'] : '0');
		$porcentaje1							= (!empty($_REQUEST['porcentaje1']) ? $_REQUEST['porcentaje1'] : '');
		$porcentaje2							= (!empty($_REQUEST['porcentaje2']) ? $_REQUEST['porcentaje2'] : '');
		$criterio								= (!empty($_REQUEST['criterio']) ? $_REQUEST['criterio'] : '');
		$regla									= (!empty($_REQUEST['regla']) ? $_REQUEST['regla'] : '');
		$certifica								= (!empty($_REQUEST['certifica']) ? $_REQUEST['certifica'] : '');
		$resultadoFormula						= (!empty($_REQUEST['resultadoFormula']) ? $_REQUEST['resultadoFormula'] : '');
		$concurrircon							= (!empty($_REQUEST['concurrircon']) ? $_REQUEST['concurrircon'] : '');
		$estudioscomplementarios				= (!empty($_REQUEST['estudioscomplementarios']) ? $_REQUEST['estudioscomplementarios'] : '');
		$modalidad								= (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');
		//debug($diagnosticos);
		$diagnosticos = str_replace('null','',$diagnosticos);
		$idsolicitud  = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$idpaciente   = (!empty($_REQUEST['idpaciente']) ? $_REQUEST['idpaciente'] : '');
		//$observaciones = str_replace(';',',',$observaciones);
		
		$valoresold = getRegistroSQL("	SELECT e.tipodiscapacidad AS 'Tipo de discapacidad', 
										e.tiposolicitud AS 'Tipo de solicitud',
										e.horainicio AS 'Hora de inicio',
										e.horafinal AS 'Hora final', 
										e.documentos AS 'Documentos',
										e.diagnostico AS 'Diagnósticos',
										e.codigojunta AS 'Código de junta',
										e.fechainiciodano AS 'Fecha de inicio de daño',
										e.ayudatecnica AS 'Ayuda técnica',
										e.ayudatecnicaotro AS 'Ayuda técnica otro',	
										e.alfabetismo AS 'Alfabetismo',
										e.niveleducacional AS 'Nivel educacional', 
										e.niveleducacionalcompletado AS 'Nivel educacional completado', 
										e.niveleducacionalincompleto AS 'Nivel educacional incompleto',
										e.concurrenciaeducacionalcompletado AS 'Concurrencia educacional completada', 
										e.tipoeducacion AS 'Tipo de educación',
										e.concurrenciatipoeducacion AS 'Concurrencia del tipo de educación',
										e.convivencia AS 'Convivencia', 
										e.tipovivienda AS 'Tipo de vivienda',
										e.viviendaadaptada AS 'Vivienda adaptada',
										e.mediotransporte AS 'Medio de transporte',
										e.estadocalles AS 'Estado de las calles', 
										e.vinculos AS 'Vínculos',
										e.etnia AS 'Etnia',
										e.religion AS 'Religión',
										e.ingresomensual AS 'Ingreso mensual',
										e.ingresomensualotro AS 'Otro ingreso mensual',
										e.acompanante AS 'Acompañante', 
										e.nombreacompanante AS 'Nombre del/la acompañante',
										e.observaciones AS 'Observaciones', 
										e.duracion AS 'Duración',
										e.tipoduracion AS 'Tipo de duración',
										e.fechavencimiento AS 'Fecha de vencimiento', 
										e.fechaemision AS 'Fecha de emisión',
										e.ciudad AS 'Ciudad',
										e.adecuacion AS 'Adecuación',
										e.cantidadhabitaciones AS 'Cantidad de habitaciones',
										e.porcentaje1 AS 'Porcentaje 1',
										e.porcentaje2 AS 'Porcentaje 2',
										e.criterio AS 'Criterio', 
										e.regla AS 'Regla', 
										e.certifica AS 'Certifica', 
										e.resultadoFormula AS 'Resultado de la formula',
										e.concurrircon AS 'Concurrir con',
										e.estudioscomplementarios AS 'Estudios complementarios',
										e.modalidad AS 'Modalidad'
									FROM evaluacion e 
									WHERE e.id = '".$id."' ");
		
		$query 	= " UPDATE evaluacion SET tipodiscapacidad = '".$tipodiscapacidad."', tiposolicitud = '".$tiposolicitud."', 
					horainicio = '".$horainicio."', horafinal = '".$horafinal."', documentos = '".$documentos."', 
					diagnostico = '".$diagnosticos."', codigojunta = '".$codigojunta."', fechainiciodano = '".$fechainiciodano."', 
					ayudatecnica = '".$ayudatecnica."', ayudatecnicaotro = '".$ayudatecnicaotro."', alfabetismo = '".$alfabetismo."', 
					niveleducacional = '".$niveleducacional."', niveleducacionalcompletado = '".$niveleducacionalcompletado."', 
					niveleducacionalincompleto = '".$niveleducacionalincompleto."', 
					concurrenciaeducacionalcompletado = '".$concurrenciaeducacionalcompletado."', tipoeducacion = '".$tipoeducacion."', 
					concurrenciatipoeducacion = '".$concurrenciatipoeducacion."', convivencia = '".$convivencia."', 
					tipovivienda = '".$tipovivienda."', viviendaadaptada = '".$viviendaadaptada."', mediotransporte = '".$mediotransporte."',
					estadocalles = '".$estadocalles."', vinculos = '".$vinculos."', etnia = '".$etnia."', religion = '".$religion."', 
					ingresomensual = '".$ingresomensual."', ingresomensualotro = '".$ingresomensualotro."', acompanante = '".$acompanante."',
					nombreacompanante = '".$nombreacompanante."', observaciones = '".$observaciones."', duracion = '".$cantidadvencimiento."',
					tipoduracion = '".$tipovencimiento."', fechavencimiento = '".$fechavencimiento."', fechaemision = '".$fechaemision."', 
					ciudad = '".$ciudad."', adecuacion = '".$adecuacion."', cantidadhabitaciones = '".$cantidadhabitaciones."', 
					porcentaje1 = '".$porcentaje1."', porcentaje2 = '".$porcentaje2."', criterio = '".$criterio."', regla = '".$regla."', 
					certifica = '".$certifica."', resultadoFormula = '".$resultadoFormula."', concurrircon = '".$concurrircon."', 
					estudioscomplementarios = '".$estudioscomplementarios."', modalidad = '".$modalidad."'
					WHERE id = '".$id."' ";
		//debug($query);
		$result = $mysqli->query($query);
		if($result = $mysqli->query($query)){			
			$valoresnew = array(
				'Tipo de discapacidad' 		=> $tipodiscapacidad,
				'Tipo de solicitud' 		=> $tiposolicitud,
				'Hora de inicio'	 		=> $horainicio,
				'Hora final' 				=> $horafinal,
				'Documentos' 				=> $documentos, 
				'Diagnósticos' 				=> $diagnosticos,
				'Código de junta'			=> $codigojunta,
				'Fecha de inicio de daño'	=> $fechainiciodano,
				'Ayuda técnica'				=> $ayudatecnica,
				'Ayuda técnica otro'		=> $ayudatecnicaotro,
				'Alfabetismo'				=> $alfabetismo,
				'Nivel educacional'			=> $niveleducacional,
				'Nivel educacional completado'			=> $niveleducacionalcompletado,
				'Nivel educacional incompleto'			=> $niveleducacionalincompleto,
				'Concurrencia educacional completada'	=> $concurrenciaeducacionalcompletado,
				'Tipo de educación'						=> $tipoeducacion,
				'Concurrencia del tipo de educación'	=> $concurrenciatipoeducacion,
				'Convivencia'				=> $convivencia,
				'Tipo de vivienda'			=> $tipovivienda,
				'Vivienda adaptada'			=> $viviendaadaptada,
				'Medio de transporte'		=> $mediotransporte,
				'Estado de las calles'		=> $estadocalles,
				'Vínculos'					=> $vinculos,
				'Etnia'						=> $etnia ,
				'Religión'					=> $religion,
				'Ingreso mensual'			=> $ingresomensual,
				'Otro ingreso mensual'		=> $ingresomensualotro,
				'Acompañante'				=> $acompanante,
				'Nombre del/la acompañante'	=> $nombreacompanante,
				'Observaciones'				=> $observaciones,
				'Duración'					=> $cantidadvencimiento,
				'Tipo de duración'			=> $tipovencimiento,
				'Fecha de vencimiento'		=> $fechavencimiento,
				'Fecha de emisión'			=> $fechaemision,
				'Ciudad'					=> $ciudad,
				'Adecuación'				=> $adecuacion,
				'Cantidad de habitaciones'	=> $cantidadhabitaciones,
				'Porcentaje 1'				=> $porcentaje1,
				'Porcentaje 2'				=> $porcentaje2,
				'Criterio'					=> $criterio,
				'Regla'						=> $regla,
				'Certifica'					=> $certifica,
				'Resultado de la formula'	=> $resultadoFormula,
				'Concurrir con'				=> $concurrircon,
				'Estudios complementarios'	=> $estudioscomplementarios,
				'modalidad'					=> $modalidad
			);
			actualizarRegistro('Evaluación','Evaluación',$id,$valoresold,$valoresnew,$query);
			
			if($idestados == '' || $idestados == '0'){
				if($certifica == 'SI'){
					$idestados = 3;
				}else{
					$idestados = 4;
				}
			}
			//ACTUALIZAR EL ESTADO DE LA SOLICITUD
			$idestadosOld = getValor('estatus','solicitudes',$idsolicitud,'');
			if($idestados != $idestadosOld && $idestados != 0){
				$queryS 	= " UPDATE solicitudes SET estatus = '".$idestados."', fechacambioestado = NOW() WHERE id = '".$idsolicitud."' ";
				$result = $mysqli->query($queryS);
				
				if($result == true){
					//Crear registro en solicitudes_estados
					$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
								VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), '".$idestadosOld."', '".$idestados."') ";
					$mysqli->query($queryE);
				}
			}
			
			$valoresoe = array('Estado' => getValor('descripcion','estados',$idestadosOld,'') );
			$valoresne = array('Estado' => getValor('descripcion','estados',$idestados,'') );
			actualizarRegistro('Evaluación','Solicitud',$idsolicitud,$valoresoe,$valoresne,$queryS);
			echo $id;
		} else {
			echo $query;
		}
	}

	function guardarCif(){
		global $mysqli;
		
		$id = $_REQUEST['id'];
		$cif = (!empty($_REQUEST['cif']) ? $_REQUEST['cif'] : '');		
		$query 	= "	UPDATE evaluacion SET cif = '$cif' WHERE id = '$id'";
		debugL($query,"DEBUGL-GUARDARCIF");
		guardarRegistroG('Evaluacion','guardarCif', $id, $query);
		if($result = $mysqli->query($query)){
			echo 1;
		} else {
			echo 0;
		}
	}
	
	function guardar(){
		global $mysqli;		
		$tipodiscapacidad 						= (!empty($_REQUEST['tipodiscapacidad']) ? $_REQUEST['tipodiscapacidad'] : '');
		$tiposolicitud 							= (!empty($_REQUEST['tiposolicitud']) ? $_REQUEST['tiposolicitud'] : 0);
		$horainicio 							= (!empty($_REQUEST['horainicio']) ? $_REQUEST['horainicio'] : '');
		$horafinal 								= (!empty($_REQUEST['horafinal']) ? $_REQUEST['horafinal'] : '');
		$documentos 							= (!empty($_REQUEST['documentos']) ? $_REQUEST['documentos'] : '');
		$diagnosticos 							= (!empty($_REQUEST['diagnosticos']) ? $_REQUEST['diagnosticos'] : '');
		$codigojunta 							= (!empty($_REQUEST['codigojunta']) ? $_REQUEST['codigojunta'] : '');
		$fechainiciodano 						= (!empty($_REQUEST['fechainiciodano']) ? $_REQUEST['fechainiciodano'] : '');
		$ayudatecnica 							= (!empty($_REQUEST['ayudatecnica']) ? $_REQUEST['ayudatecnica'] : '');
		$ayudatecnicaotro 						= (!empty($_REQUEST['ayudatecnicaotro']) ? $_REQUEST['ayudatecnicaotro'] : '');
		$alfabetismo 							= (!empty($_REQUEST['alfabetismo']) ? $_REQUEST['alfabetismo'] : '0');
		$niveleducacional 						= (!empty($_REQUEST['niveleducacional']) ? $_REQUEST['niveleducacional'] : '0');
		$niveleducacionalcompletado 			= (!empty($_REQUEST['niveleducacionalcompletado']) ? $_REQUEST['niveleducacionalcompletado'] : '0');
		$niveleducacionalincompleto 			= (!empty($_REQUEST['niveleducacionalincompleto']) ? $_REQUEST['niveleducacionalincompleto'] : '');
		$concurrenciaeducacionalcompletado 		= (!empty($_REQUEST['concurrenciaeducacionalcompletado']) ? $_REQUEST['concurrenciaeducacionalcompletado'] : '');
		$tipoeducacion 							= (!empty($_REQUEST['tipoeducacion']) ? $_REQUEST['tipoeducacion'] : '0');
		$concurrenciatipoeducacion 				= (!empty($_REQUEST['concurrenciatipoeducacion']) ? $_REQUEST['concurrenciatipoeducacion'] : '0');
		$convivencia 							= (!empty($_REQUEST['convivencia']) ? $_REQUEST['convivencia'] : '0');
		$tipovivienda 							= (!empty($_REQUEST['tipovivienda']) ? $_REQUEST['tipovivienda'] : '0');
		$viviendaadaptada 						= (!empty($_REQUEST['viviendaadaptada']) ? $_REQUEST['viviendaadaptada'] : '0');
		$mediotransporte 						= (!empty($_REQUEST['mediotransporte']) ? $_REQUEST['mediotransporte'] : '0');
		$estadocalles 							= (!empty($_REQUEST['estadocalles']) ? $_REQUEST['estadocalles'] : '0');
		$vinculos 								= (!empty($_REQUEST['vinculos']) ? $_REQUEST['vinculos'] : '');
		$etnia 									= (!empty($_REQUEST['etnia']) ? $_REQUEST['etnia'] : '');
		$religion 								= (!empty($_REQUEST['religion']) ? $_REQUEST['religion'] : '');
		$ingresomensual 						= (!empty($_REQUEST['ingresomensual']) ? $_REQUEST['ingresomensual'] : '');
		$ingresomensualotro 					= (!empty($_REQUEST['ingresomensualotro']) ? $_REQUEST['ingresomensualotro'] : '');
		$acompanante 							= (!empty($_REQUEST['acompanante']) ? $_REQUEST['acompanante'] : '');
		$nombreacompanante 						= (!empty($_REQUEST['nombreacompanante']) ? $_REQUEST['nombreacompanante'] : '0');
		$observaciones 							= (!empty($_REQUEST['observaciones']) ? $_REQUEST['observaciones'] : '');
		$fechavencimiento 						= (!empty($_REQUEST['fechavencimiento']) ? $_REQUEST['fechavencimiento'] : '');
		$cantidadvencimiento 					= (!empty($_REQUEST['cantidadvencimiento']) ? $_REQUEST['cantidadvencimiento'] : 0);
		$tipovencimiento 						= (!empty($_REQUEST['tipovencimiento']) ? $_REQUEST['tipovencimiento'] : '');
		$fechaemision 							= (!empty($_REQUEST['fechaemision']) ? $_REQUEST['fechaemision'] : '');
		$ciudad 								= (!empty($_REQUEST['ciudad']) ? $_REQUEST['ciudad'] : '');
		$idestados 								= (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : '');
		$adecuacion								= (!empty($_REQUEST['adecuacion']) ? $_REQUEST['adecuacion'] : '');
		$idpaciente								= (!empty($_REQUEST['idpaciente']) ? $_REQUEST['idpaciente'] : '');
		$estatus								= (!empty($_REQUEST['estatus']) ? $_REQUEST['estatus'] : '13');
		$idsolicitud							= (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$cantidadhabitaciones					= (!empty($_REQUEST['cantidadhabitaciones']) ? $_REQUEST['cantidadhabitaciones'] : '0');
		$porcentaje1							= (!empty($_REQUEST['porcentaje1']) ? $_REQUEST['porcentaje1'] : '');
		$porcentaje2							= (!empty($_REQUEST['porcentaje2']) ? $_REQUEST['porcentaje2'] : '');
		$criterio								= (!empty($_REQUEST['criterio']) ? $_REQUEST['criterio'] : '');
		$regla									= (!empty($_REQUEST['regla']) ? $_REQUEST['regla'] : '');
		$certifica								= (!empty($_REQUEST['certifica']) ? $_REQUEST['certifica'] : '');
		$resultadoFormula						= (!empty($_REQUEST['resultadoFormula']) ? $_REQUEST['resultadoFormula'] : '');
		$concurrircon							= (!empty($_REQUEST['concurrircon']) ? $_REQUEST['concurrircon'] : '');
		$estudioscomplementarios				= (!empty($_REQUEST['estudioscomplementarios']) ? $_REQUEST['estudioscomplementarios'] : '');
		$modalidad								= (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');

		$query 	= "	INSERT INTO	evaluacion (tipodiscapacidad, tiposolicitud, horainicio, horafinal, documentos, diagnostico, codigojunta, 
					fechainiciodano, ayudatecnica, ayudatecnicaotro, alfabetismo, niveleducacional, niveleducacionalcompletado, 
					niveleducacionalincompleto, concurrenciaeducacionalcompletado, tipoeducacion, concurrenciatipoeducacion, convivencia, 
					tipovivienda, viviendaadaptada, mediotransporte, estadocalles, vinculos, etnia, religion, ingresomensual, 
					ingresomensualotro, acompanante, nombreacompanante, observaciones, duracion, tipoduracion, fechavencimiento, 
					fechaemision, ciudad, idpaciente, cif, estatus, idsolicitud, adecuacion,cantidadhabitaciones, porcentaje1, 
					porcentaje2, criterio, regla, certifica, resultadoFormula,concurrircon,estudioscomplementarios,modalidad)
					VALUES ('$tipodiscapacidad', '$tiposolicitud', '$horainicio', '$horafinal', '$documentos', '$diagnosticos', 
					'$codigojunta', '$fechainiciodano', '$ayudatecnica', '$ayudatecnicaotro', '$alfabetismo', '$niveleducacional', 
					'$niveleducacionalcompletado', '$niveleducacionalincompleto', '$concurrenciaeducacionalcompletado', '$tipoeducacion', 
					'$concurrenciatipoeducacion', '$convivencia', '$tipovivienda', '$viviendaadaptada', '$mediotransporte', '$estadocalles', 
					'$vinculos', '$etnia', '$religion', '$ingresomensual', '$ingresomensualotro', '$acompanante', '$nombreacompanante', 
					'$observaciones', '$cantidadvencimiento', '$tipovencimiento', '$fechavencimiento', '$fechaemision', '$ciudad', 
					'$idpaciente','',$estatus,'$idsolicitud','$adecuacion', '$cantidadhabitaciones','$porcentaje1','$porcentaje2',
					'$criterio','$regla','$certifica', '$resultadoFormula', '$concurrircon', '$estudioscomplementarios', '$modalidad')";
		//debugL($query);
		if($result = $mysqli->query($query)){
			$idevaluacion = $mysqli->insert_id;			
			$valoresnew = array(
				'Tipo de discapacidad' 		=> $tipodiscapacidad,
				'Tipo de solicitud' 		=> $tiposolicitud,
				'Hora de inicio'	 		=> $horainicio,
				'Hora final' 				=> $horafinal,
				'Documentos' 				=> $documentos, 
				'Diagnósticos' 				=> $diagnosticos,
				'Código de junta'			=> $codigojunta,
				'Fecha de inicio de daño'	=> $fechainiciodano,
				'Ayuda técnica'				=> $ayudatecnica,
				'Ayuda técnica otro'		=> $ayudatecnicaotro,
				'Alfabetismo'				=> $alfabetismo,
				'Nivel educacional'			=> $niveleducacional,
				'Nivel educacional completado'			=> $niveleducacionalcompletado,
				'Nivel educacional incompleto'			=> $niveleducacionalincompleto,
				'Concurrencia educacional completada'	=> $concurrenciaeducacionalcompletado,
				'Tipo de educación'						=> $tipoeducacion,
				'Concurrencia del tipo de educación'	=> $concurrenciatipoeducacion,
				'Convivencia'				=> $convivencia,
				'Tipo de vivienda'			=> $tipovivienda,
				'Vivienda adaptada'			=> $viviendaadaptada,
				'Medio de transporte'		=> $mediotransporte,
				'Estado de las calles'		=> $estadocalles,
				'Vínculos	'				=> $vinculos,
				'Etnia'						=> $etnia ,
				'Religión'					=> $religion,
				'Ingreso mensual'			=> $ingresomensual,
				'Otro ingreso mensual'		=> $ingresomensualotro,
				'Acompañante'				=> $acompanante,
				'Nombre del/la acompañante'	=> $nombreacompanante,
				'Observaciones'				=> $observaciones,
				'Duración'					=> $duracion,
				'Tipo de duración'			=> $tipoduracion,
				'Fecha de vencimiento'		=> $fechavencimiento,
				'Fecha de emisión'			=> $fechaemision,
				'Ciudad'					=> $ciudad,
				'Adecuación'				=> $adecuacion,
				'Cantidad de habitaciones'	=> $cantidadhabitaciones,
				'Porcentaje 1'				=> $porcentaje1,
				'Porcentaje 2'				=> $porcentaje2,
				'Criterio'					=> $criterio,
				'Regla'						=> $regla,
				'Certifica'					=> $certifica,
				'Resultado de la formula'	=> $resultadoFormula,
				'Concurrir con'				=> $concurrircon,
				'Estudios complementarios'	=> $estudioscomplementarios,
				'modalidad'					=> $modalidad
			);
			nuevoRegistro('Evaluación','Evaluación',$idevaluacion,$valoresnew,$query);
			//ACTUALIZAR EL ESTADO DE LA SOLICITUD			
			//$idsolicitud = getValor('idsolicitud','evaluacion',$idevaluacion,'');
			$idestadosOld = getValor('estatus','solicitudes',$idsolicitud,'');
			if($idestados != $idestadosOld && $idestados != 0){
				$queryS 	= " UPDATE solicitudes SET estatus = '".$idestados."', fechacambioestado = NOW() WHERE id = '".$idsolicitud."' ";
				$result = $mysqli->query($queryS);
				if($result == true){
					//Crear registro en solicitudes_estados
					$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
								VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), '".$idestadosOld."', '".$idestados."') ";
					$mysqli->query($queryE);
				}
				
				$valoresoe = array('Estado' => getValor('descripcion','estados',$idestadosOld,'') );
				$valoresne = array('Estado' => getValor('descripcion','estados',$idestados,'') );
				actualizarRegistro('Evaluación','Solicitud',$idsolicitud,$valoresoe,$valoresne,$queryS);
			}
			echo $idevaluacion;
		} else {
			echo $query;
		}
	}
	
	function existe(){
		global $mysqli;
		$cedula = $_REQUEST['cedula'];
		$count = 0;
		$query = "SELECT cedula FROM medicos WHERE cedula = '$cedula'";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}

	function buscarobservacion(){
		global $mysqli;
		$id = $_REQUEST['id'];
		$total = (!empty($_REQUEST['total']) ? $_REQUEST['total'] : '0');
		$total1 = (!empty($_REQUEST['total1']) ? $_REQUEST['total1'] : '0');
		$criterio = (!empty($_REQUEST['criterio']) ? $_REQUEST['criterio'] : '0');
		$certificado = (!empty($_REQUEST['certificado']) ? $_REQUEST['certificado'] : '0');
		$discapacidad = (!empty($_REQUEST['discapacidad']) ? $_REQUEST['discapacidad'] : '0');
		
		$query = "SELECT observacion FROM observacionesdiscapacidad WHERE id = '$id' ";
		$result = $mysqli->query($query);
	    while($row = $result->fetch_assoc()){
	        $observacion = $row['observacion'];
	        if ($id != '3') {
	        	$observacion = str_replace('%porcentaje1%', $total, $observacion);
	        }
	        if ($id != '3' && $id != '4') {
	        	$observacion = str_replace('%criterio%', $criterio, $observacion);	
	        }
	        if ($id == '2') {
	        	$observacion = str_replace('%porcentaje2%', $total1, $observacion);	
	        }
			if ($id == '1') {
				if($certificado == 'NO'){
					//$observacion = str_replace('criterio #', 'criterio #'.$criterio, $observacion);
					$observacion = str_replace('discapacidad ______________', 'discapacidad '.$discapacidad, $observacion);
					$observacion = str_replace('por lo que certifica por un periodo de ____  años.', 'por lo que NO certifica', $observacion);					
				}
	        }
			echo $observacion;
		}
	}
	
	function checkpacienteseguro(){
		global $mysqli;
		$idseguro = $_REQUEST['id'];
		$count = 0;
		$query = "SELECT id FROM pacienteseguros WHERE idmedicos = '$idseguro'";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}
	
	function getEstadoSolicitud(){
		global $mysqli;
		
		$idsolicitud = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		
		$query 	= "	SELECT estatus
					FROM solicitudes  
					WHERE id = ".$idsolicitud." ";
					
		$result = $mysqli->query($query);
		
		if($row = $result->fetch_assoc()){
			
			$resultado = array(
				'idestados'	=> 	$row['estatus']
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
	
/* 	function guardarEstudiosComplementarios(){
		global $mysqli;
		
		$idevaluacion = (!empty($_REQUEST['idevaluacion']) ? $_REQUEST['idevaluacion'] : '');
		$estudioscomplementarios = (!empty($_REQUEST['estudioscomplementarios']) ? $_REQUEST['estudioscomplementarios'] : '');
		
		$query 	= " UPDATE 
						evaluacion
					SET 
						estudioscomplementarios = '".$estudioscomplementarios."'
					WHERE 
						id = ".$idevaluacion."
					";
					
		$result = $mysqli->query($query);
		if($result == true){
			echo 1;
		}else{
			echo 0;
		}
	} */	


?>