<?php
    include('session.php');
    date_default_timezone_set('America/Mexico_City');
  // define ("CaptchaPrivateKey", "6Le9QyUcAAAAAA6W1xX4saMhewADCLHXZKfxhI7C");

  $data = json_decode(file_get_contents('php://input'), true);
  if(isset($data['Action']) && !empty($data['Action'])) {
    $action = $data['Action'];
    switch($action) {
        case 'ObtenerMensajes' : ObtenerMensajes();
        break;
    }
  }

  function ObtenerMensajes (){
    $pdo = OpenCon();
    $sp = "CALL spObtenerMensajesContacto()";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($sp);
        $statement->execute();
        if($statement->rowCount() > 0){
          $insert = $statement->fetch();
          $response-> callback = 'ObtenerMensajes';
          $response-> data = $insert[0];
          $response-> ok = true;
        } else{
          $response-> callback = 'ObtenerMensajes';
          $response-> data = null;
          $response-> ok = false;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }