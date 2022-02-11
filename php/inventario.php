<?php
    include('session.php');
    date_default_timezone_set('America/Mexico_City');
  // define ("CaptchaPrivateKey", "6Le9QyUcAAAAAA6W1xX4saMhewADCLHXZKfxhI7C");

  $data = json_decode(file_get_contents('php://input'), true);
  if(isset($data['Action']) && !empty($data['Action'])) {
    $_SESSION['LAST_ACTIVITY'] = time();
    $action = $data['Action'];
    switch($action) {
      #region OBTENER GENERAL
      case 'ObtenerProductos' : ObtenerProductos();
      break;
      case 'ObtenerUsuarios' : ObtenerUsuarios();
      break;
      case 'ObtenerImagenes' : ObtenerImagenes();
      break;
      case 'ObtenerNotificaciones' : ObtenerNotificaciones();
      break;
      #endregion

      #region OBTENER DETALLE
      case 'ObtenerDetalleMensaje' : ObtenerDetalleMensaje();
      break;
      case 'ObtenerDetalleUsuario' : ObtenerDetalleUsuario();
      break;
      case 'ObtenerDetalleImagen' : ObtenerDetalleImagen();
      break;
      case 'ObtenerDetalleNotificacion' : ObtenerDetalleNotificacion();
      break;
      #endregion

      #region ACTUALIZAR
      case 'ActualizarEstadoMensaje' : ActualizarEstadoMensaje();
      break;
      #endregion

      #region ELIMINAR
      case 'EliminarMensaje' : EliminarMensaje();
      break;
      #endregion
    }
  }
  #region OBTENER GENERAL
  function ObtenerProductos (){
    $pdo = OpenCon();
    $sp = "CALL spObtenerProductos()";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($sp);
        $statement->execute();
        while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
          $response-> callback = 'ObtenerProductos';
          $response-> data = $r;
          $response-> ok = true;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }
  function ObtenerUsuarios (){
    $pdo = OpenCon();
    $sp = "CALL spObtenerUsuarios()";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($sp);
        $statement->execute();
        while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
          $response-> callback = 'ObtenerUsuarios';
          $response-> data = $r;
          $response-> ok = true;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }
  function ObtenerImagenes (){
    $pdo = OpenCon();
    $sp = "CALL spObtenerImagenes()";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($sp);
        $statement->execute();
        while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
          $response-> callback = 'ObtenerImagenes';
          $response-> data = $r;
          $response-> ok = true;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }
  function ObtenerNotificaciones (){
    $pdo = OpenCon();
    $sp = "CALL spObtenerNotificaciones()";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($sp);
        $statement->execute();
        while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
          $response-> callback = 'ObtenerNotificaciones';
          $response-> data = $r;
          $response-> ok = true;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }
  #endregion

  #region OBTENER DETALLE
  function ObtenerDetalleMensaje (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['MensajeId'];
    $pdo = OpenCon();
    $select = "CALL spObtenerDetalleMensajePorId('$id')";
    $response = new stdClass();
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
      echo json_encode($response);
  }
  function ObtenerDetalleUsuario (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['UsuarioId'];
    $pdo = OpenCon();
    $select = "CALL spObtenerDetalleUsuarioPorId('$id')";
    $response = new stdClass();
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
      echo json_encode($response);
  }
  function ObtenerDetalleImagen (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['ImagenId'];
    $pdo = OpenCon();
    $select = "CALL spObtenerDetalleImagenPorId('$id')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        if($statement->rowCount() > 0){
            $datos = $statement->fetch();
            $response-> callback = 'ObtenerDetalleImagen';
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
      echo json_encode($response);
  }
  function ObtenerDetalleNotificacion (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['NotificacionId'];
    $pdo = OpenCon();
    $select = "CALL spObtenerDetalleNotificacionPorId('$id')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        if($statement->rowCount() > 0){
            $datos = $statement->fetch();
            $response-> callback = 'ObtenerDetalleNotificacion';
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
      echo json_encode($response);
  }
  #endregion
  
  #region ACTUALIZAR
  function ActualizarEstadoMensaje (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['MensajeId'];
    $pdo = OpenCon();
    $update = "CALL spActualizarEstadoMensaje('$id')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> callback = 'ActualizarEstadoMensaje';
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
            $response-> callback = 'ActualizarEstadoMensaje';
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }
  function ActualizarUsuario (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['UsuarioId'];
    $nombre = $data['Nombre'];
    $email = $data['Email'];
    $fecha = $data['FechaNacimiento'];
    $tipo = $data['Tipo'];
    $estado = $data['Estado'];
    $pdo = OpenCon();
    $update = "CALL spActualizarUsuario('$id','$nombre','$email','$fecha','$tipo','$estado')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> callback = 'ActualizarUsuario';
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
            $response-> callback = 'ActualizarUsuario';
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }
  function ActualizarImagen (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['ImagenId'];
    $pdo = OpenCon();
    $update = "CALL spActualizarImagen('$id')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> callback = 'ActualizarImagen';
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
            $response-> callback = 'ActualizarImagen';
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }
  function ActualizarNotificacion (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['NotificacionId'];
    $pdo = OpenCon();
    $update = "CALL spActualizarNotificacion('$id')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> callback = 'ActualizarNotificacion';
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
            $response-> callback = 'ActualizarNotificacion';
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }
  #endregion
  
  #region ELIMINAR
  function EliminarMensaje (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['MensajeId'];
    $pdo = OpenCon();
    $update = "CALL spEliminarMensaje('$id')";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> callback = 'EliminarMensaje';
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
            $response-> callback = 'EliminarMensaje';
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }
  #endregion




  ?>