<?php
    session_start();
    include('dbconn.php');
    include('recaptcha.php');
    date_default_timezone_set('America/Mexico_City');

  $data = json_decode(file_get_contents('php://input'), true);
  if(isset($data['Action']) && !empty($data['Action'])) {
    $action = $data['Action'];

    switch($action) {
        case 'IniciarSesion' : IniciarSesion();
        break;
        case 'RegistrarUsuario' : RegistrarUsuario();
        break;
        case 'ValidarSesion' : ValidarSesion();
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
    $reCaptchaToken = $data['ReCaptchaToken'];
    $response-> callback = 'IniciarSesion';
    if(Recaptcha($reCaptchaToken)){
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
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
      } else{
        $response-> data = null;
        $response-> ok = false;
        echo json_encode($response);
      }
    } else{
      $response-> data = "Validación recaptcha fallida";
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
    $response-> callback = 'GenerarToken';
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
              $response-> data = null;
              $response-> ok = false;
              echo json_encode($response);
            }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    } else{
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
      $response-> callback = 'EnviarToken';
      try {
          $statement=$pdo->prepare($select);
          $statement->execute();
          if($statement->rowCount() > 0){
              $token = $statement->fetch();
              $_SESSION['SessionStorage'] = strval($token[0]);
              $response-> data = $token[0];
              $response-> ok = true;
              $_SESSION['LAST_ACTIVITY'] = time();
          } else{
              $response-> data = null;
              $response-> ok = false;
          }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    } else{
      $response-> data = null;
      $response-> ok = false;
    }
    echo json_encode($response);
  }

  function ValidarSesion (){
    $response = new stdClass();
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['Token'];
    $response-> callback = 'ValidarSesion';
    if (!empty($token)) {
      $pdo = OpenCon();
      $select = "CALL spValidarSesion('$token')";
      try {
          $statement=$pdo->prepare($select);
          $statement->execute();
          if($statement->rowCount() > 0){
              while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
                $response-> data = $r;
                $response-> ok = true;
                }
              
          } else{
            $response-> data = null;
            $response-> ok = false;
          }
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    } else{
      $response-> data = null;
      $response-> ok = false;
    }
    echo json_encode($response);
  }

    function RegistrarUsuario(){

        $data = json_decode(file_get_contents('php://input'), true);
        $pdo = OpenCon();
        $caracteres = "/[#$^+=!*()@%&]/";
        $nombre = $data['Nombre'];
        $paterno = $data['Paterno'];
        $materno = $data['Materno'];
        $fechaNacimiento = $data['FechaNacimiento'];
        $email = $data['Email'];
        $password = $data['Password'];
        $response = new stdClass();
        $response-> callback = 'RegistrarUsuario';
        $stringPassword = base64_decode($password);
        if(strlen($stringPassword) < 8){
          $response-> callback = 'RegistrarUsuario';
          $response-> data = "La contraseña debe tener una longitud mínima de 8 caracteres.";
          $response-> ok = false;
          echo json_encode($response);
        } else if(!preg_match($caracteres, $stringPassword)){
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
                      $response-> data = $user[0];
                      $response-> ok = true;
                    } else{
                      $response-> data = "Ha ocurrido un error, consulte al administrador.";
                      $response-> ok = false;
                    }
                } catch (PDOException $e) {
                    print "¡Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
            } else{
              $response-> data = "Alguno de los valores está vacío o incompleto";
              $response-> ok = false;
            }
          } else{
            $response-> data = "El email ya ha sido registrado.";
            $response-> ok = false;
          }

        }
          echo json_encode($response);
  }

  function ExisteEmail ($email){
    $response = "";
    $pdo = OpenCon();
    $select = "CALL spValidarEmail('$email')";
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        $response = $statement->fetch();
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
    return $response[0];
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
    $response-> callback = 'EnviarToken';
    try {
      if (isset($_SESSION['SessionStorage'])) {
        $session = $_SESSION['SessionStorage'];
            $response-> data = $session;
            $response-> ok = true;
        } else{
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
