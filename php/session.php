<?php
    session_start();
    include('dbconn.php');
    date_default_timezone_set('America/Mexico_City');
  // define ("CaptchaPrivateKey", "6Le9QyUcAAAAAA6W1xX4saMhewADCLHXZKfxhI7C");

  $data = json_decode(file_get_contents('php://input'), true);
  if(isset($data['Action']) && !empty($data['Action'])) {
    $action = $data['Action'];
    switch($action) {
        case 'IniciarSesion' : IniciarSesion();
        break;
        case 'ValidarSesion' : ValidarSesion();
        break;
        case 'RegistrarUsuario' : RegistrarUsuario();
        break;
        case 'CerrarSesion' : CerrarSesion();
        break;
    }
  }

  function IniciarSesion () 
  { 
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['Email'];
    $contrasena = $data['Password'];
    $pdo = OpenCon();
    $procedure = "CALL spObtenerCredenciales('$email', '$contrasena')";
    $response = new stdClass();
    try {
      $statement=$pdo->prepare($procedure);
      $statement->execute();
      if($statement->rowCount() > 0){
        $user = $statement->fetch();
        GenerarToken($user);
      } else{
        $response-> callback = 'IniciarSesion';
        $response-> data = null;
        $response-> ok = false;
        echo json_encode($response);
      }
    } catch (PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }
  }
  function GenerarToken ($r){
    $pdo = OpenCon();
    $id = $r['UsuarioId'];
    $username = $r['Email'];
    $tipo = $r['TipoUsuarioId'];
    $hoy = date("m.d.y H:i:s");
    $token = $id.'|'.base64_encode($tipo . $id . $username . $hoy);
    $insert = "CALL spActualizarToken('$token', '$id')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($insert);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            EnviarToken($id);
          } else{
            $response-> callback = 'GenerarToken';
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }

  function EnviarToken ($id){
    $pdo = OpenCon();
    $select = "CALL spObtenerToken('$id')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        if($statement->rowCount() > 0){
            $token = $statement->fetch();
            $_SESSION['SessionStorage'] = strval($token[0]);
            $response-> callback = 'EnviarToken';
            $response-> data = $token[0];
            $response-> ok = true;
            
        } else{
            $response-> callback = 'EnviarToken';
            $response-> data = null;
            $response-> ok = false;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }

  function ValidarSesion (){
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['Token'];
    $pdo = OpenCon();
    $select = "CALL spValidarSesion('$token')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        if($statement->rowCount() > 0){
            while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
              $response-> callback = 'ValidarSesion';
              $response-> data = $r;
              $response-> ok = true;
              }
            
        } else{
          $response-> callback = 'ValidarSesion';
          $response-> data = null;
          $response-> ok = false;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }

  function RegistrarUsuario(){
    // Habilitar cuando se habilite el reCaptcha

    // $reCaptchaToken = $_POST['ReCaptchaToken'];
    // $cu = curl_init();
    // curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    // curl_setopt($cu, CURLOPT_POST, 1);
    // curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => CaptchaPrivateKey, 'response' => $reCaptchaToken)));
    // curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
    // $response = curl_exec($cu);
    // curl_close($cu);
    // $jsonResponse = json_decode($response,true);
    // if($jsonResponse['success'] == 1 && $jsonResponse['score'] >= 0.5){
      $data = json_decode(file_get_contents('php://input'), true);
      $pdo = OpenCon();
      $caracteres = "(#|$|-|_|&|%)";
      $nombre = $data['Nombre'];
      $paterno = $data['Paterno'];
      $materno = $data['Materno'];
      $fechaNacimiento = $data['FechaNacimiento'];
      $email = $data['Email'];
      $password = $data['Password'];
      $response = new stdClass();
      // if(strlen($password) < 8){
      //   $jsondata = array(
      //     'status' => 200,
      //     'data' => "La contraseña debe tener una longitud mínima de 8 caracteres.",
      //     'ok' => false
      //   );
      //     echo json_encode($jsondata);
      // } else if(!preg_match($caracteres, $password)){
      //   $jsondata = array(
      //     'status' => 200,
      //     'data' => "La contraseña debe tener por lo menos un caracter especial: #1$,-,_,&,%",
      //     'ok' => false
      //   );
      //     echo json_encode($jsondata);
      // } else{
        $procedure = "CALL spRegistrarUsuario('$nombre', '$paterno', '$materno', '$fechaNacimiento', '$email', '$password')";
        try {
            $statement=$pdo->prepare($procedure);
            $statement->execute();
            if($statement->rowCount() > 0){
                $user = $statement->fetch();
                $response-> callback = 'RegistrarUsuario';
                $response-> data = $user[0];
                $response-> ok = true;
              } else{
                $response-> callback = 'RegistrarUsuario';
                $response-> data = null;
                $response-> ok = false;
              }
          } catch (PDOException $e) {
              print "¡Error!: " . $e->getMessage() . "<br/>";
              die();
          }
          echo json_encode($response);
      //}
  // } else{
  //   $jsondata = array(
  //   'status' => 200,
  //   'data' => null,
  //   'captcha' => 'Eres un robot'
  // );
//     echo json_encode($response);
// }
}

function CerrarSesion (){
  session_unset();
  session_destroy();
  $response = new stdClass();
  $response-> callback = 'CerrarSesion';
  $response-> data = 'Sesión cerrada exitosamente';
  $response-> ok = true;
  echo json_encode($response);
}
