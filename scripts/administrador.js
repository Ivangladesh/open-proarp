document.addEventListener("DOMContentLoaded", function () {

    /*     const frmContacto = document.getElementById('frmContacto');
    
        if(frmContacto !== null){
            valid.setupForm(frmContacto);
            document.getElementById("btnRegistrarMensajeContacto").addEventListener('click', function(e){
                if(valid.form(frmContacto)){
                    registrarUsuario();
                }
                e.preventDefault();
            });btnCerrarModal
        } */



    document.getElementById("agregarImagen").addEventListener('click', function (e) {
        mostrarModalUploadImagen();
        e.preventDefault();
    });



    // function processSelectedFiles(fileInput) {
    //     var files = fileInput.files;

    //     for (var i = 0; i < files.length; i++) {
    //       alert("Filename " + files[i].name);
    //     }
    //   }


    document.getElementById("tblMensajes").addEventListener('click', function (e) {
        if (e.target.cellIndex < 5) {
            let mensajeId = parseInt(e.target.parentNode.cells[0].id);
            obtenerDetalleMensaje(mensajeId);
            actualizarEstadoMensaje(mensajeId);
        }
        e.preventDefault();
    });


    document.getElementById("tabMensajes").addEventListener('click', function (e) {
        ObtenerMensajes();
        e.preventDefault();
    });

    document.getElementById("tabUsuarios").addEventListener('click', function (e) {
        ObtenerUsuarios();
        e.preventDefault();
    });

    function ObtenerMensajes() {
        let datos = { Action: "ObtenerMensajes" };
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }
    function ObtenerUsuarios() {
        let datos = { Action: "ObtenerUsuarios" };
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    let poblarMensajes = (datos) => {
        let table = document.getElementById('tblMensajes');
        var oldTbody = table.children[1];
        var newTbody = document.createElement('tbody');
        table.removeChild(oldTbody);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
            // var element = $('<button class="btn btn-info">Modificar</button>').on('click', function(){
            //     obtenerDetalleMensaje(datos[i].MensajeId);
            // });
            var fechaFormateada = new Date(datos[i].FechaRecepcion).toLocaleDateString("es-MX");
            let newRow =
                '<tr>' +
                '<td style="display: none;" id=' + datos[i].MensajeId + '></td>' +
                '<td>' + datos[i].NombreCompleto + '</td>' +
                '<td>' + datos[i].Asunto + '</td>' +
                '<td>' + datos[i].EstadoMensaje + '</td>' +
                '<td>' + fechaFormateada + '</td>' +
                '<td style="text-align: center;"><button data-id=' + datos[i].MensajeId + ' class="btn btn-cancel btn-eliminar button-sm">&#10006;</button></td>' +
                '</tr>';
            let emptyRow = newTbody.insertRow(newTbody.rows.length);
            emptyRow.innerHTML = newRow;
            if (datos[i].EstadoMensaje === "Nuevo") {
                emptyRow.classList.add('tr-new')
            }
        };
        table.appendChild(newTbody);
        eliminarEventListener();
    }

    let poblarUsuarios = (datos) => {
        let table = document.getElementById('tblUsuarios');
        var oldTbody = table.children[1];
        var newTbody = document.createElement('tbody');
        table.removeChild(oldTbody);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
            let newRow =
                '<tr>' +
                '<td style="display: none;" id=' + datos[i].UsuarioId + '></td>' +
                '<td>' + datos[i].NombreCompleto + '</td>' +
                '<td>' + datos[i].Email + '</td>' +
                '<td>' + datos[i].FechaNacimiento + '</td>' +
                '<td>' + datos[i].Tipo + '</td>' +
                '<td>' + datos[i].EstadoUsuario + '</td>' +
                // '<td style="text-align: center;"><button data-id=' + datos[i].UsuarioId + ' class="btn btn-cancel btn-eliminar button-sm">&#10006;</button></td>'+
                '</tr>';
            let emptyRow = newTbody.insertRow(newTbody.rows.length);
            emptyRow.innerHTML = newRow;
        };
        table.appendChild(newTbody);
        eliminarEventListener();
    }

    function obtenerDetalleMensaje(mensajeId) {
        let datos = {
            Action: "ObtenerDetalleMensaje",
            MensajeId: mensajeId
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    function actualizarEstadoMensaje(mensajeId) {
        let datos = {
            Action: "ActualizarEstadoMensaje",
            MensajeId: mensajeId
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    const mostrarModal = (datos) => {
        var fechaFormateada = new Date(datos.FechaRecepcion).toLocaleDateString("es-MX");
        document.getElementById('txtNombreD').value = datos.NombreCompleto;
        document.getElementById('txtEmailD').value = datos.UsuarioEmail;
        document.getElementById('txtAsuntoD').value = datos.Asunto;
        document.getElementById('txtFechaD').value = fechaFormateada;
        document.getElementById('txtMensajeD').value = datos.Mensaje;
        document.getElementById('mdlMensaje').style.display = "block"
    }

    const mostrarModalUploadImagen = () => {
        document.getElementById("file-upload").value = null;
        document.getElementById('txtArchivoNombre').innerHTML = "";
        document.getElementById('mdlUploadImagen').style.display = "block"
        document.getElementById("file-upload").addEventListener('change', function (e) {
            var fileName = e.target.files[0].name;
            document.getElementById('txtArchivoNombre').innerHTML = fileName;
            e.preventDefault();
        });
    }

    function eliminarMensaje(id) {
        let datos = {
            Action: "EliminarMensaje",
            MensajeId: id
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    function eliminarEventListener() {
        const eliminarList = document.getElementsByClassName('btn-eliminar');
        for (let i = 0, iLen = eliminarList.length; i < iLen; i++) {
            eliminarList[i].addEventListener("click", function () {
                let rowId = parseInt(this.getAttribute('data-id'));
                alerta.confirm(rowId, handler);
            });
        }
    }

    function handler(e) {
        let msg = "";
        switch (e.callback) {
            case "ObtenerUsuario":
                if (!e.ok) {
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            case "ObtenerMensajes":
                if (e.ok) {
                    poblarMensajes(e.data);
                }
                break;
            case "ObtenerUsuarios":
                if (e.ok) {
                    poblarUsuarios(e.data);
                }
                break;
            case "ObtenerDetalleMensaje":
                if (e.ok) {
                    mostrarModal(e.data);
                }
                break;
            case "ActualizarEstadoMensaje":
                if (e.ok) {
                    ObtenerMensajes();
                } else {
                    msg = 'Ha ocurrido un error al actualizar el estado del mensaje.';
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            case "Confirmar":
                if (e.ok) {
                    eliminarMensaje(e.data);
                } else {
                    msg = 'Ha ocurrido un error al actualizar el estado del mensaje.';
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            case "EliminarMensaje":
                if (e.ok) {
                    alerta.notif('ok', 'Mensaje eliminado correctamente.', 3000);
                    ObtenerMensajes();
                } else {
                    alerta.notif('fail', 'Ha ocurrido un error al eliminar el mensaje.', 3000);
                }
                break;
            default:
                break;
        }
    }


});