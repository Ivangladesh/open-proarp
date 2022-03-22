<?php
session_start();
include('dbconn.php');
$config = include('config.php');
date_default_timezone_set('America/Mexico_City');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['Action']) && !empty($data['Action'])) {
  $_SESSION['LAST_ACTIVITY'] = time();
  $action = $data['Action'];
  switch ($action) {
    case 'InsertarImagen':
      InsertarImagen($config);
      break;
    case 'ObtenerImagenes':
      ObtenerImagenes();
      break;
    case 'ObtenerDetalleImagen':
      ObtenerDetalleImagen();
      break;
    case 'EliminarImagen':
      EliminarImagen();
      break;
    case 'ActualizarImagen':
      ActualizarImagen();
      break;
    case 'ObtenerImagenesPorInventarioId':
      ObtenerImagenesPorInventarioId();
      break;
  }
}

function ObtenerDetalleImagen()
{
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['ImagenId'];
  $pdo = OpenCon();
  $select = "CALL spObtenerDetalleImagenPorId('$id')";
  $response = new stdClass();
  $response->callback = 'ObtenerDetalleImagen';
  try {
    $statement = $pdo->prepare($select);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      $datos = $statement->fetch(PDO::FETCH_OBJ);
      $response->data = $datos;
      $response->ok = true;
    } else {
      $response->data = null;
      $response->ok = false;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}

function DetalleImagen($id)
{
  $pdo = OpenCon();
  $select = "CALL spObtenerDetalleImagenPorId('$id')";
  try {
    $statement = $pdo->prepare($select);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      return  $statement->fetch(PDO::FETCH_OBJ);
    } else {
      return null;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}

function ObtenerImagenes()
{
  $pdo = OpenCon();
  $select = "CALL spObtenerListaImagenes()";
  $response = new stdClass();
  $response->callback = 'ObtenerImagenes';
  try {
    $statement = $pdo->prepare($select);
    $statement->execute();
    while ($r = $statement->fetchAll(PDO::FETCH_ASSOC)) {
      $response->data = $r;
      $response->ok = true;
    }
  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}

function ObtenerImagenesPorInventarioId()
{
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['InventarioId'];
  $pdo = OpenCon();
  $select = "CALL spObtenerImagenPorInventarioId('$id')";
  $response = new stdClass();
  $response->callback = 'ObtenerImagenesPorInventarioId';
  try {
    $statement = $pdo->prepare($select);
    $statement->execute();
    if($statement->rowCount() > 0){
      while ($r = $statement->fetchAll(PDO::FETCH_ASSOC)) {
        $response->data = $r;
        $response->ok = true;
      }
    } else{
      $response->data = "Este producto no cuenta con imágenes disponibles.";
      $response->ok = false;
    };

  } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  echo json_encode($response);
}

function EliminarImagen()
{
  $response = new stdClass();
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['ImagenId'];
  $response->callback = 'EliminarImagen';
  $datos = DetalleImagen($id);

  if (!empty($id)) {
    $pdo = OpenCon();
    $update = "CALL spEliminarImagen('$id')";
    try {
      $statement = $pdo->prepare($update);
      $statement->execute();
      $res = $statement->rowCount();
      if ($res > 0) {
        $response->data = $datos->RutaFisica;
        $response->ok = true;
        unlink($datos->RutaFisica);
        unlink($datos->RutaThumb);
      } else {
        $response->data = null;
        $response->ok = false;
      }
    } catch (PDOException $e) {
      echo "¡Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  } else {
    $response->data = null;
    $response->ok = false;
  }

  echo json_encode($response);
}

function InsertarImagen($config)
{
  $data = json_decode(file_get_contents('php://input'), true);
  $inventarioId = $data['InventarioId'];
  $descripcion = $data['Descripcion'];
  $prefix = $config->uploads;
  $prefixThumb = $config->thumbs;
  $ruta = $prefix . $data['Archivo'];
  $rutaThumb = $prefixThumb . $data['Archivo'];
  $pdo = OpenCon();
  $insert = "CALL spInsertarImagen('$inventarioId', '$descripcion', '$ruta', '$rutaThumb')";
  $response = new stdClass();
  $response->callback = 'InsertarImagen';
  try {
    $statement = $pdo->prepare($insert);
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
  echo json_encode($response);
};

function ActualizarImagen (){
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['ImagenId'];
  $inventarioId = $data['InventarioId'];
  $descripcion = $data['Descripcion'];
  $pdo = OpenCon();
  $update = "CALL spActualizarImagen('$id','$inventarioId','$descripcion')";
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
};
