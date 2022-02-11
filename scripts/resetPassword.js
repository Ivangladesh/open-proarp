document.addEventListener("DOMContentLoaded", function () {
    
    const frmResetPassword = document.getElementById('frmResetPassword');

    if(frmResetPassword !== null){
        valid.setupForm(frmResetPassword);
        document.getElementById("btnResetPassword").addEventListener('click', function(e){
            if(valid.form(frmResetPassword)){
                EnviarCorreo();
            }
            e.preventDefault();
        });
    }

    function EnviarCorreo() {
        // let asunto = document.getElementById('txtAsuntoContacto').value;
        // let mensaje = document.getElementById('txtMensajeContacto').value;
        let datos = {
            Action: "EnviarCorreo",
            // Asunto: asunto,
            // Mensaje : mensaje,
            ReCaptchaToken : "token"
        }
        call.post("../php/lib/vendor/resetPassword.php", JSON.stringify(datos), handler, true);
     }

    function handler(e){
        let msg = "";
        switch (e.callback) {
            case "EnviarCorreo":
                if(e.ok){
                    frmResetPassword.reset();
                    msg = 'Correo enviado correctamente, en breve nos comunicaremos contigo.';
                    alerta.notif('ok', msg, 3000);
                } else{
                    alerta.notif('fail', 'Ha ocurrido un error con su sesión.', 3000);
                }
                break;
            default:
                break;
        }
    }

});