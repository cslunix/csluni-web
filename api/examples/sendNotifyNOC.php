<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);
	session_start();

	$postdata = file_get_contents("php://input");

if( isset($_SESSION['username']) ){
	$valu     = json_encode($postdata);
	$info     = json_decode($valu);
	$data     = json_decode($info);
	include('../../../config/EissonConnect.php');
	include('../../../vendor/PHPMailer/PHPMailerAutoload.php');
	$db      = new EissonConnect();
	$dbh     = $db->enchufalo();
	$correos = isset($data->notify) ?  explode(",", $data->notify->destinatarios) : array() ;
	principal( $dbh, $data->mifecha, $data->data, $correos);
	$r = array('success' => 'Se actualizaron los registros y enviaron las notificaciones  correctamente.' );
} else{
	$r = array('error' => 'La session de Usuario ha culminado, por favor vuelva a ingresar al sistema.' );
}
 echo json_encode($r);
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//  Util Functions by Eisson Alipio
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function principal( $dbh, $mifecha, $work, $correos) {
	switch ( $mifecha->statusnoc ) {
		case '1': $asunto = 'INICIO ACTIVIDAD: '         . $work->id . ' - ' . $work->nombre; break;
		case '2': $asunto = 'FIN ACTIVIDAD: '            . $work->id . ' - ' . $work->nombre; break;
		case '3': $asunto = 'CANCELACION DE ACTIVIDAD: ' . $work->id . ' - ' . $work->nombre; break;
		case '4': $asunto = 'UPDATE DE ACTIVIDAD: '      . $work->id . ' - ' . $work->nombre; break;
	};
	changeStatus($dbh, $mifecha->statusnoc, $mifecha, $correos, $asunto);
	armaCorreo($dbh, $work, $mifecha, $correos, $asunto);
};

function enviarcorreo($correos, $content, $asunto, $islas, $nocID, $data){
	$userName = $_SESSION['nombres'];
	$islas    = array(array(),array('nombre' => 'NOC Datacomm','anexo' => '5055','celular' => '994014082','correo' => 'noc.datacomm@entel.pe' ),array(),array('nombre' => 'NOC Nodos y TX','anexo' => '5045','celular' => '994177220', 'correo' => 'noc.nodos-tx@entel.pe' ),array('nombre' => 'NOC Telefonia/Datos/RAN','anexo' => '5044','celular' => '947185780', 'correo' => 'noc.telefonia@entel.pe' ),array('nombre' => 'NOC VAS','anexo' => '5054','celular' => '994013900', 'correo' => 'noc.vas@entel.pe' ));
	$body     = '<html><head><style>@font-face{font-family:Calibri;panose-1:2 15 5 2 2 2 4 3 2 4;}body{font-family:"Calibri","sans-serif";color:#0154A0}table td{border:1px solid #ddd;} table th{background-color:#1884C0;color:#fff;} span.m-1{color:#15A508} span.m-0{color:#B94444}p.MsoNormal, div.MsoNormal{margin:0cm;margin-bottom:.0001pt;font-size:11.0pt;font-family:"Calibri","sans-serif";} @page WordSection1{size:612.0pt 792.0pt;margin:70.85pt 3.0cm 70.85pt 3.0cm;}div.WordSection1{page:WordSection1;}</style></head><body><div class=WordSection1>'. $content .'<br><br><p class=MsoNormal><span style="font-size:12.0pt;color:#0154A0">'. $userName.'&nbsp;</span><b><span style="font-size:12.0pt;color:#FF6702">_</span></b><span style="font-size:12.0pt;color:#0154A0">&nbsp;'. $islas[$nocID]['nombre'] .'</span></p><p class=MsoNormal><b><span style="color:#B4B3B2">Gerencia de Aseguramiento de Calidad y NOC &#8211; VP Redes Entel</span></b></p><p class=MsoNormal><span style="color:#B4B3B2">Avenida Circunvalación 2886, piso 1,&nbsp; San Borja</span></p><p class=MsoNormal><span style="color:#B4B3B2">Tel. 611 1111 anexo '.$islas[$nocID]['anexo'].'</span></p><p class=MsoNormal><span style="color:#B4B3B2">Cel. 51 '.$islas[$nocID]['celular'].'</span></p><p class=MsoNormal><span style="color:#1F497D"><a href="mailto:'.$islas[$nocID]['correo'].'">'.$islas[$nocID]['correo'].'</a></span></p> <br> <p class=MsoNormal><span style="color:#1F497D"><img border=0 src="cid:entel" alt="Description: entel"></span></p></div></body></html>';
	try {

  		list($name, $domain) = explode('@', $islas[$nocID]['correo']);
  		$domainNet = '@entel.net.pe';
  		$newMail = $name . $domainNet;

		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->Host = '200.110.2.52;172.20.1.104;172.20.1.252';
		$mail->setFrom( $newMail, $islas[$nocID]['nombre'] );
		$mail->addReplyTo($islas[$nocID]['correo'], $islas[$nocID]['nombre']);
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		//+++++++++++++++++++++++++++++++++++++++++++++++
		switch ($nocID) {
			case '1':
				$to_noc = array('ingenieria.o&mcoreyagregador@entel.pe');
				$cc_noc = array('noc@entel.pe','noc.datacomm@entel.pe','gestion.dettsyservicios@entel.pe','ingenieria.aseguramientodecalidad@entel.pe','gestion.operativa@entel.pe','javier.ravello@entel.pe','fernando.garcia@entel.pe','jorge.herrera@entel.pe','renan.ruiz@entel.pe','igor.aliaga@entel.pe','jose.reyes@entel.pe','jfrancisco.barrenechea@entel.pe','Redes.Ingenieria.Datos@entel.pe','operaciones.RAN@entel.pe');
				break;
			case '3':
				$to_noc = array("operaciones.RAN@entel.pe","Tier2.Agregacion@entel.pe","ingenieria.o&maccesoderedytransporte@entel.pe","o&M.sitios@entel.pe","O&M.Infraestructura@entel.pe");
				$cc_noc = array('fernando.saavedra@entel.pe','noc.nodos-tx@entel.pe','victor.rodriguez@entel.pe','martin.rivera@entel.pe','rodolfo.vilchez@entel.pe','luis.latorre@entel.pe','fernando.angulo@entel.pe','noc@entel.pe','gestion.dettsyservicios@entel.pe','ingenieria.aseguramientodecalidad@entel.pe','gestion.operativa@entel.pe','javier.ravello@entel.pe','fernando.garcia@entel.pe','jorge.herrera@entel.pe','renan.ruiz@entel.pe','igor.aliaga@entel.pe','jose.reyes@entel.pe','jfrancisco.barrenechea@entel.pe');
				break;
			case '4':
				$to_noc = array('ingenieria.o&mcoreyagregador@entel.pe');
				$cc_noc = array('noc@entel.pe', 'noc.telefonia@entel.pe','gestion.dettsyservicios@entel.pe','ingenieria.aseguramientodecalidad@entel.pe','gestion.operativa@entel.pe','javier.ravello@entel.pe','fernando.garcia@entel.pe','jorge.herrera@entel.pe','renan.ruiz@entel.pe','igor.aliaga@entel.pe','jose.reyes@entel.pe','jfrancisco.barrenechea@entel.pe','Redes.Ingenieria.Datos@entel.pe','operaciones.RAN@entel.pe');
				break;
			case '5':
				$to_noc = array('ingenieria.o&mcoreyagregador@entel.pe');
				$cc_noc = array('noc@entel.pe','noc.vas@entel.pe','gestion.dettsyservicios@entel.pe','ingenieria.aseguramientodecalidad@entel.pe','gestion.operativa@entel.pe','javier.ravello@entel.pe','fernando.garcia@entel.pe','jorge.herrera@entel.pe','renan.ruiz@entel.pe','igor.aliaga@entel.pe','jose.reyes@entel.pe','jfrancisco.barrenechea@entel.pe','Redes.Ingenieria.Datos@entel.pe','operaciones.RAN@entel.pe');
				break;
		}
		foreach($correos as $correo) $mail->AddAddress($correo);
		foreach($to_noc as $t) $mail->AddAddress($t);
		foreach($cc_noc as $c) $mail->addCC($c);
		$mail->addCC($data->solicitanteMail);
		$mail->addCC('gestion.operativa@entel.pe');
		$mail->Body    = $body;
		$mail->AddEmbeddedImage('entel.jpg', 'entel');
		$mail->CharSet = 'utf-8';
		$r = $mail->send();
	} catch (phpmailerException $e) {  $r = $e->errorMessage();	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  	return $r;
};

function armaCorreo($dbh, $data, $mifecha, $correos, $asunto){
	$status = array('','Inicio', 'fin', 'cancelacion', 'Update');
		$body = 'Estimados,<br><br> Para informarles el <b>'.$status[$mifecha->statusnoc]. '</b> de la siguiente actividad:<br><br>';
		$body .= '<table>';
		$body .= '<tr> <th> MOP</th>'. '<th> ' . htmlentities($data->nombre, ENT_QUOTES | ENT_IGNORE, "UTF-8") . '</th>  </tr>';
		$body .= '<tr> <td> ID</td>'. '<td> ' . $data->id . '</td>  </tr>';
		$body .= '<tr> <td> Tarea:</td>'. '<td> ' . htmlentities($data->proposito, ENT_QUOTES | ENT_IGNORE, "UTF-8") . '</td>  </tr>';

	$body .= '<tr> <td> NEs:</td>'. '<td> ';
		foreach ($data->nes as $v) {
			$body .= $v->nombre.' ';
		}
	$body .=  '</td>  </tr>';


	$body .= '<tr> <td> Area:</td>'. '<td> ';
	$teams = array("", "REGULATORIO", "GESTION OPERATIVA", "O&M CORE", "VOZ", "CORE VOZ", "CORE RAN", "DATOS", "CORE DATOS", "DATACOMM", "CORE TRANSPORTE", "ISP", "TRANSPORTE Y AGREGACION", "VAS Y PLATAFORMAS", "VAS", "OSS", "O&M SITES", "ADMINISTRACION DE INFRAESTRUCTURA", "OPERACIONES RAN", "SITES CRM", "ACCESO TRANSPORTE");
		foreach ($data->status as $v) {
			$body .= $teams[$v->team].' ';
		}
	$body .=  '</td>  </tr>';

	$body .= '<tr> <td> Gerencia Solicitante:</td>'. '<td> ';
	$gerencias = array("","ASEGURAMIENTO DE LA CALIDAD Y NOC","CONSTRUCCION E INFRAESTRUCTURA DE RED","INGENIERIA DE REDES CORE Y SERVICIOS","INGENIERIA DE RED DE ACCESOS","O&M SITIOS Y ACCESO RED","PLANIFICACION Y CONTROL DE GESTION","O&M REDES CORE TRANSPORTE Y PLATAFORMAS","PMO DE RED","VP", "CONTABILIDAD");
	$body .= $gerencias[$data->gerencia];
	$body .=  '</td>  </tr>';

	$body .= '<tr> <td> Solicitante</td>'. '<td> ' .  htmlentities( $data->solicitante, ENT_QUOTES | ENT_IGNORE, "UTF-8") . '</td>  </tr>';

	$body .= '<tr> <td> Personal:</td>'. '<td> ';
	foreach ($data->personal as $v) {	$body .= $v->nombre.'( '.$v->empresa. ') ';		}
	$body .=  '</td>  </tr>';

	$islas = array("","Datacomm", "Dispatch", "Nodos-TX", "Telefonia", "VAS");
	$body .= '<tr> <td> NOC:</td>'. '<td> ';
	$body .= $islas[$data->noc];
	$body .=  '</td>  </tr>';

	if( !is_null($mifecha->inicio) ){
		$body .= '<tr> <td> Fecha y Hora de Inicio de la Actividad:</td>'. '<td> ' . $mifecha->fecha . '  ' . $mifecha->inicio . '</td>  </tr>';
		updateValue($dbh, $mifecha, 'inicio', $mifecha->inicio);
	}

	if( !is_null($mifecha->fin) ){
		$body .= '<tr> <td> Fecha y Hora de Fin de la Actividad:</td>'. '<td> ' . $mifecha->fecha . '  ' . $mifecha->fin . '</td>  </tr>';
		updateValue($dbh, $mifecha, 'fin', $mifecha->fin);
	}

	$afectacion = array('No', 'Si');
	$body .= '<tr><td>Afectacion:</td>'. '<td> ' . $afectacion[$mifecha->afectacion] . '</td>  </tr>';
	if( !is_null($mifecha->inicio_af) ){
		$body .= '<tr> <td> Fecha y Hora de Inicio de Afectacion:</td>'. '<td> ' . $mifecha->fecha . '  ' . $mifecha->inicio_af . '</td>  </tr>';
		updateValue($dbh, $mifecha, 'inicio_af', $mifecha->inicio_af);
	}
	if( !is_null($mifecha->fin_af) ){
		$body .= '<tr> <td> Fecha y Hora de Fin de Afectacion:</td>'. '<td> ' . $mifecha->fecha . '  ' . $mifecha->fin_af . '</td>  </tr>';
		updateValue($dbh, $mifecha, 'fin_af', $mifecha->fin_af);
	}

	$resultados = array('Cancelado','Exitoso','Rollback','Parcialmente Exitoso','En Progreso');
	$body .= '<tr> <td> Resultado:</td>'. '<td><b>' . $resultados[$mifecha->resultado] .  '</b></td></tr>';
	if( isset($data->noc_coments[0]) ) {
		$body .= '<tr> <td> comentarios:</td>'. '<td> ' . trim( htmlentities( $data->noc_coments[0]->comentario, ENT_QUOTES | ENT_IGNORE, "UTF-8") ) . '</td>  </tr>';
		saveComent($dbh, $data->id, $_SESSION['username'],  $data->noc_coments[0]->comentario, $mifecha->id);
	}
	$body .= '</table>';
	$body .= '<br>Saludos Cordiales';
	//var_dump($mifecha);
	enviarcorreo($correos, $body, $asunto, $islas[$data->noc], $data->noc, $data);
};

function updateValue($dbh, $mifecha, $nameValue, $value){
	$query = 'UPDATE fechas SET '. $nameValue .'=:value WHERE id=:id';
	$stmt  = $dbh->prepare($query);
	$stmt->bindParam(':value', $value, PDO::PARAM_STR);
	$stmt->bindParam(':id', $mifecha->id, PDO::PARAM_STR);
	$resultado = $stmt->execute();
};

function saveComent($dbh, $actividad, $author, $comentario, $fechaid){
	$query = 'SELECT actividad from comentarios_noc WHERE  mifechaid = :mifechaid';
	$stmt  = $dbh->prepare($query);
	$stmt->bindParam(':mifechaid', $fechaid, PDO::PARAM_INT);
	$resultado = $stmt->execute();
	if( $stmt->rowCount() > 0 ){
	    $query = 'UPDATE comentarios_noc SET author=:author, comentario=:comentario WHERE mifechaid=:mifechaid';
	    $stmt  = $dbh->prepare($query);
	    $stmt->bindParam(':author', $author, PDO::PARAM_STR);
	    $stmt->bindParam(':mifechaid', $fechaid, PDO::PARAM_INT);
	    $stmt->bindParam(':comentario',$comentario, PDO::PARAM_STR);
	    $resultado = $stmt->execute();
	} else{
	    $query = 'INSERT INTO comentarios_noc (`actividad`,`mifechaid`,`author`,`comentario`,`created_at`) VALUES (:actividad, :fechaid,:author,:comentario,CURRENT_TIMESTAMP)';
	    $stmt  = $dbh->prepare($query);
	    $stmt->bindParam(':actividad', $actividad, PDO::PARAM_INT);
	    $stmt->bindParam(':fechaid', $fechaid, PDO::PARAM_INT);
	    $stmt->bindParam(':author', $author, PDO::PARAM_STR);
	    $stmt->bindParam(':comentario',$comentario, PDO::PARAM_STR);
	    $resultado = $stmt->execute();
	}
}

function changeStatus($dbh, $newStatus, $mifecha, $asunto){
	//  $mifecha->afectacion : 0 : sin afectacion
	//  $mifecha->afectacion : 1 : con afectacion
	$falla = (($mifecha->statusnoc == 2 || $mifecha->statusnoc == 4) && $mifecha->falla) ? 1 : NULL;
	$query = 'UPDATE fechas SET statusnoc=:newStatus, resultado=:resultado, falla = :falla WHERE id=:id';
	$stmt  = $dbh->prepare($query);
	$stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_INT);
	$stmt->bindParam(':resultado', $mifecha->resultado, PDO::PARAM_STR); // 4 es en progreso $mifecha->resultado
	$stmt->bindParam(':falla', $falla, PDO::PARAM_INT);
	$stmt->bindParam(':id', $mifecha->id, PDO::PARAM_STR);
	$resultado = $stmt->execute();
};

?>