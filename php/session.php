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
        case 'ObtenerUsuario' : ObtenerUsuario();
        break;
        case 'ObtenerSesion' : ObtenerSesion();
        break;
        case 'EstadoSesion' : EstadoSesion();
        break;
        case 'CerrarSesion' : CerrarSesion();
        break;
    }
  }

  function IniciarSesion () {
    $response = new stdClass();
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['Email'];
    $contrasena = $data['Password'];
    if (!empty($email) && !empty($contrasena)) {
      $pdo = OpenCon();
      $procedure = "CALL spObtenerCredenciales('$email', '$contrasena')";
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
    } else{
      $response-> callback = 'IniciarSesion';
      $response-> data = null;
      $response-> ok = false;
      echo json_encode($response);
    }
  }

  function GenerarToken ($r){
    $response = new stdClass();
    $id = $r['UsuarioId'];
    $username = $r['Email'];
    $nombreCompleto = $r['NombreCompleto'];
    $tipo = $r['TipoUsuarioId'];
    if (!empty($id) && !empty($username) && !empty($nombreCompleto) && !empty($tipo)) {
      $pdo = OpenCon();
      $hoy = date("m.d.y H:i:s");
      $token = base64_encode($tipo . '|' . $username . '|' . $nombreCompleto . '|'  . $id . '|' . $hoy);
      $insert = "CALL spActualizarToken('$token', '$id')";
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
    } else{
      $response-> callback = 'GenerarToken';
      $response-> data = null;
      $response-> ok = false;
      echo json_encode($response);
    }
  }

  function EnviarToken ($id){
    $response = new stdClass();
    if (!empty($id)) {
      $pdo = OpenCon();
      $select = "CALL spObtenerToken('$id')";
      try {
          $statement=$pdo->prepare($select);
          $statement->execute();
          if($statement->rowCount() > 0){
              $token = $statement->fetch();
              $_SESSION['SessionStorage'] = strval($token[0]);
              $response-> callback = 'EnviarToken';
              $response-> data = $token[0];
              $response-> ok = true;
              $_SESSION['LAST_ACTIVITY'] = time();
          } else{
              $response-> callback = 'EnviarToken';
              $response-> data = null;
              $response-> ok = false;
          }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    } else{
      $response-> callback = 'EnviarToken';
      $response-> data = null;
      $response-> ok = false;
    }
    echo json_encode($response);
  }

  function ValidarSesion (){
    $response = new stdClass();
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['Token'];
    if (!empty($token)) {
      $pdo = OpenCon();
      $select = "CALL spValidarSesion('$token')";
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
    } else{
      $response-> callback = 'ValidarSesion';
      $response-> data = null;
      $response-> ok = false;
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
      $caracteres = "(#|$|^|+|=|!|*|(|)|@|%|&)";
      $nombre = $data['Nombre'];
      $paterno = $data['Paterno'];
      $materno = $data['Materno'];
      $fechaNacimiento = $data['FechaNacimiento'];
      $email = $data['Email'];
      $password = $data['Password'];
      $response = new stdClass();
      if(strlen($password) < 8){
        $response-> callback = 'RegistrarUsuario';
        $response-> data = "La contraseña debe tener una longitud mínima de 8 caracteres.";
        $response-> ok = false;
        echo json_encode($response);
      } else if(!preg_match($caracteres, $password)){
        $response-> callback = 'RegistrarUsuario';
        $response-> data = "La contraseña debe tener por lo menos un caracter especial: # $ ^ + = ! * ( ) @ % &.";
        $response-> ok = false;
        echo json_encode($response);
      } else{
        $existeEmail = ExisteEmail($email);
        if(!$existeEmail){
          if (!empty($nombre) && !empty($paterno) && !empty($materno) && !empty($fechaNacimiento) && !empty($email)) {
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
                    $response-> data = "Ha ocurrido un error, consulte al administrador.";
                    $response-> ok = false;
                  }
              } catch (PDOException $e) {
                  print "¡Error!: " . $e->getMessage() . "<br/>";
                  die();
              }
          } else{
            $response-> callback = 'RegistrarUsuario';
            $response-> data = "Alguno de los valores está vacío o incompleto";
            $response-> ok = false;
          }
        } else{
          $response-> callback = 'RegistrarUsuario';
          $response-> data = "El email ya ha sido registrado.";
          $response-> ok = false;
        }

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

function ExisteEmail ($email){
  $response = true;
  $pdo = OpenCon();
  $select = "CALL spValidarEmail('$email')";
  try {
      $statement=$pdo->prepare($select);
      $statement->execute();
      if($statement->rowCount() > 0){
          $response = true;
        } else{
          $response = false;
        }
    } catch (PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }
  return $response;
}

function ObtenerUsuario () { 
  $session = $_SESSION['SessionStorage'];
  list($tipo, $username, $nombreCompleto, $id, $hoy) = explode('|', base64_decode($session));
  $response = new stdClass();
  $response-> callback = 'ObtenerUsuario';
  $response-> data = $username;
  $response-> ok = false;
  echo json_encode($response);
}

function ObtenerUsuarioPorSesion () { 
  $session = $_SESSION['SessionStorage'];
  list($tipo, $username, $nombreCompleto, $id, $hoy) = explode('|', base64_decode($session));
  $response = new stdClass();
  $response-> callback = 'ObtenerUsuario';
  $response-> data = $username;
  $response-> ok = false;
  return $response;
}

function ObtenerSesion(){
  $response = new stdClass();
  try {
    if (isset($_SESSION['SessionStorage'])) {
      $session = $_SESSION['SessionStorage'];
          $response-> callback = 'EnviarToken';
          $response-> data = $session;
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

function EstadoSesion (){
  $response = new stdClass();
  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    CerrarSesion();
  } else{
    $response-> callback = 'EstadoSesion';
    $response-> ok = true;
    $response-> data = time() - $_SESSION['LAST_ACTIVITY'];
    echo json_encode($response);
  }
}

function CerrarSesion(){
  session_unset();
  session_destroy();
  $response = new stdClass();
  $response-> callback = 'CerrarSesion';
  $response-> data = 'Sesión cerrada exitosamente';
  $response-> ok = true;
  echo json_encode($response);
}

?>