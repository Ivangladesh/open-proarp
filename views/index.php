<?php session_start();
if (isset($_SESSION['SessionStorage'])) {
    $session = $_SESSION['SessionStorage'];
    list($tipo, $username, $nombre, $usuarioId, $fecha) = explode('|', base64_decode($session));
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proarp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="stylesheet" type="text/css" href="../css/nav.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6LfB2CQcAAAAAHBesFhEH8KFjd3Cn14Kt-cexHCm"></script>
</head>

<body>
    <nav>
        <?php
            if (isset($_SESSION['SessionStorage'])) {
            echo '<ul class="" style="justify-content: right;display: flex; flex-wrap: wrap;">
                <li><small style="display: flex;">Sesión iniciada como:&nbsp<strong>'.$nombre.'</strong></small>
                </ul>';
            }
        ?>
        <ul class="menu">
            <li class="logo"><img class="img-menu" src="../assets/proarp-arrow-low-min.png"></img></li>
            <?php
            if (isset($_SESSION['SessionStorage'])) {
                echo
                '<li class="item"><a href="#" class="nav-link" id="inicio" data-id="div-inicio">Inicio</a></li>';
                if ($tipo == 1) {
                    echo '<li class="item"><a href="#" class="nav-link" id="inventario" data-id="div-inventario">Inventario</a></li>';
                } else {
                    echo '<li class="item"><a href="#" class="nav-link" id="inventario" data-id="div-inventario">Catálogo</a></li>';
                }
                echo
                '<li class="item"><a href="#" class="nav-link" id="contacto" data-id="div-contacto">Contacto</a></li>';
                if ($tipo == 1) {
                    echo '<li class="item"><a href="#" class="nav-link" id="administrador" data-id="div-administrador">Administrador</a></li>';
                }
                echo '<li class="item button secondary"><a href="#" onclick="cerrarSesion()">Cerrar sesión</a></li>';
                echo '<li class="item button secondary">'.$nombre.'</li>';
            } else {
                echo
                '<li class="item button"><a href="#" class="nav-link" id="login" data-id="div-inicio-sesion">Iniciar sesión</a></li>
                <li class="item button"><a href="#" class="nav-link" id="registro" data-id="div-registro-usuario">Registro</a></li>';
            }
            ?>
            <li class="toggle"><a href="#" id="toggleBtn"><i class="fa fa-bars toggle"></i></a></li>
        </ul>
    </nav>
    <div class="canvas" id="mainContainer">
        <main>
            <br>
            <div class="container-fluid" id="content">
                <?php
                if (isset($_SESSION['SessionStorage'])) {
                    readfile('../views/partials/_main.html');
                    readfile('../views/partials/_contacto.html');
                    if ($tipo == 1) {
                        readfile('../views/partials/_administrador.html');
                    }
                    if ($tipo == 1 || $tipo == 2) {
                        readfile('../views/partials/_inventario.html');
                    }
                } else {
                    readfile('../views/partials/_registro.html');
                    readfile('../views/partials/_login.html');
                    readfile('../views/partials/_resetPassword.html');
                }
                ?>
            </div>
        </main>
    </div>
    <?php
    readfile('../views/partials/_footer.html');
    readfile('../views/partials/modals/_modal-mensaje.html');
    readfile('../views/partials/modals/_modal-confirm.html');
    if (isset($_SESSION['SessionStorage'])) {
        if ($tipo == 1) {
            readfile('../views/partials/modals/_modal-upload-imagen.html');
            readfile('../views/partials/modals/_modal-detalle-persona.html');
            readfile('../views/partials/modals/_modal-nuevo-usuario.html');
        }
        if ($tipo == 1 || $tipo == 2) {
            readfile('../views/partials/modals/_modal-nuevo-producto.html');
            readfile('../views/partials/modals/_modal-nuevo-proveedor.html');
            readfile('../views/partials/modals/_modal-detalle-producto.html');
            readfile('../views/partials/modals/_modal-detalle-proveedor.html');
        }
    }
    ?>
</body>
<script src="../scripts/alert.js?v=1.000"></script>
<script src="../scripts/ajax.js?v=1.000"></script>
<script src="../scripts/validation.js?v=1.000"></script>
<script src="../scripts/zero.js?v=1.001"></script>
<script src="../scripts/resetPassword.js?v=1.001"></script>
<?php
if (isset($_SESSION['SessionStorage'])) {
    echo '<script>
    const cerrarSesion = () =>{
        if(sessionStorage.getItem("Session") !== null){
            sessionStorage.removeItem("Session");
        };
        call.post("../php/session.php", JSON.stringify({ Action: "CerrarSesion"}), handler, true);
    }
    function handler(e){
        if(e.callback === "EstadoSesion"){
            console.info(e.ok + " " + e.data);
        } else{
            if(e.ok){
                alerta.notif("info", e.data, 2000);
                setTimeout(function(){
                    location.reload();
                },2100);
            }else{
                console.log(e);
            }
        }

    }
    setTimeout(function(){
        if (sessionStorage.getItem("Session") !== null) {
            setInterval(sessionStatus, 10000);
        };
    },5000);

    function sessionStatus() {
        console.log("Check session");
        call.post("../php/session.php", JSON.stringify({
            Action: "EstadoSesion"
        }), handler, true);
    }
    </script>';
    echo '<script src="../scripts/contacto.js?v=1.001"></script>';
    if ($tipo == 1) {
        echo '<script src="../scripts/administrador.js?v=1.002"></script>';
        echo '<script src="../scripts/imagen.js?v=1.003"></script>';
    }
    if ($tipo == 1 || $tipo == 2) {
        echo '<script src="../scripts/inventario.js?v=1.001"></script>';
    }
} else {
    echo '<script src="../scripts/registro.js?v=1.001"></script>';
}
?>
</html>