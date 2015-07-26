<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

session_start();
	if ( isset($_GET) ) {
		include('../../../config/EissonConnect.php');
		$data = json_decode($_GET['data']);
		$userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
		if ( $userID != 0 ) {
			$db  = new EissonConnect();
			$dbh = $db->enchufalo();
			$teams  = getTeamsArray($userID, $dbh);
			$team = 2; # Team GO
			if ( in_array($team, $teams, false) ) {
				$result = update($dbh, $data->id, $_SESSION['username'], $data->status);
				if($result){
					$result = $_SESSION['username'];
				}
			} else{
				// No forma parte del team GO
				$result[0] = array('error' => 'No formas parte del Team GO');
			}
		} else {
			$result[0] = array('error' => 'La session ha terminado, por favor reinicie session');
		}

	} else {
		$result[0] = array('error' => 'No se ha recibido ningun dato.');
	}

	$result = array('resultado' => $result );

	echo json_encode($result);



function update($dbh, $id, $username, $status) {
	# 0 : pendiente
	# 1 : aprobado
	# 2 : cancelado
	$resultado = false;
	$query = 'UPDATE actividades SET go=:status, go_validator=:username WHERE id=:id';
	$stmt  = $dbh->prepare($query);
	$stmt->bindParam(':status', $status, PDO::PARAM_INT);
	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$resultado = $stmt->execute();
    return $resultado;
}

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