document.addEventListener("DOMContentLoaded", function () {
    
    const frmContacto = document.getElementById('frmContacto');

    if(frmContacto !== null){
        valid.setupForm(frmContacto);
        document.getElementById("btnRegistrarMensajeContacto").addEventListener('click', function(e){
            if(valid.form(frmContacto)){
                RegistrarMensaje();
            }
            e.preventDefault();
        });
    }

    function RegistrarMensaje() {
        let asunto = document.getElementById('txtAsuntoContacto').value;
        let mensaje = document.getElementById('txtMensajeContacto').value;
        let datos = {
            Action: "RegistrarMensaje",
            Asunto: asunto,
            Mensaje : mensaje,
            ReCaptchaToken : "token"
        }
        call.post("../php/contacto.php", JSON.stringify(datos), handler, true);
     }

    function handler(e){
        let msg = "";
        switch (e.callback) {
            case "RegistrarMensaje":
                if(e.ok){
                    frmContacto.reset();
                    msg = 'Mensaje registrado correctamente, en breve nos comunicaremos contigo.';
                    alerta.notif('ok', msg, 3000);
                } else{
                    alerta.notif('fail', 'Ha ocurrido un error, consulte a su administrador.', 3000);
                }
                break;
            default:
                break;
        }
    }

});