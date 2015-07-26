<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

	session_start();
	$userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
	//var_dump( $_SESSION );
	//array(5) { ["username"]=> string(8) "ealipior" ["id"]=> int(16) ["gerencia"]=> int(1) ["gerenciaName"]=> string(33) "ASEGURAMIENTO DE LA CALIDAD Y NOC" ["slim.flash"]=> array(0) { }}
	 $workID = $_GET["workID"];
	// if( !$userID == 0){
	 	include('../../../config/EissonConnect.php');
	 	$db  = new EissonConnect();
	 	$dbh = $db->enchufalo();
	 	killer($dbh, $workID);
	// 	$teamsIN = getTeamsforIN($userID, $dbh);
	// 	if( strlen($teamsIN) != 0 ){
	// 		$query   = 'SELECT v.actividad , a.nombre, v.team, v.token, a.rpn, a.noc, a.go, a.go_validator, a.created_at FROM validaciones v INNER JOIN actividades a ON a.id = v.actividad WHERE team <> 1 AND team IN ('.$teamsIN.')  ORDER BY a.created_at DESC';
	// 		$stmt    = $dbh->prepare($query);
	// 		$stmt->execute();
	// 		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// 	} else{
	 		$result['status'] = true;
	// 	}
	// } else{
	// 	$result = array();
	// }
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


function killer($dbh, $workID){
	$q = array('DELETE FROM actividades WHERE id=:workID','DELETE FROM map_involucrados WHERE actividad=:workID','DELETE FROM map_afectados WHERE actividad=:workID','DELETE FROM nes WHERE actividad=:workID','DELETE FROM zonas_afectadas WHERE actividad=:workID','DELETE FROM validaciones WHERE actividad=:workID','DELETE FROM personal WHERE actividad=:workID','DELETE FROM fechas WHERE actividad=:workID','DELETE FROM archivos WHERE actividad=:workID','DELETE FROM comentarios WHERE actividad=:workID');
	foreach ($q as $v) {
		$stmt = $dbh->prepare($v);
		$stmt->bindParam(':workID', $workID, PDO::PARAM_INT);
		$stmt->execute();
	}
};