<?php

session_start();
if (isset($_SESSION['TempSession'])) {
    $session = $_SESSION['TempSession'];
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
        <ul class="menu">
            <li class="logo"><img class="img-menu" src="../assets/proarp-arrow-low-min.png"></img></li>
            <?php
                echo
                '<li class="item button"><a href="./index.php" class="" id="registro">Ir al inicio</a></li>';
            ?>
            <li class="toggle"><a href="#" id="toggleBtn"><i class="fa fa-bars toggle"></i></a></li>
        </ul>
    </nav>
    <div class="canvas" id="mainContainer">
        <main>
            <br>
            <div class="container-fluid" id="content">
                <?php
                    if (isset($_SESSION['TempSession'])) {
                        list($tipo, $username, $nombre, $usuarioId, $fecha) = explode('|', base64_decode($session));
                        echo '<div class="animate-bottom sub-container" id="div-inicio-sesion">
                        <div class="container">
                            <h1>Reestablecer contraseña</h1>
                            <div class="card animate-bottom">
                                <hr class="divider-base">
                                <div class="row">
                                    <div class="card-body login">
                                        <div class="row">
                                            <div class="frm-col-1">
                                                <img class="img-login" src="../assets/proarp-min.png">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <form id="frmNewPassword">
                                                <div class="frm-col-1">
                                                    <div class="form-group">
                                                        <label for="txtResetUsername">Email</label>
                                                        <input type="text" data-id="email" class="form-control input-form" id="txtResetUsername" value= "'.$username.'" disabled>
                                                    </div>
                                                </div>
                                                <div class="frm-col-2">
                                                    <div class="form-group">
                                                        <label for="txtResetPassword">Ingrese una nueva contraseña:
                                                            <i class="tooltip">?
                                                                <span class="tooltiptext">Longitud mínima de 8 y máxima de 15 caracteres y debe contener por lo menos un caracter especial "#$^+=!*()@%&". Por ejemplo: contrasena123!, contrasen@
                                                                </span>
                                                            </i>
                                                        </label>
                                                        <input type="password" data-id="password" for="txtResetConfirmaPassword" class="form-control input-form" id="txtResetPassword">
                                                    </div>
                                                </div>
                                                <div class="frm-col-2">
                                                    <div class="form-group">
                                                        <label for="txtResetConfirmaPassword">Confirme la contraseña: </label>
                                                        <input type="password"data-id="password" for="txtResetConfirmaPassword" class="form-control input-form" id="txtResetConfirmaPassword">
                                                    </div>
                                                </div>
                                                <div class="frm-col-1">
                                                    <div class="form-group">
                                                        <button class="btn btn-primary" id="btnNewPassword" data-id="frmNewPassword">Reestablecer</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                    } else{
                        echo
                        '<div class="animate-bottom sub-container" id="div-inicio-sesion">
                        <div class="container">
                            <h1>Prohibido</h1>
                            <div class="card animate-bottom">
                                <hr class="divider-base">
                                <div class="row">
                                    <div class="card-body login">
                                        <div class="row">
                                            <h2 style="text-align : center">No tiene acceso</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
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
<script src="../scripts/resetPassword.js?v=1.002"></script>
</html>