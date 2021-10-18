document.addEventListener("DOMContentLoaded", function () {
    const formRegistrar = document.getElementById('frmRegistrar');
    const frmLogin = document.getElementById('frmLogin');
    valid.setupForm(frmLogin);
    valid.setupForm(formRegistrar);

    document.getElementById("btnLogin").addEventListener('click', function(e){
        if(valid.form(frmLogin)){
            registrarUsuario();
        }
        e.preventDefault();
    });

    document.getElementById("btnRegistrar").addEventListener('click', function(e){
        if(valid.form(formRegistrar)){
            registrarUsuario();
        }
        e.preventDefault();
    });

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
        call.post("../php/session.php", datos, callback, true);
    }
    function callback(){

    }

});