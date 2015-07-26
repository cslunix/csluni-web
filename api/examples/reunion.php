<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);
    session_start();
/***
Solo se muestran actividades
*/
$dia  = date('D');  //Mon, Wed, Fri
$hora = date('H');
$resu = array();
if( 1 ){
//if( $dia != 'Sat' and $dia != 'Sun'){
	if( $dia == 'Fri' ){
		// solamente los viernes
		include('../../../config/EissonConnect.php');
		$db    = new EissonConnect();
		$dbh   = $db->enchufalo();
		$query = "SELECT DISTINCT f.actividad, a.nombre, a.go, a.go_validator, a.rpn, a.created_at, CONCAT(u.nombres,' ', u.apellidos) as solicitante,
				  MIN(n.validacion) AS validacion, n.validador,
				  MIN(f.validacion) AS  regulatorio, f.validador AS regulador,
				  MIN(f.afectacion) AS afectacion
			      FROM fechas f
			      INNER JOIN actividades a ON f.actividad = a.id
			      LEFT JOIN nes n ON n.actividad = a.id
			      INNER JOIN usuarios u ON a.solicitante = u.id
			      WHERE (f.fecha BETWEEN ADDDATE(CURDATE(), 1) AND  ADDDATE(CURDATE(), 3) ) GROUP BY f.id";
		$stmt  = $dbh->prepare($query);
		$stmt->execute();
		$resu = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		// cualquier dia que no sea viernes
		include('../../../config/EissonConnect.php');
		$db    = new EissonConnect();
		$dbh   = $db->enchufalo();
		$query = "SELECT DISTINCT f.actividad, a.nombre, a.go, a.go_validator, a.rpn, a.created_at, CONCAT(u.nombres,' ', u.apellidos) as solicitante,
				  MIN(n.validacion) AS validacion, n.validador,
				  MIN(f.validacion) AS  regulatorio, f.validador AS regulador,
				  MIN(f.afectacion) AS afectacion
			      FROM fechas f
			      INNER JOIN actividades a ON f.actividad = a.id
			      INNER JOIN usuarios u ON a.solicitante = u.id
			      LEFT JOIN nes n ON n.actividad = a.id
			      WHERE (f.fecha = ADDDATE(CURDATE(), 1) )  GROUP BY f.id";
		$stmt  = $dbh->prepare($query);
		$stmt->execute();
		$resu = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
	echo json_encode($resu);
?>