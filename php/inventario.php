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
      case 'ObtenerProveedores' : ObtenerProveedores();
      break;
      case 'ObtenerEstadoInventario' : ObtenerEstadoInventario();
      break;
      #endregion

      #region OBTENER DETALLE
      case 'ObtenerDetalleProducto' : ObtenerDetalleProducto();
      break;
      case 'ObtenerDetalleProveedor' : ObtenerDetalleProveedor();
      break;
      #endregion

      #region ACTUALIZAR
      case 'ActualizarProducto' : ActualizarProducto();
      break;
      case 'ActualizarProveedor' : ActualizarProveedor();
      break;
      #endregion

      #region ELIMINAR XAXX010101000
      case 'EliminarMensaje' : EliminarMensaje();
      break;
      case 'EliminarProveedor' : EliminarProveedor();
      break;
      #endregion

      #region INSERTAR
      case 'InsertarProducto' : InsertarProducto();
      break;
      case 'InsertarProveedor' : InsertarProveedor();
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
  function ObtenerProveedores (){
    $pdo = OpenCon();
    $sp = "CALL spObtenerProveedores()";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($sp);
        $statement->execute();
        while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
          $response-> callback = 'ObtenerProveedores';
          $response-> data = $r;
          $response-> ok = true;
        }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
      echo json_encode($response);
  }
  function ObtenerEstadoInventario (){
    $pdo = OpenCon();
    $sp = "CALL spObtenerEstadoInventario()";
    $response = new stdClass();
    try {
        $statement=$pdo->prepare($sp);
        $statement->execute();
        while($r = $statement->fetchAll(PDO::FETCH_ASSOC)){
          $response-> callback = 'ObtenerEstadoInventario';
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
  function ObtenerDetalleProducto (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['ProductoId'];
    $pdo = OpenCon();
    $select = "CALL spObtenerDetalleProductoPorId('$id')";
    $response = new stdClass();
    $response-> callback = 'ObtenerDetalleProducto';
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        if($statement->rowCount() > 0){
            $datos = $statement->fetch();
            $response-> data = $datos;
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
  function ObtenerDetalleProveedor (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['ProveedorId'];
    $pdo = OpenCon();
    $select = "CALL spObtenerDetalleProveedorPorId('$id')";
    $response = new stdClass();
    $response-> callback = 'ObtenerDetalleProveedor';
    try {
        $statement=$pdo->prepare($select);
        $statement->execute();
        if($statement->rowCount() > 0){
            $datos = $statement->fetch();
            $response-> data = $datos;
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
  #endregion
  
  #region ACTUALIZAR
  function ActualizarProducto (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['InventarioId'];
    $nombre = $data['Nombre'];
    $descripcion = $data['Descripcion'];
    $estado = $data['Estado'];
    $precioVenta = $data['PrecioVenta'];
    $fechaCompra = $data['FechaCompra'];
    $existencias = $data['Existencias'];
    $pdo = OpenCon();
    $update = "CALL spActualizarProducto('$id','$nombre','$descripcion','$estado','$precioVenta','$fechaCompra','$existencias')";
    $response = new stdClass();
    $response-> callback = 'ActualizarProducto';
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }
  function ActualizarProveedor (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['ProveedorId'];
    $razonSocial = $data['RazonSocial'];
    $marcaComercial = $data['MarcaComercial'];
    $RFC = $data['RFC'];
    $direccion = $data['Direccion'];
    $telefono = $data['Telefono'];
    $telefonoAlternativo = $data['TelefonoAlternativo'];
    $nombreContacto = $data['NombreContacto'];
    $descripcion = $data['Descripcion'];
    $notas = $data['Notas'];
    $pdo = OpenCon();
    $update = "CALL spActualizarProveedor('$id',
    '$razonSocial',
    '$marcaComercial',
    '$RFC',
    '$direccion',
    '$telefono',
    '$telefonoAlternativo',
    '$nombreContacto',
    '$descripcion',
    '$notas'
    )";
    $response = new stdClass();
    $response-> callback = 'ActualizarProveedor';
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
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
  
  
  #region INSERTAR
  function InsertarProducto (){
    $data = json_decode(file_get_contents('php://input'), true);
    $nombre = $data['Nombre'];
    $descripcion = $data['Descripcion'];
    $estado = $data['Estado'];
    $precioVenta = $data['PrecioVenta'];
    $fechaCompra = $data['FechaCompra'];
    $existencias = $data['Existencias'];
    $pdo = OpenCon();
    $update = "CALL spInsertarProducto('$nombre','$descripcion','$estado','$precioVenta','$fechaCompra','$existencias')";
    $response = new stdClass();
    $response-> callback = 'InsertarProducto';
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
            $response-> data = null;
            $response-> ok = false;
            echo json_encode($response);
          }
      } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
      }
  }

  function InsertarProveedor (){
    $data = json_decode(file_get_contents('php://input'), true);
    $razonSocial = $data['RazonSocial'];
    $marcaComercial = $data['MarcaComercial'];
    $RFC = $data['RFC'];
    $direccion = $data['Direccion'];
    $telefono = $data['Telefono'];
    $telefonoAlternativo = $data['TelefonoAlternativo'];
    $nombreContacto = $data['NombreContacto'];
    $descripcion = $data['Descripcion'];
    $notas = $data['Notas'];
    $pdo = OpenCon();
    $update = "CALL spInsertarProveedor(
    '$razonSocial',
    '$marcaComercial',
    '$RFC',
    '$direccion',
    '$telefono',
    '$telefonoAlternativo',
    '$nombreContacto',
    '$descripcion',
    '$notas',
    )";
    $response = new stdClass();
    $response-> callback = 'InsertarProveedor';
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> data = $count;
            $response-> ok = true;
            echo json_encode($response);
          } else{
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
  function EliminarProducto (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['ProductoId'];
    $pdo = OpenCon();
    $update = "CALL spEliminarProducto('$id')";
    $response = new stdClass();
    $response-> callback = 'EliminarProducto';
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> data = $count;
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

  function EliminarProveedor (){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['ProveedorId'];
    $pdo = OpenCon();
    $update = "CALL spEliminarProveedor('$id')";
    $response = new stdClass();
    $response-> callback = 'EliminarProveedor';
    try {
        $statement=$pdo->prepare($update);
        $statement->execute();
        if($statement->rowCount() > 0){
            $count = $statement->rowCount();
            $response-> data = $count;
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
  #endregion

  ?>