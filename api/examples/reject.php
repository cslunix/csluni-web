<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

session_start();
	if( isset($_GET) ){
		include('../../../config/EissonConnect.php');
		include('../../../vendor/PHPMailer/PHPMailerAutoload.php');
		# $session = json_decode($_GET['session']);
		$data      = json_decode($_GET['data']); //{id: 1, nombre: "LN432_HusaresJunin", tipo: 0, validacion: 0}
		$user      = json_decode($_GET['user']); //{"nombres":"Eisson Alipio Rodriguez","id":16,"correo":"eisson.alipio@entel.pe","foto":"ealipior","gerencia":1,"actividades":1}
		$actividad = json_decode($_GET['actividad']);
		$userID    = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
		if( $userID == $user->id){
			$db  = new EissonConnect();
			$dbh = $db->enchufalo();
			$result = reject($dbh, $data->id, $_SESSION['username']);
		} else{
			//intento de hackeo por session, se acabo la sesion o esta intentando hack the system
			$result  = 'error';
		}
		$result = array('resultado' => $result, 'username' => $_SESSION['username'] );
		//s.solicitante s.solicitanteID s.solicitanteMail
		//echo $actividad->solicitanteMail;
		$asunto = 'Rechazada ' . $actividad->id . ' - ' . html_entity_decode(htmlentities( $actividad->nombre , ENT_QUOTES | ENT_IGNORE, "UTF-8"));
		$body = 'Estimado <b>' . $actividad->solicitante. ',</b><br><br>';
		$body .= 'Los trabajos en <b>'. $data->nombre.'</b> fueron rechazados por ' . $user->nombres .' <br>';
		$body .= 'Detalles:<br><br> http://aseguramientodelacalidad/GO/modules/validacion/#/busqueda/solicitante/actividad/' . $actividad->id;
		$body .= '<br>Por favor use chrome o Firefox';
		notificaSolicitante($actividad->solicitanteMail, $body, $asunto);
		echo json_encode($result);
	}

function reject($dbh, $idNE, $user) {
	$resultado = false;
	// validacion = 2 : trabajo rechazado
	$query = 'UPDATE `nes` SET `validacion`=2,`validador`=:user WHERE `id`=:idNE';
	$stmt  = $dbh->prepare($query);
	$stmt->bindParam(':user', $user, PDO::PARAM_INT);
	$stmt->bindParam(':idNE', $idNE, PDO::PARAM_STR);
	$resultado = $stmt->execute();
    return $resultado;
}

function notificaSolicitante($to, $cuerpo, $asunto){
	$firma  = '<br><br>';
	$firma .= '<div style="color: #4183c4;"><b>Gesti&oacute;n Operativa';
		$firma .= '<br><span style="color: #797979;">Gerencia de Aseguramiento de Calidad &amp; NOC<span>';
		$firma .= '<br><span style="color: #AAAAAA;">M&oacute;vil: 94713 4946<br>Anexo: 2344</span>';
		$firma .= '<br><span style="color: #AAAAAA;">Av. Del Ejercito 291 - Miraflores</span></b>';
	$firma .= '</div>';
  	$mail = new PHPMailer;
  	$mail->isSMTP();
  	// Specify main and backup SMTP servers
  	$mail->Host = '200.110.2.52;172.20.1.104;172.20.1.252';
  	$mail->From = 'gestion.operativa@nextel.net.pe';
  	$mail->FromName = 'Gestion Operativa';
  	// foreach($destinatarios as $destino) $mail->AddAddress($destino);
  	$mail->AddAddress($to);
  	//$mail->addCC('gestion.operativa@entel.pe');
  	// a futuro todo sera con copia a gestion operativa
  	$mail->addReplyTo('gestion.operativa@entel.pe', 'GESTION OPERATIVA');
  	$mail->isHTML(true);
  	$mail->Subject = $asunto;
  	$mail->Body    = $cuerpo.$firma;
  	if( !$mail->send() ) $respuesta = TRUE;
  	else                 $respuesta = FALSE;
  	return $respuesta;
};


?>