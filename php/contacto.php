<?php
    include('session.php');
    date_default_timezone_set('America/Mexico_City');
  // define ("CaptchaPrivateKey", "6Le9QyUcAAAAAA6W1xX4saMhewADCLHXZKfxhI7C");

  $data = json_decode(file_get_contents('php://input'), true);
  if(isset($data['Action']) && !empty($data['Action'])) {
    $_SESSION['LAST_ACTIVITY'] = time();
    $action = $data['Action'];
    switch($action) {
        case 'RegistrarMensaje' : RegistrarMensaje();
        break;
    }
  }

  function RegistrarMensaje (){
    $response = new stdClass();
    $data = json_decode(file_get_contents('php://input'), true);
    $session = ObtenerUsuarioPorSesion();
    $email = $session->data;
    $asunto = $data['Asunto'];
    $mensaje = $data['Mensaje'];
    if (!empty($email) && !empty($asunto) && !empty($mensaje)) {
      $pdo = OpenCon();
      $sp = "CALL spRegistrarMensajeContacto('$asunto','$mensaje', '$email')";
      try {
          $statement=$pdo->prepare($sp);
          $statement->execute();
          if($statement->rowCount() > 0){
            $insert = $statement->fetch();
            $response-> callback = 'RegistrarMensaje';
            $response-> data = $insert[0];
            $response-> ok = true;
          } else{
            $response-> callback = 'RegistrarMensaje';
            $response-> data = null;
            $response-> ok = false;
          }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    } else{
      $response-> callback = 'RegistrarMensaje';
      $response-> data = null;
      $response-> ok = false;
    }

    echo json_encode($response);
  }

  ?>