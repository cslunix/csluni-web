<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

session_start();
	if( isset($_GET) ){
		include('../../../config/EissonConnect.php');
		//include('/../../../vendor/PHPMailer/PHPMailerAutoload.php');
		$data      = json_decode($_GET['data']);
		$tipo      = $_GET['tipo'];

		$gerenciaID = isset( $_SESSION['gerencia'] ) ? $_SESSION['gerencia'] : 0;
		if( $gerenciaID == 1 ){
			if( $tipo == 'rechazar'){
				//gerencia
				$db  = new EissonConnect();
				$dbh = $db->enchufalo();
				$result['status'] = rechazar($dbh, $data, $_SESSION['username']);
				$result['username'] = $_SESSION['username'];
			};
		}
		/***
		$user      = json_decode($_GET['tipo']);
		$actividad = json_decode($_GET['actividad']);
		$userID    = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
		if( $userID == $user->id){
			$result = rechazar($dbh, $data->id, $user->id);
		}
		$result = array('resultado' => $result );
		*/
		//s.solicitante s.solicitanteID s.solicitanteMail
		//echo $actividad->solicitanteMail;
		//$asunto = 'Notificacion de validacion - Reject';
		//$body = 'Estimado ' . $actividad->solicitante. '<br><br>';
		//$body .= 'Se te informa que '.$user->nombres.'  rechazo los trabajos en el elemento '. $data->nombre.'<br>';
		//$body .= ' Los detalles se pueden revisar en tus actividades o en la siguiente direccion:<br> </br>';
		//notificaSolicitante($actividad->solicitanteMail, $body, $asunto);
		echo json_encode($result);
		//array(2) { ["data"]=> string(2) "62" ["tipo"]=> string(8) "rechazar"}
	}

function rechazar($dbh, $data, $user) {
	$resultado = false;
	$query = 'UPDATE fechas SET validacion=2,validador=:user WHERE id=:id';
	$stmt  = $dbh->prepare($query);
	$stmt->bindParam(':user', $user, PDO::PARAM_INT);
	$stmt->bindParam(':id', $data->id, PDO::PARAM_STR);
	$resultado = $stmt->execute();
    return $resultado;
};

function notificaSolicitante($to, $cuerpo, $asunto){
//function notificaSolicitante($to, $cc, $cuerpo, $asunto){
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
  	//$mail->addCC($cc);
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