document.addEventListener("DOMContentLoaded", function () {
    
/*     const frmContacto = document.getElementById('frmContacto');

    if(frmContacto !== null){
        valid.setupForm(frmContacto);
        document.getElementById("btnRegistrarMensajeContacto").addEventListener('click', function(e){
            if(valid.form(frmContacto)){
                registrarUsuario();
            }
            e.preventDefault();
        });
    } */

    function registrarUsuario() {
        let nombre = document.getElementById('txtRegistroNombre').value;
        let paterno = document.getElementById('txtRegistroPaterno').value;
        let materno = document.getElementById('txtRegistroMaterno').value;
        let fecha = document.getElementById('txtRegistroFecha').value;
        let email = document.getElementById('txtRegistroEmail').value;
        let password = btoa(document.getElementById('txtRegistroPassword').value);

        let datos = {
            Action: "RegistrarUsuario",
            Nombre: nombre,
            Paterno : paterno,
            Materno : materno,
            FechaNacimiento : fecha,
            Email : email,
            Password: password,
            ReCaptchaToken : "token"
        }
        call.post("../php/session.php", JSON.stringify(datos), handler, true);
     }

     function obtenerUsuario() {
        let datos = {
            Action: "ObtenerUsuario"
        }
        call.post("../php/session.php", JSON.stringify(datos), handler, true);
    }

    function handler(e){
        let msg = "";
        switch (e.callback) {
            case "ObtenerUsuario":
                if(!e.ok){
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            default:
                break;
        }
    }

    //obtenerUsuario();

});