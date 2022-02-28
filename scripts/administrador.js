document.addEventListener("DOMContentLoaded", function () {

    let tipoUsuario, estadoUsuario;

    const frmDetalleUsuario = document.getElementById('frmDetalleUsuario');

    if(frmDetalleUsuario !== null){
        valid.setupForm(frmDetalleUsuario);
        document.getElementById("btnActualizarUsuario").addEventListener('click', function(e){
            if(valid.form(frmDetalleUsuario)){
                actualizarUsuario(e);
            }
            e.preventDefault();
        });
    }

    document.getElementById("btnEliminarUsuario").addEventListener('click', function (e) {
        let dataId = e.target.getAttribute("data-id");
        let data = {callback : "EliminarUsuario", id : dataId}
        alerta.confirm(data, handler);
        e.preventDefault();
    });

    document.getElementById("agregarImagen").addEventListener('click', function (e) {
        mostrarModalUploadImagen();
        e.preventDefault();
    });

    document.getElementById("tblMensajes").addEventListener('click', function (e) {
        if (e.target.cellIndex < 5) {
            let mensajeId = parseInt(e.target.parentNode.cells[0].id);
            obtenerDetalleMensaje(mensajeId);
            actualizarEstadoMensaje(mensajeId);
        }
        e.preventDefault();
    });

    document.getElementById("tblUsuarios").addEventListener('click', function (e) {
        if (e.target.cellIndex < 5) {
            let usuarioId = parseInt(e.target.parentNode.cells[0].id);
            obtenerDetalleUsuario(usuarioId);
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
        if(datos.length > 0){
            let longitud = datos.length - 1;
            for (let i = 0; i <= longitud; i++) {
                var fechaFormateada = new Date(datos[i].FechaRecepcion).toLocaleDateString("es-MX");
                let newRow =
                    '<tr>' + '<td style="display: none;" id=' + datos[i].MensajeId + '></td>' +
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
        } else{
            alerta.info("fail", "No cuenta con registros.", 3000);
        }

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

    function actualizarUsuario(e) {
       let nombre = document.getElementById('txtNombreUsr').value;
       let paterno = document.getElementById('txtPaternoUsr').value;
       let materno = document.getElementById('txtMaternoUsr').value;
       let email = document.getElementById('txtEmailUsr').value;
       let fecha = document.getElementById('txtFechaNacimientoUsr').value;
       let tipo = document.getElementById('cmbTipoUsuario').value;
       let estado = document.getElementById('cmbEstadoUsuario').value;
       let dataId = e.target.getAttribute("data-id");
        let datos = {
            Action: "ActualizarUsuario",
            UsuarioId: dataId,
            Nombre: nombre,
            Paterno: paterno,
            Materno: materno,
            Email: email,
            FechaNacimiento: fecha,
            Tipo: tipo,
            Estado: estado
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    function obtenerDetalleUsuario(usuarioId) {
        let datos = {
            Action: "ObtenerDetalleUsuario",
            UsuarioId: usuarioId
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    function obtenerTipoUsuario() {
        let datos = {
            Action: "ObtenerTipoUsuario"
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }
    
    function obtenerEstadoUsuario() {
        let datos = {
            Action: "ObtenerEstadoUsuario"
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    function eliminarUsuario(id) {
        let datos = {
            Action: "EliminarUsuario",
            UsuarioId: id
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
        document.getElementById("imgUpload").value = null;
        document.getElementById('txtArchivoNombre').innerHTML = "";
        document.getElementById('mdlUploadImagen').style.display = "block"
        document.getElementById("imgUpload").addEventListener('change', function (e) {
            var fileName = e.target.files[0].name;
            document.getElementById('txtArchivoNombre').innerHTML = fileName;
            e.preventDefault();
        });
    }

    const mostrarModalUsuario = (datos) => {
        obtenerTipoUsuario();
        obtenerEstadoUsuario();
        document.getElementById('txtNombreUsr').value = datos.Nombres;
        document.getElementById('txtPaternoUsr').value = datos.Paterno;
        document.getElementById('txtMaternoUsr').value = datos.Materno;
        document.getElementById('txtEmailUsr').value = datos.Email;
        tipoUsuario = datos.TipoUsuario;
        estadoUsuario = datos.EstadoUsuario;
        document.getElementById('txtFechaNacimientoUsr').value = datos.FechaNacimiento;
        document.getElementById('btnActualizarUsuario').setAttribute("data-id", datos.UsuarioId);
        document.getElementById('btnEliminarUsuario').setAttribute("data-id", datos.UsuarioId);
        document.getElementById('mdlDetallePersona').style.display = "block"
    }

    let poblarComboTipoUsuario = (datos) =>{
		let select = document.getElementById("cmbTipoUsuario");
		removeOptions(select);
		let placeHolder = document.createElement("option");
		placeHolder.text = "Seleccione...";
		placeHolder.value = "";
		placeHolder.disabled = true;
		placeHolder.selected = true;
		select.appendChild(placeHolder);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
			var option = document.createElement("option");
			option.text = datos[i].Tipo;
			option.value = datos[i].TipoUsuarioId;
			select.appendChild(option);
        };
    }

    let poblarComboEstadoUsuario = (datos) =>{
		let select = document.getElementById("cmbEstadoUsuario");
		removeOptions(select);
		let placeHolder = document.createElement("option");
		placeHolder.text = "Seleccione...";
		placeHolder.value = "";
		placeHolder.disabled = true;
		placeHolder.selected = true;
		select.appendChild(placeHolder);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
			var option = document.createElement("option");
			option.text = datos[i].EstadoUsuario;
			option.value = datos[i].EstadoUsuarioId;
			select.appendChild(option);
        };
    }

    function selectElement(id, valueToSelect) {    
        let element = document.getElementById(id);
        element.value = valueToSelect;
    }

	function removeOptions(selectElement) {
		var i, L = selectElement.options.length - 1;
		for(i = L; i >= 0; i--) {
		   selectElement.remove(i);
		}
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
                let data = {callback : "EliminarMensaje", id : rowId}
                alerta.confirm(data, handler);
            });
        }
    }

    function handler(e) {
        let msg = "";
        switch (e.callback) {
            case "ActualizarUsuario":
                if (e.ok) {
                    alerta.notif('ok', 'Actualización correcta', 3000);
                    document.getElementById("mdlDetallePersona").style.display = "none";
                    ObtenerUsuarios();
                } else {
                    alerta.notif('fail', 'Ha ocurrido un error.', 3000);
                }
                break;
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
            case "ObtenerTipoUsuario":
                if (e.ok) {
                    poblarComboTipoUsuario(e.data);
                    selectElement('cmbTipoUsuario', tipoUsuario);
                }
                break;
            case "ObtenerEstadoUsuario":
                if (e.ok) {
                    poblarComboEstadoUsuario(e.data);
                    selectElement('cmbEstadoUsuario', estadoUsuario);
                }
                break;
            case "ObtenerDetalleUsuario":
                if (e.ok) {
                    mostrarModalUsuario(e.data);
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
                    if(e.data.callback === "EliminarMensaje"){
                        eliminarMensaje(e.data.id);
                    } else if(e.data.callback === "EliminarUsuario"){
                        eliminarUsuario(e.data.id);
                    }
                    document.getElementById("mdlDetallePersona").style.display = "none";
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
            case "EliminarUsuario":
                if (e.ok) {
                    alerta.notif('ok', 'Usuario eliminado correctamente.', 3000);
                    ObtenerUsuarios();
                } else {
                    alerta.notif('fail', 'Ha ocurrido un error al eliminar el usuario.', 3000);
                }
                break;
            default:
                break;
        }
    }

    ObtenerMensajes();
});