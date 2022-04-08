document.addEventListener("DOMContentLoaded", function () {
    
    const frmResetPassword = document.getElementById('frmResetPassword');
    const frmNewPassword = document.getElementById('frmNewPassword');

    if(frmResetPassword !== null){
        valid.setupForm(frmResetPassword);
        document.getElementById("btnResetPassword").addEventListener('click', function(e){
            if(valid.form(frmResetPassword)){
                reCaptcha(false);
            }
            e.preventDefault();
        });
    }

    if(frmNewPassword !== null){
        valid.setupForm(frmNewPassword);
        document.getElementById("btnNewPassword").addEventListener('click', function(e){
            if(valid.form(frmNewPassword)){
                reCaptcha(true);
            }
            e.preventDefault();
        });
    }

    function EnviarCorreo(token) {
        let email = document.getElementById('txtResetEmail').value;
        let datos = {
            Action: "EnviarCorreo",
            Email: email,
            ReCaptchaToken : token
        }
        call.post("../php/email.php", JSON.stringify(datos), handler, true);
     }

     function ActualizarContrasena(token) {
        let email = document.getElementById('txtResetUsername').value;
        let contrasena = btoa(document.getElementById('txtResetPassword').value);
        let datos = {
            Action: "ActualizarContrasena",
            Email: email,
            Contrasena: contrasena,
            ReCaptchaToken : token
        }
        call.post("../php/email.php", JSON.stringify(datos), handler, true);
     }

    function handler(e){
        let msg = "";
        switch (e.callback) {
            case "EnviarCorreo":
                if(e.ok){
                    frmResetPassword.reset();
                    
                    msg = 'Correo enviado correctamente, este podría demorar un poco, no olvides revisar tu bandeja de spam.';
                    alerta.notif('ok', msg, 10000);
                    setTimeout(function(){location.reload();},10100);
                } else{
                    alerta.notif('fail', 'Ha ocurrido un error, consulte a su administrador.', 3000);
                }
                break;
            case "ActualizarContrasena":
                if(e.ok){
                    frmNewPassword.reset();
                    msg = 'Contraseña actualizada correctamente.';
                    alerta.notif('ok', msg, 3000);
                    setTimeout(function(){location.href = 'index.php';},3000);
                } else{
                    alerta.notif('fail', 'Ha ocurrido un error, consulte a su administrador.', 3000);
                }
                break;
            default:
                break;
        }
    }

    function reCaptcha(actualizaContrasena) {
        grecaptcha.ready(function() {
          grecaptcha.execute('6LfB2CQcAAAAAHBesFhEH8KFjd3Cn14Kt-cexHCm',
          {action: 'validarInicioSesion'}).then(function(token) {
            if(actualizaContrasena){
                ActualizarContrasena(token);
            } else{
                EnviarCorreo(token);
            }
          });
        });
      }

});