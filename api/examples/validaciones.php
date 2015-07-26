<?php

    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);
	session_start();

	$userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
	if( !$userID == 0){
		include('../../../config/EissonConnect.php');
		$db  = new EissonConnect();
		$dbh = $db->enchufalo();
		$teamsIN = getTeamsforIN($userID, $dbh);
		if( strlen($teamsIN) != 0 ){
			// Get activities and tokens
			$postdata = file_get_contents("php://input");

			if( strlen($postdata) > 0 ){
				$data = json_decode( json_decode( json_encode($postdata) ) );
				$page = $data->data->count;
				//$query = 'SELECT a.id, a.noc, a.rpn, a.nombre, a.proposito, a.go, a.go_validator, CONCAT(u.nombres, " ", u.apellidos) AS solicitante FROM actividades a INNER JOIN usuarios u ON u.id = a.solicitante ORDER BY a.created_at DESC LIMIT :page , 200';
				$query = 'SELECT
				 			a.id,
				 			a.rpn,
				 			a.go,
				 			a.go_validator,
				 			a.noc,
				 			a.created_at,
				 			a.nombre,
				 			v.validador as encargado,
				 			n.validador,
				 			CONCAT(u.nombres, " ", u.apellidos) AS solicitante,
				 			v.team,
				 			v.token,
				 			MIN(n.validacion) AS validacion
				 			FROM nes n
				 			INNER JOIN actividades a ON a.id = n.actividad
				 			INNER JOIN usuarios u ON u.id = a.solicitante
				 			INNER JOIN validaciones v ON n.actividad  = v.actividad
				 			WHERE v.team <> 1 AND  v.team IN (' . $teamsIN . ') GROUP BY n.actividad ORDER BY a.go ASC, a.id DESC LIMIT :page , 200';
				$stmt  = $dbh->prepare($query);
				$stmt->bindParam(':page', intval($page), PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$query = 'SELECT
				 			a.id,
				 			a.rpn,
				 			a.go,
				 			a.go_validator,
				 			a.noc,
				 			a.created_at,
				 			a.nombre,
				 			v.validador as encargado,
				 			n.validador,
				 			CONCAT(u.nombres, " ", u.apellidos) AS solicitante,
				 			v.team,
				 			v.token,
				 			MIN(n.validacion) AS validacion
				 			FROM nes n
				 			INNER JOIN actividades a ON a.id = n.actividad
				 			INNER JOIN usuarios u ON u.id = a.solicitante
				 			INNER JOIN validaciones v ON n.actividad  = v.actividad
				 			WHERE v.team <> 1 AND  v.team IN (' . $teamsIN . ') GROUP BY n.actividad ORDER BY a.go ASC, a.id DESC  LIMIT 200';
				$stmt    = $dbh->prepare($query);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}


		} else{
			$result = array();
		}
	} else{
		$result = array();
	}
	echo json_encode($result);

function getTeamsforIN($userID, $dbh){
	$query   = 'SELECT team FROM validadores WHERE usuario = :userID';
	$stmt    = $dbh->prepare($query);
	$stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$teams = array();
	foreach ($result as $value) $teams[] = $value['team'];
	$teams_IN = implode($teams, ',');
	return $teams_IN;
};