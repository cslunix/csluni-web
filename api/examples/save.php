<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);
session_start();
	if( isset($_POST) ){
		include('../../../config/EissonConnect.php');
		include('../../../vendor/PHPMailer/PHPMailerAutoload.php');
		$db         = new EissonConnect();
		$dbh          = $db->enchufalo();
		$actividad    = filter_var($_POST['actividad'],  FILTER_VALIDATE_INT);
		$validacionID = filter_var($_POST['idValid'], FILTER_VALIDATE_INT);
		$comentario   = trim( filter_var($_POST['comentario'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) );
		$author       = filter_var($_POST['author'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		// value of select validacion
		$validacion = filter_var($_POST['validacion'], FILTER_VALIDATE_INT);

		switch ($validacion) {
			case 0:
				# pendiente
				$resultado = saveComment($comentario, $validacionID, $actividad, $author, $dbh);
				if($resultado){
					$asunto = 'GO-Bot: Se rechazo el TP';
					// el correo de rechazo va dirigido al solicitante con copia a GO
					sendNotificaciones('eisson.alipio@entel.pe', '',$asunto);
				}
				break;
			case 1:
				# se valida
				$resultado = saveValidado($comentario, $validacionID, $actividad, $author, $dbh);
				$personal     = $_POST['person']; //array
				savePersonal($personal, $actividad, $dbh);
				if($resultado){
					$asunto = 'GO-Bot: Validacion de MOP';
					// el correo de validacion va dirigido al solicitante con copia a GO
					sendNotificaciones('eisson.alipio@entel.pe', '',$asunto);
				}
				break;
		}
	}

function sendNotificaciones($destino, $cuerpo, $asunto){
  // $destinatarios: array de strings
  // $cuerpo: string
  // $asunto: string
  $firma = '<br><br><div style="color: #F06000;"><b>Gesti&oacute;n Operativa<br>Gerencia de Aseguramiento de Calidad &amp; NOC<br><br>M&oacute;vil: 94713 4946<br>Anexo: 2344<br>Av. Del Ejercito 291 - Miraflores<br></b></div>';
  $mail = new PHPMailer;
  $mail->isSMTP();
  $mail->Host = '200.110.2.52;172.20.1.104;172.20.1.252';  // Specify main and backup SMTP servers
  $mail->From = 'gestion.operativa@nextel.net.pe';
  $mail->FromName = 'GO-Bot';
  //foreach($destinatarios as $destino) $mail->AddAddress($destino);
  $mail->AddAddress($destino);
  $mail->addCC('eisson.alipio@nextel.com.pe'); // a futuro todo sera con copia a gestion operativa
  $mail->addReplyTo('gestion.operativa@nextel.com.pe', 'GESTION OPERATIVA');
  $mail->isHTML(true);
  $mail->Subject = $asunto;
  $mail->Body    = $cuerpo.$firma;

  if(!$mail->send()) $respuesta = TRUE;
  else $respuesta = FALSE;
  return $respuesta;
}

function savePersonal($personal, $actividad, $dbh){
	$query = 'INSERT INTO personal (`actividad`,`nombre`, `celular`, `empresa`, `tipo`, `created_at`) VALUES (:actividad, :nombre, :celular, :empresa, :tipo, CURRENT_TIMESTAMP)';
	$empresa = 'Entel Peru';
	$stmt  = $dbh->prepare($query);
	foreach ($personal['name'] as $key => $nombre) {
		$stmt->bindParam(':actividad',  $actividad, PDO::PARAM_STR);
		$stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
		$stmt->bindParam(':celular', $personal['cel'][$key], PDO::PARAM_STR);
		$stmt->bindParam(':empresa', $empresa, PDO::PARAM_STR);
		$stmt->bindParam(':tipo', $personal['type'][$key], PDO::PARAM_STR);
		$stmt->execute();
	}
}

function saveValidado($comentario, $validacionID, $actividad, $author, $dbh) {
	$resultado = false;
    if( strlen($comentario) > 0){
    	// update status
	    $query = 'UPDATE validaciones SET estado=:estado WHERE id=:validacionID';
	    $estado = 1;
		$stmt  = $dbh->prepare($query);
		$stmt->bindParam(':validacionID', $validacionID, PDO::PARAM_STR);
		$stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
	    $resultado = $stmt->execute();
	    // save POCs
    	// save comment
    	saveComment($comentario, $validacionID, $actividad, $author, $dbh);
    }
    return $resultado;
}

function saveComment($comentario, $validacion, $actividad, $author, $dbh) {
	//empty comment?
	$resultado = false;
    if( strlen($comentario) > 0){
	    $query = 'INSERT INTO comentarios (`validacion`,`actividad`, `author`,`comentario`, `created_at`) VALUES (:validacion, :actividad, :author, :comentario, CURRENT_TIMESTAMP)';
		$stmt  = $dbh->prepare($query);
		$stmt->bindParam(':validacion', $validacion, PDO::PARAM_STR);
		$stmt->bindParam(':actividad',  $actividad, PDO::PARAM_STR);
		$stmt->bindParam(':author', $author, PDO::PARAM_STR);
		$stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);
	    $resultado = $stmt->execute();
    }
    return $resultado;
}

?>