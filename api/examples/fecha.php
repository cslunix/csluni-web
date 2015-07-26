<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

session_start();
	if ( isset($_GET) ) {
		include('../../../config/EissonConnect.php');
		$data = json_decode($_GET['data']);
		$opt  = $_GET['option'];
		$r = array();
		$userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
		if ( $userID != 0 ) {
			$db  = new EissonConnect();
			$dbh = $db->enchufalo();
			switch ($opt) {
				case 'del':
					$r['status'] =  delFecha($dbh, $data);
					$r['data']   =  $data;
					# code...
					break;
				case 'upd':
					$r['status'] =  updFecha($dbh, $data);
					$r['data']   =  $data;
					# code...
					break;
				case 'add':
					$r['status'] =  addFecha($dbh, $data);
					$lastId = $dbh->lastInsertId();
					$data->id = $lastId;
					$data->afectacion = 0;
					$data->end = $data->horaFin;
					$data->fin =  null;
					$data->fin_af =  null;
					$data->inicio =  null;
					$data->inicio_af =  null;
					$data->interrupcion =  null;
					$data->osiptel =  null;
					$data->resultado = null;
					$data->start = $data->horaInicio;
					$data->statusnoc = 0;
					$data->tiempo = null;
					$data->tipo = 0;
					$data->validacion = 0;
					$data->validador =  null;

					$r['data']   =  $data;
					# code...
					break;
				default:
					$r['status'] =  'none';
					break;
			}

		} else {
			//$result[0] = array('error' => 'La session ha terminado, por favor reinicie session');
		}

	} else {
		//$result[0] = array('error' => 'No se ha recibido ningun dato.');
	}

	echo json_encode($r);

function addFecha($dbh, $data){
	$q = 'INSERT INTO fechas (actividad,fecha,start,end,created_at) VALUES (:workID, :fecha, :horaInicio, :horaFin, CURRENT_TIMESTAMP)';
	$stmt  = $dbh->prepare($q);
	$stmt->bindParam(':workID', $data->workID , PDO::PARAM_INT);
	$stmt->bindParam(':fecha',  $data->fecha , PDO::PARAM_STR);
	$stmt->bindParam(':horaInicio', $data->horaInicio , PDO::PARAM_STR);
	$stmt->bindParam(':horaFin', $data->horaFin , PDO::PARAM_STR);
	$r = $stmt->execute();
	return $r;
};

function delFecha($dbh, $data){
	$q = 'DELETE FROM fechas WHERE id=:id';
	$stmt  = $dbh->prepare($q);
	$stmt->bindParam(':id', $data->id , PDO::PARAM_INT);
	$r = $stmt->execute();
	return $r;
};

function updFecha($dbh, $data){
	$q = 'UPDATE fechas SET fecha=:fecha WHERE id=:id';
	$stmt  = $dbh->prepare($q);
	$stmt->bindParam(':id', $data->elementID , PDO::PARAM_INT);
	$stmt->bindParam(':fecha', $data->newDate , PDO::PARAM_INT);
	$r = $stmt->execute();
	return $r;
};

?>