<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

	session_start();
	$userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
	if( !$userID == 0){
		include('../../../config/EissonConnect.php');
		$db     = new EissonConnect();
		$dbh    = $db->enchufalo();
		$teams  = getTeamsArray($userID, $dbh);
		$teamGO = 2;
		// --------------------------------------------
		if (in_array($teamGO, $teams, false)) {
			$postdata = file_get_contents("php://input");
			if( strlen($postdata) > 0 ){
				$data = json_decode( json_decode( json_encode($postdata) ) );
				$page = $data->data->count;
				$query = 'SELECT a.id, a.noc, a.rpn, a.nombre, a.proposito, a.go, a.go_validator, CONCAT(u.nombres, " ", u.apellidos) AS solicitante FROM actividades a INNER JOIN usuarios u ON u.id = a.solicitante ORDER BY a.created_at DESC LIMIT :page , 200';
				$stmt  = $dbh->prepare($query);
				$stmt->bindParam(':page', intval($page), PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$query = 'SELECT a.id, a.noc, a.rpn, a.nombre, a.proposito, a.go, a.go_validator, CONCAT(u.nombres, " ", u.apellidos) AS solicitante FROM actividades a INNER JOIN usuarios u ON u.id = a.solicitante ORDER BY a.created_at DESC LIMIT 200';
				$stmt  = $dbh->prepare($query);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		} else{	$result[0] = array('error' => 'No formas parte del Team GO');	}
	} else{	$result[0] = array('error' => 'La session ha terminado, por favor reinicie session');}

	echo json_encode($result);

// -----------------------------------------------------------------------
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

?>