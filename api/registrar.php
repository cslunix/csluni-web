<?php
  /***
  Author: Eisson Alipio
  Date: 16 Marzo 2015 [12:42 Hrs]
  Function: registrar usuarios para el evento
  */
  $postdata = file_get_contents("php://input");
  $data = json_decode($postdata, true);
  $r = init($data);
  echo json_encode($r);

//++++++++++++++++++++++   Functions +++++++++++++++++++++++++++++++
function init($data){
    include('config/EissonConnect.php');
    $db  = new EissonConnect();
    $dbh = $db->enchufalo();
    $q   = 'INSERT INTO hackparty (`nombres`, `correo`, `telefono`,`procedencia`,`esperanzas`, `created_at`)
    VALUES (:nombres, :correo, :telefono, :procedencia, :esperanzas, CURRENT_TIMESTAMP)';
    $stmt  = $dbh->prepare($q);
    $stmt->bindParam(':nombres', $data['names'], PDO::PARAM_STR);
    $stmt->bindParam(':correo', $data['mail'], PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $data['phone'], PDO::PARAM_STR);
    $stmt->bindParam(':procedencia', $data['procedencia'], PDO::PARAM_STR);
    $stmt->bindParam(':esperanzas', $data['hopes'], PDO::PARAM_STR);
    $r = $stmt->execute() ? array('success' => 'Ahora estas registrado para el evento! te esperamos este sabado 27 Marzo 15 Horas! en el Auditorio FIEE UNI, nos vemos!') : array('error' => 'No se pudo realizar el registro');
    return $r;
};