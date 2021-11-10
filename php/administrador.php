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
        case 'ObtenerDetalleMensaje' : ObtenerDetalleMensaje();
        break;
        case 'ActualizarEstadoMensaje' : ActualizarEstadoMensaje();
        break;
        case 'EliminarMensaje' : EliminarMensaje();
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
        while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
          $response-> callback = 'ObtenerMensajes';
          $response-> data = $r;
          $response-> ok = true;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }

  function ObtenerDetalleMensaje (){
    $response = new stdClass();
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['MensajeId'];
    if (!empty($id)) {
      $pdo = OpenCon();
      $select = "CALL spObtenerDetalleMensajePorId('$id')";
      try {
          $statement=$pdo->prepare($select);
          $statement->execute();
          if($statement->rowCount() > 0){
              $datos = $statement->fetch();
              $response-> callback = 'ObtenerDetalleMensaje';
              $response-> data = $datos;
              $response-> ok = true;
              
          } else{
              $response-> callback = 'ObtenerDetalleMensaje';
              $response-> data = null;
              $response-> ok = false;
          }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    } else{
      $response-> callback = 'ObtenerDetalleMensaje';
      $response-> data = null;
      $response-> ok = false;
    }

    echo json_encode($response);
  }

  function ActualizarEstadoMensaje (){
    $response = new stdClass();
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['MensajeId'];
    if (!empty($id)) {
      $pdo = OpenCon();
      $update = "CALL spActualizarEstadoMensaje('$id')";
      try {
          $statement=$pdo->prepare($update);
          $statement->execute();
          if($statement->rowCount() > 0){
              $count = $statement->rowCount();
              $response-> callback = 'ActualizarEstadoMensaje';
              $response-> data = $count;
              $response-> ok = true;
            } else{
              $response-> callback = 'ActualizarEstadoMensaje';
              $response-> data = null;
              $response-> ok = false;
            }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        } 
    } else{
      $response-> callback = 'ActualizarEstadoMensaje';
      $response-> data = null;
      $response-> ok = false;
    }

    echo json_encode($response);
  }

  function EliminarMensaje (){
    $response = new stdClass();
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['MensajeId'];
    if (!empty($id)) {
      $pdo = OpenCon();
      $update = "CALL spEliminarMensaje('$id')";
      try {
          $statement=$pdo->prepare($update);
          $statement->execute();
          if($statement->rowCount() > 0){
              $count = $statement->rowCount();
              $response-> callback = 'EliminarMensaje';
              $response-> data = $count;
              $response-> ok = true;
            } else{
              $response-> callback = 'EliminarMensaje';
              $response-> data = null;
              $response-> ok = false;
            }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        } 
    } else{
      $response-> callback = 'EliminarMensaje';
      $response-> data = null;
      $response-> ok = false;
    }

    echo json_encode($response);
  }