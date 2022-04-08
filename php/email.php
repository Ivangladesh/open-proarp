<?php require 'PHPMailer/PHPMailerAutoload.php';?>
<?php
    session_start();
    include('dbconn.php');
    include('recaptcha.php');
    include('email-template.php');
    date_default_timezone_set('America/Mexico_City');

    $resetPath = '/proarp/views/reset.php';
    $protocol = 'https://';

    if($_SERVER['SERVER_NAME'] == 'localhost'){
        $resetPath = '/views/reset.php';
        $protocol = 'http://';
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['Action']) && !empty($data['Action'])) {
    
    $action = $data['Action'];
        switch($action) {
            case 'EnviarCorreo' : _DummyCorreo();//EnviarCorreo();
            break;
            case 'ActualizarContrasena' : ActualizarContrasena();//EnviarCorreo();
            break;
        }
    }
    if(isset($_GET['token'])){
        $_SESSION['TempSession'] = $_GET['token'];
        $response = new stdClass();
        list($tipo, $username, $nombreCompleto, $id, $hoy) = explode('|', base64_decode($_GET['token']));
        $today = strtotime($hoy); 
        $secondDate = date("Y-m-d H:i:s");
        $from_time = strtotime($secondDate);
        if(round(round(abs($today - $from_time) / 60,2)) <= 1440){
            if(ValidarCodigoTemporal($username, $_GET['token'])){
                header('Location: '. $protocol.$_SERVER['SERVER_NAME'].$resetPath);
            } else{
                echo "Datos inválidos";
            }
            
        } else{
            echo "El código ha expirado";
            session_unset();
            session_destroy();
        }
        echo round(round(abs($today - $from_time) / 60,2)). " minute";

    }

    function _DummyCorreo (){
        $response = new stdClass();
        $response-> callback = 'EnviarCorreo';
        BloquearUsuario();
        $stringToken = ObtenerDatosUsuario();
        if($stringToken == null){
            $response-> data = "Token vacío";
            $response-> ok = false;
        } else{
            $link = "https://".$_SERVER['SERVER_NAME']."/php/email.php/?token=".$stringToken;
            $response-> data = ResetPassword($link);
            $response-> ok = true;
        }
        echo ResetPassword($link);
    }

    function ValidarCodigoTemporal ($email, $token){
        $response = false;
        $data = json_decode(file_get_contents('php://input'), true);
        $pdo = OpenCon();
        $sp = "CALL spValidarCuenta('$email','$token')";
        try {
            $statement=$pdo->prepare($sp);
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

    function GuardarCodigoTemporal ($email, $token, $date){
        $response = false;
        $data = json_decode(file_get_contents('php://input'), true);
        $pdo = OpenCon();
        $sp = "CALL spRegistrarCodigoTemporal('$email','$token','$date')";
        try {
            $statement=$pdo->prepare($sp);
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

    function _EnviarCorreo()
    {
        $response = new stdClass();
        $response->callback = 'EnviarCorreo';
        $data = json_decode(file_get_contents('php://input'), true);
        $reCaptchaToken = $data['ReCaptchaToken'];
        if(Recaptcha($reCaptchaToken)){
            try{
                $tomail = $data['Email'];
                if($tomail == 'ivan@gmail.com'){
                    $tomail="ivangladesh@gmail.com";
                }
                $subject = "Reestablecer contraseña";
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'To: '.$tomail."\r\n";
                $headers .= 'From: proarp@'.$_SERVER['SERVER_NAME'];
                $stringToken = ObtenerDatosUsuario();
                if($stringToken == null){
                    $response-> data = "Token vacío";
                    $response-> ok = false;
                }
                $link = 'https://'.$_SERVER['SERVER_NAME']."/php/email.php/?token=".$stringToken;
                $message =  ResetPassword($link);
                if(mail($tomail,$subject,$message,$headers))
                {
                    $response-> data = "Correo enviado";
                    $response-> ok = true;
                }
                else{
                    $response-> data = "Ha ocurrido un error";
                    $response-> ok = false;
                }
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
            }
        }
        else{
            $response-> data = "Validación recaptcha fallida";
            $response-> ok = false;
        }
        echo json_encode($response);
    }

    function ActualizarContrasena(){
        $data = json_decode(file_get_contents('php://input'), true);
        $reCaptchaToken = $data['ReCaptchaToken'];
        if(Recaptcha($reCaptchaToken)){
            $pdo = OpenCon();
            $email = $data['Email'];
            $password = $data['Contrasena'];
            $caracteres = "/[#$^+=!*()@%&]/";
            $stringPassword = base64_decode($password);
            $response = new stdClass();
            $response-> callback = 'ActualizarContrasena';
            if(strlen($stringPassword) < 8){
                $response-> data = "La contraseña debe tener una longitud mínima de 8 caracteres.";
                $response-> ok = false;
            } else if(!preg_match($caracteres, $stringPassword)){
                $response-> data = "La contraseña debe tener por lo menos un caracter especial: # $ ^ + = ! * ( ) @ % &.";
                $response-> ok = false;
            } else{
                $procedure = "CALL spActualizarContrasena('$email', '$password')";
                try {
                    $statement=$pdo->prepare($procedure);
                    $statement->execute();
                    if($statement->rowCount() > 0){
                        $count = $statement->rowCount();
                        $response->data = $count;
                        $response->ok = true;
                        } else{
                        $response-> data = "Ha ocurrido un error, consulte al administrador.";
                        $response-> ok = false;
                        }
                    } catch (PDOException $e) {
                        print "¡Error!: " . $e->getMessage() . "<br/>";
                        die();
                    }
            }
        }else{
            $response-> data = "Validación recaptcha fallida";
            $response-> ok = false;
        }
        session_unset();
        session_destroy();
        echo json_encode($response);
    }

    function BloquearUsuario()
    {

    }

    function ObtenerDatosUsuario() {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['Email'];
        $response = "";
          if (!empty($email)) {
            $pdo = OpenCon();
            $procedure = "CALL spObtenerUsuarioPorEmail('$email')";
            try {
              $statement=$pdo->prepare($procedure);
              $statement->execute();
              if($statement->rowCount() > 0){
                $user = $statement->fetch();
                $response = GenerarTokenEmail($user);
                if($response == false){
                    return null;
                }
              }
            } catch (PDOException $e) {
                print "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
          }
          return $response;

      }

    function GenerarTokenEmail ($r){
        $id = $r['UsuarioId'];
        $username = $r['Email'];
        $nombreCompleto = $r['NombreCompleto'];
        $tipo = $r['TipoUsuarioId'];
        $response = null;
        if (!empty($id) && !empty($username) && !empty($nombreCompleto) && !empty($tipo)) {
            $pdo = OpenCon();
            $hoy = date("Y-m-d H:i:s");
            $token = base64_encode($tipo . '|' . $username . '|' . $nombreCompleto . '|'  . $id . '|' . $hoy);
            $ok = GuardarCodigoTemporal($username,$token,$hoy);
            if(!$ok){
                return false;
            } else{
                return $token;
            }
        }        
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