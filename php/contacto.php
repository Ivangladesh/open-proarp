<?php
    session_start();
    include('dbconn.php');
    date_default_timezone_set('America/Mexico_City');
  // define ("CaptchaPrivateKey", "6Le9QyUcAAAAAA6W1xX4saMhewADCLHXZKfxhI7C");

  $data = json_decode(file_get_contents('php://input'), true);
  if(isset($data['Action']) && !empty($data['Action'])) {
    $action = $data['Action'];
    switch($action) {
        case 'ObtenerUsuario' : ObtenerUsuario();
        break;
        case 'RegistrarContacto' : RegistrarContacto();
        break;
    }
  }

  function ObtenerUsuario () 
  { 
    $session = $_SESSION['SessionStorage'];
    list($one, $two, $three, $four, $five) = explode('|', base64_decode($session));
    $response = new stdClass();
    $response-> callback = 'ObtenerUsuario';
    $response-> data = $three;
    $response-> ok = false;
    echo json_encode($response);

    // $data = json_decode(file_get_contents('php://input'), true);
    // $email = $data['Email'];
    // $contrasena = $data['Password'];
    // $pdo = OpenCon();
    // $procedure = "CALL spObtenerCredenciales('$email', '$contrasena')";
    // $response = new stdClass();
    // try {
    //   $statement=$pdo->prepare($procedure);
    //   $statement->execute();
    //   if($statement->rowCount() > 0){
    //     $user = $statement->fetch();
    //     GenerarToken($user);
    //   } else{
    //     $response-> callback = 'IniciarSesion';
    //     $response-> data = null;
    //     $response-> ok = false;
    //     echo json_encode($response);
    //   }
    // } catch (PDOException $e) {
    //     print "¡Error!: " . $e->getMessage() . "<br/>";
    //     die();
    // }
  }
  function RegistrarContacto (){
    $pdo = OpenCon();
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['UsuarioId'];
    $asunto = $data['Asunto'];
    $mensaje = $data['Mensaje'];
    $insert = "CALL spRegistrarMensajeContacto('$id', '$asunto','$mensaje')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($insert);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            EnviarToken($id);
          } else{
            $response-> callback = 'RegistrarContacto';
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }