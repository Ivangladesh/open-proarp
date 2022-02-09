<?php
session_start();
    include('dbconn.php');
    date_default_timezone_set('America/Mexico_City');

  $data = json_decode(file_get_contents('php://input'), true);

  if(isset($data['Action']) && !empty($data['Action'])) {
    $_SESSION['LAST_ACTIVITY'] = time();
    $action = $data['Action'];
    switch($action) {
      case 'InsertarImagen' : InsertarImagen();
      break;
      case 'ObtenerImagenes' : ObtenerImagenes();
      break;
  }
  }


  function ObtenerImagenes(){
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

  function InsertarImagen(){
    $data = json_decode(file_get_contents('php://input'), true);
    $inventarioId = $data['InventarioId'];
    $descripcion = $data['Descripcion'];
    $prefix = "../uploads/";
    $prefixThumb = "../uploads/thumbnails/thumb_";
    $ruta = $prefix.$data['Archivo'];
    $rutaThumb = $prefixThumb.$data['Archivo'];
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
