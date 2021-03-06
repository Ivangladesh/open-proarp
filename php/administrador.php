<?php
include('session.php');
date_default_timezone_set('America/Mexico_City');

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['Action']) && !empty($data['Action'])) {
  $_SESSION['LAST_ACTIVITY'] = time();
  $action = $data['Action'];
  switch ($action) {
      #region OBTENER GENERAL
    case 'ObtenerMensajes':
      ObtenerMensajes();
      break;
    case 'ObtenerUsuarios':
      ObtenerUsuarios();
      break;
    case 'ObtenerImagenes':
      ObtenerImagenes();
      break;
    case 'ObtenerNotificaciones':
      ObtenerNotificaciones();
      break;
    case 'ObtenerTipoUsuario':
      ObtenerTipoUsuario();
      break;
    case 'ObtenerEstadoUsuario':
      ObtenerEstadoUsuario();
      break;
      #endregion

      #region OBTENER DETALLE
    case 'ObtenerDetalleMensaje':
      ObtenerDetalleMensaje();
      break;
    case 'ObtenerDetalleUsuario':
      ObtenerDetalleUsuario();
      break;
    case 'ObtenerDetalleImagen':
      ObtenerDetalleImagen();
      break;
    case 'ObtenerDetalleNotificacion':
      ObtenerDetalleNotificacion();
      break;
      #endregion

      #region ACTUALIZAR
    case 'ActualizarEstadoMensaje':
      ActualizarEstadoMensaje();
      break;
    case 'ActualizarUsuario':
      ActualizarUsuario();
      break;
      #endregion

      #region ELIMINAR
    case 'EliminarMensaje':
      EliminarMensaje();
      break;

    case 'EliminarUsuario':
      EliminarUsuario();
      break;
      #endregion
  }
}
#region OBTENER GENERAL
function ObtenerMensajes()
{
  $pdo = OpenCon();
  $sp = "CALL spObtenerMensajesContacto()";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($sp);
    $statement->execute();
    while ($r = $statement->fetchAll(PDO::FETCH_ASSOC)) {
      $response->callback = 'ObtenerMensajes';
      $response->data = $r;
      $response->ok = true;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}
function ObtenerUsuarios()
{
  $pdo = OpenCon();
  $sp = "CALL spObtenerUsuarios()";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($sp);
    $statement->execute();
    while ($r = $statement->fetchAll(PDO::FETCH_ASSOC)) {
      $response->callback = 'ObtenerUsuarios';
      $response->data = $r;
      $response->ok = true;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}
function ObtenerImagenes()
{
  $pdo = OpenCon();
  $sp = "CALL spObtenerImagenes()";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($sp);
    $statement->execute();
    while ($r = $statement->fetchAll(PDO::FETCH_ASSOC)) {
      $response->callback = 'ObtenerImagenes';
      $response->data = $r;
      $response->ok = true;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}
function ObtenerNotificaciones()
{
  $pdo = OpenCon();
  $sp = "CALL spObtenerNotificaciones()";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($sp);
    $statement->execute();
    while ($r = $statement->fetchAll(PDO::FETCH_ASSOC)) {
      $response->callback = 'ObtenerNotificaciones';
      $response->data = $r;
      $response->ok = true;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}
#endregion

#region OBTENER DETALLE
function ObtenerDetalleMensaje()
{
  $response = new stdClass();
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['MensajeId'];
  if (!empty($id)) {
    $pdo = OpenCon();
    $select = "CALL spObtenerDetalleMensajePorId('$id')";
    try {
      $statement = $pdo->prepare($select);
      $statement->execute();
      if ($statement->rowCount() > 0) {
        $datos = $statement->fetch();
        $response->callback = 'ObtenerDetalleMensaje';
        $response->data = $datos;
        $response->ok = true;
      } else {
        $response->callback = 'ObtenerDetalleMensaje';
        $response->data = null;
        $response->ok = false;
      }
    } catch (PDOException $e) {
      print "¡Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  } else {
    $response->callback = 'ObtenerDetalleMensaje';
    $response->data = null;
    $response->ok = false;
  }

  echo json_encode($response);
}
function ObtenerDetalleUsuario()
{
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['UsuarioId'];
  $pdo = OpenCon();
  $select = "CALL spObtenerDetalleUsuarioPorId('$id')";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($select);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      $datos = $statement->fetch();
      $response->callback = 'ObtenerDetalleUsuario';
      $response->data = $datos;
      $response->ok = true;
    } else {
      $response->callback = 'ObtenerDetalleUsuario';
      $response->data = null;
      $response->ok = false;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}
function ObtenerDetalleImagen()
{
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['ImagenId'];
  $pdo = OpenCon();
  $select = "CALL spObtenerDetalleImagenPorId('$id')";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($select);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      $datos = $statement->fetch();
      $response->callback = 'ObtenerDetalleImagen';
      $response->data = $datos;
      $response->ok = true;
    } else {
      $response->callback = 'ObtenerDetalleMensaje';
      $response->data = null;
      $response->ok = false;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}
function ObtenerDetalleNotificacion()
{
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['NotificacionId'];
  $pdo = OpenCon();
  $select = "CALL spObtenerDetalleNotificacionPorId('$id')";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($select);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      $datos = $statement->fetch();
      $response->callback = 'ObtenerDetalleNotificacion';
      $response->data = $datos;
      $response->ok = true;
    } else {
      $response->callback = 'ObtenerDetalleMensaje';
      $response->data = null;
      $response->ok = false;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}
#endregion

#region ACTUALIZAR
function ActualizarEstadoMensaje()
{
  $response = new stdClass();
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['MensajeId'];
  if (!empty($id)) {
    $pdo = OpenCon();
    $update = "CALL spActualizarEstadoMensaje('$id')";
    try {
      $statement = $pdo->prepare($update);
      $statement->execute();
      if ($statement->rowCount() > 0) {
        $count = $statement->rowCount();
        $response->callback = 'ActualizarEstadoMensaje';
        $response->data = $count;
        $response->ok = true;
      } else {
        $response->callback = 'ActualizarEstadoMensaje';
        $response->data = null;
        $response->ok = false;
      }
    } catch (PDOException $e) {
      print "¡Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  } else {
    $response->callback = 'ActualizarEstadoMensaje';
    $response->data = null;
    $response->ok = false;
  }

  echo json_encode($response);
}
function ActualizarUsuario (){
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['UsuarioId'];
  $nombres = $data['Nombre'];
  $paterno = $data['Paterno'];
  $materno = $data['Materno'];
  $email = $data['Email'];
  $fecha = $data['FechaNacimiento'];
  $tipo = $data['Tipo'];
  $estado = $data['Estado'];
  $pdo = OpenCon();
  $update = "CALL spActualizarUsuario('$id','$nombres','$paterno','$materno','$email','$fecha','$tipo','$estado')";
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

function ActualizarImagen()
{
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['ImagenId'];
  $pdo = OpenCon();
  $update = "CALL spActualizarImagen('$id')";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($update);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      $count = $statement->rowCount();
      $response->callback = 'ActualizarImagen';
      $response->data = $count;
      $response->ok = true;
      echo json_encode($response);
    } else {
      $response->callback = 'ActualizarImagen';
      $response->data = null;
      $response->ok = false;
      echo json_encode($response);
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}
function ActualizarNotificacion()
{
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['NotificacionId'];
  $pdo = OpenCon();
  $update = "CALL spActualizarNotificacion('$id')";
  $response = new stdClass();
  try {
    $statement = $pdo->prepare($update);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      $count = $statement->rowCount();
      $response->callback = 'ActualizarNotificacion';
      $response->data = $count;
      $response->ok = true;
      echo json_encode($response);
    } else {
      $response->callback = 'ActualizarNotificacion';
      $response->data = null;
      $response->ok = false;
      echo json_encode($response);
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}
#endregion

#region ELIMINAR
function EliminarMensaje()
{
  $response = new stdClass();
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['MensajeId'];
  $response->callback = 'EliminarMensaje';
  if (!empty($id)) {
    $pdo = OpenCon();
    $update = "CALL spEliminarMensaje('$id')";
    try {
      $statement = $pdo->prepare($update);
      $statement->execute();
      if ($statement->rowCount() > 0) {
        $count = $statement->rowCount();
        $response->data = $count;
        $response->ok = true;
      } else {
        $response->data = null;
        $response->ok = false;
      }
    } catch (PDOException $e) {
      print "¡Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  } else {
    $response->data = null;
    $response->ok = false;
  }

  echo json_encode($response);
}

function EliminarUsuario()
{
  $response = new stdClass();
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['UsuarioId'];
  $response->callback = 'EliminarUsuario';
  if (!empty($id)) {
    $pdo = OpenCon();
    $update = "CALL spEliminarUsuario('$id')";
    try {
      $statement = $pdo->prepare($update);
      $statement->execute();
      if ($statement->rowCount() > 0) {
        $count = $statement->rowCount();
        $response->data = $count;
        $response->ok = true;
      } else {
        $response->data = null;
        $response->ok = false;
      }
    } catch (PDOException $e) {
      print "¡Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  } else {
    $response->data = null;
    $response->ok = false;
  }

  echo json_encode($response);
}

function ObtenerTipoUsuario (){
  $pdo = OpenCon();
  $sp = "CALL spObtenerTipoUsuario()";
  $response = new stdClass();
  try {
      $statement=$pdo->prepare($sp);
      $statement->execute();
      while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
        $response-> callback = 'ObtenerTipoUsuario';
        $response-> data = $r;
        $response-> ok = true;
      }
    } catch (PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    echo json_encode($response);
}

function ObtenerEstadoUsuario (){
  $pdo = OpenCon();
  $sp = "CALL spObtenerEstadoUsuario()";
  $response = new stdClass();
  try {
      $statement=$pdo->prepare($sp);
      $statement->execute();
      while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
        $response-> callback = 'ObtenerEstadoUsuario';
        $response-> data = $r;
        $response-> ok = true;
      }
    } catch (PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    echo json_encode($response);
}

?>