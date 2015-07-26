<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

	session_start();
	$userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
	if( isset($_GET['workID']) ){
		$workID = $_GET['workID'];
		if( !$userID == 0){
			include('../../../config/EissonConnect.php');
			$db     = new EissonConnect();
			$dbh    = $db->enchufalo();
			$teams  = getTeamsArray($userID, $dbh);
			$teamGO = 2;
			if (in_array($teamGO, $teams, false)) {
				include('../../../resources/php/api.php');
				$result = getTpById($workID, $dbh);
			} else{
				// No forma parte del team GO
				$result = array('error' => 'No formas parte del Team GO');
			}
		} else{
			$result = array('error' => 'La session ha terminado, por favor reinicie session');
		}
	echo json_encode($result);
	}

//-----------------------------------------------------------------------
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