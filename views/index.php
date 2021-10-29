<?php session_start();
if (isset($_SESSION['SessionStorage'])) {
    $session = $_SESSION['SessionStorage'];
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="stylesheet" type="text/css" href="../css/nav.css">
</head>

<body>
    <nav>
        <ul class="menu">
            <li class="logo"><img class="img-menu" src="../assets/proarp-arrow-low-min.png"></img></li>
            <?php
            if (isset($_SESSION['SessionStorage'])) {
                $session = $_SESSION['SessionStorage'];
                echo
                '<li class="item"><a href="#" class="nav-link" id="inicio" data-id="div-inicio">Inicio</a></li>
                <li class="item"><a href="#" class="nav-link" id="inventario" data-id="div-inventario">Catálogo</a></li>
                <li class="item"><a href="#" class="nav-link" id="contacto" data-id="div-contacto">Contacto</a></li>
                <li class="item button secondary"><a href="#" onclick="cerrarSesion()">Cerrar sesión</a></li>';
            } else{
                echo
                '<li class="item button"><a href="#" class="nav-link" id="login" data-id="div-inicio-sesion">Iniciar sesión</a></li>
                <li class="item button"><a href="#" class="nav-link" id="login" data-id="div-registro-usuario">Registro</a></li>';
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
                    $session = $_SESSION['SessionStorage'];
                    readfile('../views/partials/_main.html');
                    readfile('../views/partials/_contacto.html');
                } else{
                    readfile('../views/partials/_registro.html');
                    readfile('../views/partials/_login.html');
                }
                ?>
            </div>
        </main>
    </div>
</body>
<script src="../scripts/alert.js?v=1.000"></script>
<script src="../scripts/ajax.js?v=1.000"></script>
<script src="../scripts/validation.js?v=1.000"></script>
<script src="../scripts/zero.js?v=1.001"></script>
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
        if(e.ok){
            alerta.notif("info", e.data, 2000);
            setTimeout(function(){
                location.reload();
            },2100);
        }else{
            console.log(e);
        }
    }
    </script>';
} else{
    echo '<script src="../scripts/registro.js?v=1.001"></script>';
}
?>


</html>