<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

	session_start();
	$msg = 'Error, no se ha recibido ningun dato';
	$status = 'error';
	if( isset($_GET) ){
		include('../../../config/EissonConnect.php');
		//include('/../../../vendor/PHPMailer/PHPMailerAutoload.php');
		$data = json_decode($_GET['data']);;
		if( isset($_SESSION['id']) ){
			//if( $_SESSION['id'] == $data->userID ){
				$db        = new EissonConnect();
				$dbh       = $db->enchufalo();
				$resultado = insertTickets($data, $dbh);
				if($resultado){
					$status = 'success';
					$msg = 'Registo exitoso.';
				} else{
					$status = 'error';
					$msg = 'No se pudo guardar el ticket.';
				}
			//} else {$status = 'error'; $msg = 'Error, el usuario que emitio los tickets es no es el mismo que el usuario de la session actual.';}
		} else {$status = 'error'; $msg = 'Error, la session ha expirado, por favor inicie session nuevamente.';}
	}
	echo json_encode( array($status => $msg) );

function insertTickets($data, $dbh) {
	switch ( $data->tipo ) {
		case 'osiptel':
			$query = 'UPDATE fechas SET osiptel=:numTicket WHERE id=:afectacionID';
		break;
		case 'interrupcion':
			$query = 'UPDATE fechas SET interrupcion=:numTicket WHERE id=:afectacionID';
		break;
		case 'tipo':
			$query = 'UPDATE fechas SET tipo=:numTicket WHERE id=:afectacionID';
		break;

	}
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':numTicket', $data->numTicket, PDO::PARAM_STR);
	$stmt->bindParam(':afectacionID', $data->afectacionID, PDO::PARAM_INT);
	$resultado = $stmt->execute();
    return $resultado;
}
?>