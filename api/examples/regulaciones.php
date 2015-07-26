<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);
	session_start();
	$userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
	if( !$userID == 0){
		include('../../../config/EissonConnect.php');
		$db  = new EissonConnect();
		$dbh = $db->enchufalo();
		$teams = getTeamsArray($userID, $dbh);
		$regulatorioTeamID = 1;
		if (in_array($regulatorioTeamID, $teams, false)) {
			$postdata = file_get_contents("php://input");
			if( strlen($postdata) > 0 ){
				$data = json_decode( json_decode( json_encode($postdata) ) );
				$page = $data->data->count;

				$query = 'SELECT a.id,
							(SELECT GROUP_CONCAT(ma.red) FROM map_afectados ma WHERE ma.actividad = a.id) AS redes,
							f.validador,f.validacion as regulatorio, f.fecha,f.start, f.end, CONCAT(u.nombres, " ", u.apellidos) AS solicitante,
							a.nombre, a.go, a.go_validator,a.proposito, v.token	FROM validaciones v
							INNER JOIN actividades a ON a.id = v.actividad
							INNER JOIN fechas f ON f.actividad = a.id
							INNER JOIN usuarios u ON a.solicitante = u.id
							WHERE v.team = 1 AND f.afectacion = 1
							ORDER BY a.created_at DESC LIMIT :page , 250';
				$stmt  = $dbh->prepare($query);
				$stmt->bindParam(':page', intval($page), PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$query = 'SELECT a.id,
							(SELECT GROUP_CONCAT(ma.red) FROM map_afectados ma WHERE ma.actividad = a.id) AS redes,
							f.validador,f.validacion as regulatorio, f.fecha,f.start, f.end, CONCAT(u.nombres, " ", u.apellidos) AS solicitante,
							a.nombre, a.go, a.go_validator,a.proposito, v.token	FROM validaciones v
							INNER JOIN actividades a ON a.id = v.actividad
							INNER JOIN fechas f ON f.actividad = a.id
							INNER JOIN usuarios u ON a.solicitante = u.id
							WHERE v.team = 1 AND f.afectacion = 1
							ORDER BY a.created_at DESC LIMIT 250';
				$stmt    = $dbh->prepare($query);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		} else{	$result = array();	}
	} else{	$result = array();	}

	echo json_encode($result);

function getTeamsArray($userID, $dbh){
	$query   = 'SELECT team FROM validadores WHERE usuario = :userID';
	$stmt    = $dbh->prepare($query);
	$stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$teams = array();
	foreach ($result as $value) $teams[] = $value['team'];
	return $teams;
};