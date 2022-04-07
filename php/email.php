<?php require 'PHPMailer/PHPMailerAutoload.php';?>
<?php

    include('dbconn.php');
    date_default_timezone_set('America/Mexico_City');
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['Action']) && !empty($data['Action'])) {
    $_SESSION['LAST_ACTIVITY'] = time();
    $action = $data['Action'];
        switch($action) {
            case 'EnviarCorreo' : _DummyCorreo();//EnviarCorreo();
            break;
        }
    }
    if(isset($_GET['token'])){

        list($tipo, $username, $nombreCompleto, $id, $hoy) = explode('|', base64_decode($_GET['token']));
        $today = strtotime($hoy); 
        $secondDate = date("Y-m-d H:i:s");
        $from_time = strtotime($secondDate);

        echo round(round(abs($today - $from_time) / 60,2)). " minute";

    }

    function _DummyCorreo (){
        BloquearUsuario();
        $stringToken = ObtenerDatosUsuario();
        $link = "https://ivangladesh.000webhostapp.com/php/email.php/?token=" . $stringToken;
        echo $link;
    }

    function GuardarCodigoTemporal (){

    }

    function _EnviarCorreo()
    {
        $tomail="ivangladesh@gmail.com";
        $subject="Password Reset";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'To: '.$tomail."\r\n";
        $link="https://ivangladesh.000webhostapp.com/views/reset_password.php";
        $htmlContent = file_get_contents("email_template.html");
        $message="<html><head></head><body><p>Click on the link below to reset your password</p><br><br><a href='".$link."'>Click here to reset your password</a></body></html>";
        if(mail($tomail,$subject,$message,$headers))
        {
            $myobj=array('status'=>'success','message'=>'Check your mail to reset password!!');
            echo json_encode($myobj);
        }
        else{
            $myobj=array('status'=>'error','message'=>'Invalid e-mail!!');
            echo json_encode($myobj);
        }
    }

    function ActualizarContrasena(){
        $data = json_decode(file_get_contents('php://input'), true);
        $pdo = OpenCon();
        $email = $data['Email'];
        $password = $data['Password'];
        $caracteres = "/[#$^+=!*()@%&]/";
        $stringPassword = base64_decode($password);
        if(strlen($stringPassword) < 8){
            $response-> callback = 'ActualizarContrasena';
            $response-> data = "La contraseña debe tener una longitud mínima de 8 caracteres.";
            $response-> ok = false;
            echo json_encode($response);
        } else if(!preg_match($caracteres, $stringPassword)){
            $response-> callback = 'ActualizarContrasena';
            $response-> data = "La contraseña debe tener por lo menos un caracter especial: # $ ^ + = ! * ( ) @ % &.";
            $response-> ok = false;
            echo json_encode($response);
        } else{
            $procedure = "CALL spActualizarContrasena('$email', '$password')";
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
        }
        echo json_encode($response);
    }

    function BloquearUsuario()
    {

    }

    function ObtenerDatosUsuario () {
        $data = json_decode(file_get_contents('php://input'), true);
        //$email = $data['Email'];
        $email = "ivan@gmail.com";
        $response = "";
          if (!empty($email)) {
            $pdo = OpenCon();
            $procedure = "CALL spObtenerUsuarioPorEmail('$email')";
            try {
              $statement=$pdo->prepare($procedure);
              $statement->execute();
              if($statement->rowCount() > 0){
                $user = $statement->fetch();
                $response = GenerarToken($user);
              }
            } catch (PDOException $e) {
                print "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
          }
          return $response;

      }

    function GenerarToken ($r){
        $id = $r['UsuarioId'];
        $username = $r['Email'];
        $nombreCompleto = $r['NombreCompleto'];
        $tipo = $r['TipoUsuarioId'];
        $response = null;
        if (!empty($id) && !empty($username) && !empty($nombreCompleto) && !empty($tipo)) {
          $pdo = OpenCon();
          $hoy = date("Y-m-d H:i:s");
          return $token = base64_encode($tipo . '|' . $username . '|' . $nombreCompleto . '|'  . $id . '|' . $hoy);
          $insert = "CALL spActualizarToken('$token', '$id')";
          try {
              $statement=$pdo->prepare($insert);
              $statement->execute();
              if($statement->rowCount() > 0){
                  $count = $statement->rowCount();
                  $response = $token;
                }
            } catch (PDOException $e) {
                print "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        return $response;
        
      }


function EnviarCorreo()
{

    $response = new stdClass();
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data["Nombre"];
    $email = $data["Email"];
    $phone = $data["Telefono"];
    $message = $data["Mensaje"];

    // Email Functionality

    date_default_timezone_set('Etc/UTC');

    $mail = new PHPMailer();

    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';

    $mail->Host       = "smtp.gmail.com";     // SMTP server example
    $mail->SMTPDebug  = 1;                      // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                   // enable SMTP authentication
    $mail->Port       = 465; // 465 or 587      // set the SMTP port for the GMAIL server
    $mail->Username   = "servicios.carlos.ivan@gmail.com"; 	        // SMTP account username example
    $mail->Password   = "Servicios2022.";             // SMTP account password example


    $mail->setFrom('servicios.carlos.ivan@gmail.com');
    $mail->addAddress('ivangladesh@gmail.com');

    // The subject of the message.
    $mail->Subject = 'Received Message From Client ' . $name;

    $message = '<html><body>';
    $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
    $message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . strip_tags($name) . "</td></tr>";
    $message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($email) . "</td></tr>";
    $message .= "<tr><td><strong>Phone:</strong> </td><td>" . strip_tags($phone) . "</td></tr>";
    $message .= "<tr><td><strong>Message</strong> </td><td>" . strip_tags($message) . "</td></tr>";
    $message .= "</table>";
    $message .= "</body></html>";

    $mail->Body = $message;

    $mail->isHTML(true);

    if ($mail->send()) {
        $response->callback = 'EnviarCorreo';
        $response->data = 'Email enviado';
        $response->ok = true;
      } else {
        $response->callback = 'EnviarCorreo';
        $response->data = null;
        $response->ok = false;
      }

}

?>