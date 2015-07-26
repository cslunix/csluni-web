<?php

function checkNocUser( $workID, $fecha, $dbh ){
  $userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;
  if( !$userID == 0){
    $teams = getTeamsArray($userID, $dbh);
    $teamNOC = 21;
    $result = array();
    if (in_array($teamNOC, $teams, false)) {
      //$result = getTpById($workID, $dbh);
      $result = getTpByIdForNoc($workID, $dbh);
      $result['fecha_'] = $fecha;
      $result['noc_coments'] = getNocComments($dbh, $workID, $fecha);

    } else $result['error'] = 'No formas parte de ningun Team NOC';

  }
  else{
    $result = array('action' => 'redirect', 'where' => 'login' );
  }
  echo json_encode($result);
};

function getNocComments($dbh, $workID, $itemID){
  $query  = 'SELECT author, comentario FROM comentarios_noc WHERE mifechaid = :itemID';
  $stmt    = $dbh->prepare($query);
  $stmt->bindParam(':itemID', $itemID, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $result;
};

function checkTokenAndGetDataReg( $token, $dbh ){
  $result =array();
  $userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;

  if( !$userID == 0){
    $teams = getTeamsArray($userID, $dbh);
    $regulatorioTeamID = 1;
    if (in_array($regulatorioTeamID, $teams, false)) {
      //echo "si es de regulatorio";
      //$query   = 'SELECT v.actividad , a.nombre, v.team, v.token, a.created_at FROM validaciones v INNER JOIN actividades a ON a.id = v.actividad WHERE team = 1';
      $query  = 'SELECT id, actividad, team FROM validaciones WHERE team = :regulatorioTeamID AND  token = :token';
      $stmt    = $dbh->prepare($query);
      $stmt->bindParam(':token', $token, PDO::PARAM_STR);
      $stmt->bindParam(':regulatorioTeamID', $regulatorioTeamID, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
       if( count($result) == 0 ){
        $result['error'] = 'No se encontraron actividades, posible token invalido.';
      }
      else{
      $validacion             = $result[0];
      $validacion['session']  = $_SESSION;
      //$validacion['comments'] = getComments($validacion['actividad'], $dbh);
      $validacion['info']     = getTpById($validacion['actividad'], $dbh);
      $result = $validacion;
      }
    } else $result['error'] = 'No eres del team Regulatorio';
  }
  else $result['error'] = 'La session ha caducado, por favor inicia session nuevamente';

  echo json_encode($result);
};

function checkTokenAndGetData( $token, $dbh ){
  $result =array();
  $userID = isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0;

  if( !$userID == 0){
    $teamsIN = getTeamsforIN($userID, $dbh);
    // Get activities and tokens
    $query  = 'SELECT id, actividad, team FROM validaciones WHERE team <> 1 AND ( token = :token  AND team IN ('.$teamsIN.') )';
    $stmt    = $dbh->prepare($query);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    //$stmt->bindParam(':validador', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if( count($result) == 0 ){
      $result['error'] = 'Usted no esta permitido acceder a la validacion de este trabajo.';
    }
    else{
      $validacion             = $result[0];
      $validacion['session']  = $_SESSION;
      //$validacion['comments'] = getComments($validacion['actividad'], $dbh);
      $validacion['info']     = getTpById($validacion['actividad'], $dbh);
      $result = $validacion;
    }
  }
  else{
    $result = array('action' => 'redirect', 'where' => 'login' );
  }
  echo json_encode($result);
};
//--------------------------------------------------------
function getTeamsArray($userID, $dbh){
  $query   = 'SELECT team FROM validadores WHERE usuario = :userID';
  $stmt    = $dbh->prepare($query);
  $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $teams = array();
  foreach ($result as $value) $teams[] = $value['team'];
  //$teams_IN = implode($teams, ',');
  return $teams;
};
//--------------------------------------------------------
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

function getComments($actividad, $dbh){
  $sql = 'SELECT author, comentario, created_at FROM comentarios WHERE actividad = :actividad';
  $stmt =  $dbh->prepare($sql);
  $stmt->bindParam(':actividad', $actividad, PDO::PARAM_STR);
  $stmt->execute();
  $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $comentarios;
};

function userDataFromSession($dbh){
  $noSession = array('error' => 'La sesion llego a ttl = 0');
  $noUser    = array('error' => 'El usuario no existe');
  if( isset( $_SESSION['username'] ) ){
    $id = $_SESSION['id'];
    $query = 'SELECT * FROM countActividades WHERE id = :id';
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if( count($resultados) > 0 ){
    	return json_encode($resultados[0]);
    } else{
    	return json_encode($noUser);
    }
  } else {
    return json_encode($noSession);
  }
}

function worksWithOpenLoop($dbh){
  $noSession = array(array('error' => 'Session not exist.'));
  if( isset( $_SESSION['username'] ) ){
    $solicitante = $_SESSION['id'];
    $query = 'SELECT id, nombre, created_at FROM actividades WHERE solicitante = :solicitante AND ciclo = 0';
    $stmt  = $dbh->prepare($query);
    $stmt->bindParam(':solicitante', $solicitante, PDO::PARAM_STR);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($resultados);
  } else {
    return json_encode($noSession);
  }
}

function workById($id, $dbh){
  $noSession = array('error' => 'Session not exist.');
  $noOwn = array('error' => 'El usuario no es el duenio.');
  if( isset( $_SESSION['username'] ) ){
    $own = $_SESSION['id'];
    // preguntar si la actividad en cuestion es del solicitante = own
    $check = checkOwn($dbh, $own, $id);
    if($check){
      $resultados  = getTpById($id, $dbh);
      return json_encode($resultados);
    } else{
      return json_encode($noOwn);
    }
  } else {
    return json_encode($noSession);
  }
}

function checkOwn($dbh, $own, $id){
  // si retorna FALSE, entonces no es el duenio.
  $query = 'SELECT id FROM actividades a where solicitante = :own and a.id = :id';
  $rpta = TRUE;
  $stmt  = $dbh->prepare($query);
  $stmt->bindParam(':own', $own, PDO::PARAM_STR);
  $stmt->bindParam(':id', $id, PDO::PARAM_STR);
  $stmt->execute();
  $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if( count($resultados) == 0 ){
    return FALSE;
  }
  return $rpta;
}