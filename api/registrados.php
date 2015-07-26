<?php

  include('config/EissonConnect.php');
  $db  = new EissonConnect();
  $dbh = $db->enchufalo();
  $query = 'SELECT * FROM hackparty';
  $stmt  = $dbh->prepare($query);
  $stmt->execute();
  $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode( $r );

?>