<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

	session_start();
	if( isset($_SESSION['id']) ){
		include('../../../config/EissonConnect.php');
		$userID    = $_SESSION['id'];
		$db        = new EissonConnect();
		$dbh       = $db->enchufalo();
		$respuesta = getWorks($userID, $dbh);
	} else {
		$respuesta = array();
	}
	echo json_encode( $respuesta );

	function getWorks($userID, $dbh){
		//$q    = 'SELECT id, nombre, noc, rpn, go, go_validator, created_at FROM actividades WHERE solicitante = :userID ORDER BY created_at DESC';
		$q    = 'SELECT a.id, a.nombre, a.noc, a.rpn, a.go, a.go_validator, MIN(n.validacion) AS validacion, n.validador, a.created_at FROM actividades a LEFT JOIN nes n ON n.actividad = a.id WHERE a.solicitante = :userID GROUP BY a.id ORDER BY a.created_at DESC ';
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
?>