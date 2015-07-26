<?php
  session_start();
  header('Content-type: application/json');
  require '../../vendor/Slim/Slim.php';
  include('../../config/EissonConnect.php');
  include('../../resources/php/api.php'); //call for old API
  include('utiles.php');

  \Slim\Slim::registerAutoloader();

  $app = new \Slim\Slim();
  $db  = new EissonConnect();
  $dbh = $db->enchufalo();

// $template = <<<EOT
//  <!DOCTYPE html>
//    <html>
//        <head>
//             </section>
//         </body>
//     </html>
// EOT;

  // GET route
  $app->get( '/user',  function () use ($dbh) {
    echo userDataFromSession($dbh);
  });

  $app->get( '/loop-abierto',  function () use ($dbh) {
    echo worksWithOpenLoop($dbh);
  });

  $app->get( '/mis-actividades/:id', function($id) use ($dbh){
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    echo workById($id, $dbh);
  });

  $app->get( '/validacion/v/:token', function($token) use ($dbh){
    //$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    echo checkTokenAndGetData( $token, $dbh );
  });

  $app->get( '/n/:workID/:fecha', function($workID, $fecha) use ($dbh){
    $workID = filter_var($workID, FILTER_SANITIZE_NUMBER_INT);
    echo checkNocUser( $workID, $fecha, $dbh );
  });

  $app->get( '/validacion/r/:token', function($token) use ($dbh){
    //echo 'data de acuerdo al token de regulatorio';
    echo checkTokenAndGetDataReg( $token, $dbh );
  });

// POST route
$app->post(
    '/post',
    function () {
        echo 'This is a POST route';
    }
);
// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});
  // PUT for update actividad
  $app->put(
      '/put',
      function () {
          echo 'This is a PUT route';
      }
  );

  // DELETE actividad
  $app->delete(
      '/delete',
      function () {
          echo 'This is a DELETE route';
      }
  );

  $app->run();