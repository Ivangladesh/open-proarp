<?php
    session_start();
  include('dbconn.php');
  date_default_timezone_set('America/Mexico_City');
  define ("CaptchaPrivateKey", "6Le9QyUcAAAAAA6W1xX4saMhewADCLHXZKfxhI7C");


  if(isset($_POST['Action']) && !empty($_POST['Action'])) {
    $action = $_POST['Action'];
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
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $pdo = OpenCon();
    $query = "SELECT Id, Username, Pass ,Activo FROM `sesionstorage` WHERE Username = ? AND Pass = ? ";
    try {
      $statement=$pdo->prepare($query);
      $statement->execute([$username, $password]);
      if($statement->rowCount() > 0){
        $user = $statement->fetch();
        GenerarToken($user);
      } else{
        $jsondata[] = array(
          'status' => 200,
          'data' => null);
          echo json_encode($jsondata);
      }
    } catch (PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }
  }
  function GenerarToken ($r){
    $pdo = OpenCon();
    $id = $r['Id'];
    $username = $r['Username'];
    $hoy = date("m.d.y H:i:s");
    $token = $username.'|'.base64_encode($r['Id'] . $username . $r['Pass'] . $hoy);
    $insert = "UPDATE sesionstorage SET  CookieSession = '$token' WHERE Id = $id";
    try {
        $statement=$pdo->prepare($insert);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            EnviarToken($id);
          } else{
            $jsondata[] = array(
              'status' => 200,
              'data' => null);
              echo json_encode($jsondata);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }

  function EnviarToken ($id){
    $pdo = OpenCon();
    $select = "SELECT CookieSession FROM `sesionstorage` WHERE Id = $id";
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        if($statement->rowCount() > 0){
            $token = $statement->fetch();
            $jsondata[] = array(
              'status' => 200,
              'data' => $token[0]);
              echo json_encode($jsondata);
        } else{
            echo false;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }

  function ValidarSesion (){
    $token = $_POST['Token'];
    $pdo = OpenCon();
    $select = "SELECT NombreCompleto, CookieSession FROM `sesionstorage` WHERE CookieSession = '$token'";
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        if($statement->rowCount() > 0){
            while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
                $jsondata[] = array(
                  'status' => 200,
                  'data' => $r);
              }
            echo json_encode($jsondata);
        } else{
            echo false;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }

  function RegistrarUsuario(){
    $reCaptchaToken = $_POST['ReCaptchaToken'];
    $cu = curl_init();
    curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($cu, CURLOPT_POST, 1);
    curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => CaptchaPrivateKey, 'response' => $reCaptchaToken)));
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($cu);
    curl_close($cu);
    $jsonResponse = json_decode($response,true);
    if($jsonResponse['success'] == 1 && $jsonResponse['score'] >= 0.5){
      $pdo = OpenCon();
      $caracteres = "(#|$|-|_|&|%)";
      $nombre = $_POST['Nombre'];
      $paterno = $_POST['Paterno'];
      $materno = $_POST['Materno'];
      $fechaNacimiento = $_POST['FechaNacimiento'];
      $email = $_POST['Email'];
      $password = $_POST['Password'];

      if(strlen($password) < 8){
        $jsondata = array(
          'status' => 200,
          'data' => "La contraseña debe tener una longitud mínima de 8 caracteres.",
          'ok' => false
        );
          echo json_encode($jsondata);
      } else if(!preg_match($caracteres, $password)){
        $jsondata = array(
          'status' => 200,
          'data' => "La contraseña debe tener por lo menos un caracter especial: #1$,-,_,&,%",
          'ok' => false
        );
          echo json_encode($jsondata);
      } else{
        $procedure = "CALL spRegistrarUsuario('$nombre', '$paterno', '$materno', '$fechaNacimiento', '$email', '$password')";
        try {
            $statement=$pdo->prepare($procedure);
            $statement->execute();
            if($statement->rowCount() > 0){
                $userId = $statement->fetch();
                $jsondata = array(
                  'status' => 200,
                  'data' => $userId[0],
                  'ok' => true);
                  echo json_encode($jsondata);
              } else{
                $jsondata = array(
                  'status' => 200,
                  'data' => null,
                  'ok' => false);
                  echo json_encode($jsondata);
              }
          } catch (PDOException $e) {
              print "¡Error!: " . $e->getMessage() . "<br/>";
              die();
          }
      }
  } else{
    $jsondata = array(
    'status' => 200,
    'data' => null,
    'captcha' => 'Eres un robot'
  );
    echo json_encode($response);
}
}

function CerrarSesion (){
  session_unset();
  session_destroy();
}
