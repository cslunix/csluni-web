<?php
    //ini_set('session.gc_maxlifetime', 72000);
    //session_set_cookie_params(72000);

session_start();
$resultado = array();
if( !isset($_SESSION['username']) ){
	//No tiene session abierta, lo mandaremos al login con una galleta en la mano
	// que le hara regresar despues a validar la actividad.
	if( isset($_POST['token']) ){
		$token   = $_POST['token'];
		$_SESSION['URLvalidacion'] = 'http://'.$_SERVER['HTTP_HOST'].'/GO/modules/validacion/index.php?token='.$token;
	}
	$resultado = array('action' => 'redirect', 'where' => 'login' );
} else{
	include('../../../resources/php/api.php');
	if( isset($_SESSION['URLvalidacion']) ){
		// ya tiene seteada la URLvalidacion, es decir que viene de haberse logeado
		unset($_SESSION['URLvalidacion']);
	}
	///modules/validacion/index.php?token=109d9c8ed487a1639d6f5c222bc1ce394
	// Requisitos:
	// Estar Logueado en el sistema
	// almacenar la URL de origen para luego redireccionarlo
	if( isset($_POST['token']) ){
		include('../../../config/EissonConnect.php');
		$db      = new EissonConnect();
		$dbh     = $db->enchufalo();
		$token   = $_POST['token'];
		$usuario = $_SESSION['id'];

		$query   = 'SELECT id, actividad, team FROM validaciones WHERE token = :token';
		$stmt    = $dbh->prepare($query);
		$stmt->bindParam(':token', $token, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// token valido
		if (count($result) == 1){
			$team      = $result[0]['team'];
			$actividad = $result[0]['actividad'];
			$idValidac = $result[0]['id'];
			// token valido, veamos si el usuario esta en el team validadores
			$query = 'SELECT usuario FROM validadores WHERE team = :team';
			$stmt  = $dbh->prepare($query);
			$stmt->bindParam(':team', $team, PDO::PARAM_STR);
			$stmt->execute();
			$validadores = array();
			while($data = $stmt->fetch( PDO::FETCH_ASSOC )){
			     $validadores[] = $data['usuario'];
			}
			if (in_array($usuario, $validadores)) {
			    // el usuario esta en el team de validadores
			    // dibujar todos los datos relacionados a la actividad usando el API
			    $dataActividad = getTpById($actividad, $dbh);
			    $comentarios = getComments($actividad, $dbh);
			    $dataActividad['idValid'] = $idValidac; // add id validacion because I need it
			    $dataActividad['author']  = $_SESSION['username']; // add author for table comments_om
			    $dataActividad['comentarios']  = $comentarios;
			    $dataActividad = json_encode($dataActividad);
			    $resultado = array('action' => 'drawResults', 'data' => $dataActividad);
			} else{
				// el usuario no esta en el team de validadores, se ha conseguido el link de alguna forma, esta haciendo un mal uso del sistema, banear
				$errorMsg =  'No formas parte del team de validacion.';
				$resultado = array('action' => 'showError', 'msg' => $errorMsg );
			}
		} else{
			// error, token invalido... cuando el token no se encuentra en la DB
			$errorMsg = 'Error, el token no se encuentra en la DB';
			$resultado = array('action' => 'showError', 'msg' => $errorMsg );
		}
	} else{
		// si no llego token ni nada, sin embargo el usuario esta logeado en el sistema
		$resultado = array('action' => 'redirect', 'where' => 'main' );
	}
} // end else session

echo json_encode($resultado);




function getComments($actividad, $dbh){
	$sql = 'SELECT author, comentario, created_at FROM comentarios WHERE actividad = :actividad';
	$stmt =  $dbh->prepare($sql);
	$stmt->bindParam(':actividad', $actividad, PDO::PARAM_STR);
	$stmt->execute();
	$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $comentarios;
}