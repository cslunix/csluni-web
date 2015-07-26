<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);
	session_start();

$dia  = date('D');  //Mon, Wed, Fri
$hora = date('H');
$resu = array();

	$userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
	if( !$userID == 0){
		include('../../../config/EissonConnect.php');
		$db    = new EissonConnect();
		$dbh   = $db->enchufalo();
		$query = "SELECT f.actividad, GROUP_CONCAT(n.nombre) as nes,f.fecha, f.id as fechaID, f.start, f.resultado, f.afectacion, f.statusnoc, f.validador, a.noc, a.nombre, a.rpn, u.celular, CONCAT(u.nombres,' ', u.apellidos) AS solicitante
		FROM fechas f
		INNER JOIN actividades a ON f.actividad = a.id
		INNER JOIN nes n ON n.actividad = f.actividad
		INNER JOIN usuarios u ON a.solicitante = u.id
		WHERE f.fecha BETWEEN ADDDATE(CURDATE(), -1) AND  ADDDATE(CURDATE(), 1) AND a.go = 1 GROUP BY f.id ORDER BY fecha ASC";
		// para que tome hasta el Lunes
		# WHERE f.fecha between CURDATE() AND  ADDDATE(CURDATE(), 3) AND a.go = 1 GROUP BY f.actividad  ORDER BY fecha ASC";
		$stmt  = $dbh->prepare($query);
		$stmt->execute();
		$resu = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else{	$resu[0] = array('error' => 'La session ha terminado, por favor vuelva a iniciar session.');}

	echo json_encode($resu);


?>