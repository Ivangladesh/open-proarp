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
                        readfile('../views/partials/_newPassword.html');
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