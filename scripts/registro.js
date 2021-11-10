document.addEventListener("DOMContentLoaded", function () {

    const formRegistrar = document.getElementById('frmRegistrar');
    const frmLogin = document.getElementById('frmLogin');
    const maxYear = new Date(new Date().setFullYear(new Date().getFullYear() - 18));
    const maxDateFormatted = new Date(maxYear).toISOString().split("T")[0];

    document.getElementById("txtRegistroFecha").setAttribute("max", maxDateFormatted);

    if(frmLogin !== null || formRegistrar !== null){
        valid.setupForm(frmLogin);
        valid.setupForm(formRegistrar);
        document.getElementById("btnLogin").addEventListener('click', function(e){
            if(valid.form(frmLogin)){
                iniciarSesion();
            }
            e.preventDefault();
        });
    
        document.getElementById("btnRegistrar").addEventListener('click', function(e){
            if(valid.form(formRegistrar)){
                registrarUsuario();
            }
            e.preventDefault();
        });
    }

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

     function iniciarSesion() {
        let email = document.getElementById('txtUsername').value;
        let password = btoa(document.getElementById('txtPassword').value);
        let datos = {
            Action: "IniciarSesion",
            Email : email,
            Password: password,
            ReCaptchaToken : "token"
        }
        call.post("../php/session.php", JSON.stringify(datos), handler, true);
    }

    function validarSesion(token) {
        const _token = token;
        let datos = {
            Action: "ValidarSesion",
            Token: token
        }
        call.post("../php/session.php", JSON.stringify(datos), handler, true);
    }

    function handler(e){
        let msg = "";
        switch (e.callback) {
            case "EnviarToken":
                sessionStorage.setItem("Session", e.data);
                validarSesion(e.data);
                break;
            case "IniciarSesion":
                if(!e.ok){
                    alerta.notif('fail', 'Ha ocurrido un error. Verifique su usuario y contraseña', 3000);
                }
                break;
            case "RegistrarUsuario":
                msg = `Registro realizado correctamente, use el correo registrado para ingresar.`;
                if(e.ok){
                    formRegistrar.reset();
                    alerta.notif('ok', msg, 2000);
                    setTimeout(function(){zero.navHandler("div-inicio-sesion");},2100);
                } else{
                    alerta.notif('fail', e.data, 3000);
                }
                break;
            case "ValidarSesion":
                msg = `Sesión iniciada correctamente, Bienvenido!`;
                if(e.ok){
                    alerta.notif('ok', msg, 2000);
                    if(self.location.pathname === "/proarp/views/index.php"){
                        alerta.notif('ok', msg, 2500);
                        setTimeout(function(){location.reload();},2100);
                    }
                } else{
                    alerta.notif('fail', 'Ha ocurrido un error, consulte a su administrador.', 3000);
                }

                break;
            default:
                break;
        }
    }

});