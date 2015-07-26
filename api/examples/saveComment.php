<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);
    session_start();
/***
Author: Eisson Alipio
Date: 18 Feb 2015 [18:02 Hrs]
Function: Guardar Comentario en DB y enviar el comentario a los involucrados por correo
*/
	if( isset($_GET) ){
		include('../../../config/EissonConnect.php');
		include('../../../vendor/PHPMailer/PHPMailerAutoload.php');
		$db           = new EissonConnect();
		$dbh          = $db->enchufalo();

		$session      = json_decode($_GET['session']);
		$actividad    = filter_var($_GET['actividad'],  FILTER_VALIDATE_INT);
		//$validacionID = ( $_GET['id'] != 0) ? $_GET['id'] : NULL;
		$comentario   = trim( htmlentities( $_GET['data'], ENT_QUOTES | ENT_IGNORE, "UTF-8") );
		$comentarioDB = trim(  $_GET['data'] );
		$workName     = html_entity_decode(htmlentities( $_GET['workName'] , ENT_QUOTES | ENT_IGNORE, "UTF-8"));
		$author       = filter_var($session->username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		$asunto       = 'Comentario de '. $author . ' - ' . $actividad .' '. $workName;
		$result       = array('author' => $session->username,'comentario' => $comentarioDB, 'created_at'  =>  date('Y-m-d h:i:s')	);

		saveComment($comentarioDB, $actividad, $author, $dbh);
		//saveComment($comentarioDB, $validacionID, $actividad, $author, $dbh);
		sendEmailComments($dbh, $actividad, $comentario, $asunto, $author, $workName);
		echo json_encode($result);
	}

// Functions
function saveComment($comentario, $actividad, $author, $dbh) {
//function saveComment($comentario, $validacion, $actividad, $author, $dbh) {
	$resultado = false;
    if( strlen($comentario) > 0){
	    $query = 'INSERT INTO comentarios (`actividad`, `author`,`comentario`, `created_at`) VALUES (:actividad, :author, :comentario, CURRENT_TIMESTAMP)';
	    //$query = 'INSERT INTO comentarios (`validacion`,`actividad`, `author`,`comentario`, `created_at`) VALUES (:validacion, :actividad, :author, :comentario, CURRENT_TIMESTAMP)';
		$stmt  = $dbh->prepare($query);
		//$stmt->bindParam(':validacion', $validacion, PDO::PARAM_STR);
		$stmt->bindParam(':actividad',  $actividad, PDO::PARAM_INT);
		$stmt->bindParam(':author', $author, PDO::PARAM_STR);
		$stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);
	    $resultado = $stmt->execute();
    }
    return $resultado;
};

function getEmails($dbh, $workID){
	$q = 'SELECT u.correo  AS solicitante, t.correo AS team FROM actividades a INNER JOIN usuarios u ON u.id = a.solicitante INNER JOIN validaciones v ON v.actividad=a.id INNER JOIN teams t ON t.id=v.team WHERE a.id=:workID';
	$stmt  = $dbh->prepare($q);
	$stmt->bindParam(':workID',  $workID, PDO::PARAM_INT);
	$resultado = $stmt->execute();
	if( $resultado ){
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		return array();
	}
};

function sendEmailComments($dbh, $workID, $comentario, $asunto, $author, $workName){
	$correos = getEmails($dbh, $workID);
	if( count($correos) > 0){
		$teamsMails = array();
		foreach ($correos as $value) {
			$teamsMails[]    = $value['team'];
			$solicitanteMail = $value['solicitante'];
		}
		$cuerpo = '';
		list($solicitante, $garbage) = explode('@', $solicitanteMail);
		$cuerpo .= 'Estimado(a) '. $solicitante . '<br><br>';
		$cuerpo .= 'El usuario(a) <b>'.$author . '</b> realiz&oacute; el siguiente comentario en la actividad con ID <b>' . $workID . '</b> (' . $workName . ') :<br><br>';
		$cuerpo .= '<div style ="background-color:#333; color:#fff; padding:20px;font-family:sans-serif">';
		$cuerpo .= $comentario;
		$cuerpo .= '</div><br>';
		$cuerpo .= 'Detalles del trabajo: <br>' . 'http://aseguramientodelacalidad/GO/modules/validacion/#/busqueda/solicitante/actividad/'.$workID;
		$cuerpo .= '<br>Por favor usar Chrome o Firefox<br>';
		$cuerpo .= '<br>Saludos Cordiales';
		sendEmail($solicitanteMail, $teamsMails, $cuerpo, $asunto);
	}
};

function sendEmail($solicitanteMail, $teamsMails, $cuerpo, $asunto){
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
  	$mail->AddAddress($solicitanteMail);
  	//$mail->addCC('gestion.operativa@entel.pe');
  	foreach($teamsMails as $team) $mail->addCC($team);
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