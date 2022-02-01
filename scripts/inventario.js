document.addEventListener("DOMContentLoaded", function () {
    
    document.getElementById("inventario").addEventListener('click', function(e){
        ObtenerProductos();
        e.preventDefault();
    });

    // document.getElementById("tblMensajes").addEventListener('click', function(e){
    //     if(e.target.cellIndex < 5){
    //         let mensajeId = parseInt(e.target.parentNode.cells[0].id);
    //         obtenerDetalleMensaje(mensajeId);
    //         actualizarEstadoMensaje(mensajeId);
    //     }
    //     e.preventDefault();
    // });

    // document.getElementById("tabMensajes").addEventListener('click', function(e){
    //     ObtenerProductos();
    //     e.preventDefault();
    // });

    function ObtenerProductos() {
        let datos = {Action: "ObtenerProductos"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    let poblarMensajes = (datos) =>{
        let table = document.getElementById('tblInventario');
        var oldTbody = table.children[1];
        var newTbody = document.createElement('tbody');
        table.removeChild(oldTbody);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
            var fechaFormateada = new Date(datos[i].FechaRecepcion).toLocaleDateString("es-MX");
            let newRow =
                '<tr>' +
                '<td style="display: none;" id=' + datos[i].InventarioId + '></td>' +
                '<td>' + datos[i].Nombre + '</td>' +
                '<td>' + datos[i].Descripcion + '</td>' +
                '<td>' + datos[i].Existencias + '</td>' +
                '<td>' + datos[i].EstadoInventario + '</td>' +
                '</tr>';
                let emptyRow = newTbody.insertRow(newTbody.rows.length);
                emptyRow.innerHTML = newRow; 
                if(datos[i].EstadoMensaje === "Nuevo"){
                    emptyRow.classList.add('tr-new')
                }
        };
        table.appendChild(newTbody);
        eliminarEventListener();
    }

    function obtenerDetalleMensaje(mensajeId){
        let datos = {
            Action: "ObtenerDetalleMensaje",
            MensajeId: mensajeId
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    function actualizarEstadoMensaje(mensajeId){
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

    function eliminarMensaje(id){
        let datos = {
            Action: "EliminarMensaje",
            MensajeId: id
        }
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    function eliminarEventListener (){
        const eliminarList = document.getElementsByClassName('btn-eliminar');
        for (let i = 0, iLen = eliminarList.length; i < iLen; i++) {
            eliminarList[i].addEventListener("click", function () {
                let rowId = parseInt(this.getAttribute('data-id'));
                alerta.confirm(rowId, handler);
            });
        }
    }

    function handler(e){
        let msg = "";
        switch (e.callback) {
            case "ObtenerUsuario":
                if(!e.ok){
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            case "ObtenerProductos":
                if(e.ok){
                    poblarMensajes(e.data);
                }
                break;
            case "ObtenerUsuarios":
                if(e.ok){
                    poblarUsuarios(e.data);
                }
                break;
            case "ObtenerDetalleMensaje":
                if(e.ok){
                    mostrarModal(e.data);
                }
                break;
            case "ActualizarEstadoMensaje":
                if(e.ok){
                    ObtenerProductos();
                } else{
                    msg = 'Ha ocurrido un error al actualizar el estado del mensaje.';
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            case "Confirmar":
                if(e.ok){
                    eliminarMensaje(e.data);
                } else{
                    msg = 'Ha ocurrido un error al actualizar el estado del mensaje.';
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            case "EliminarMensaje":
                if(e.ok){
                    alerta.notif('ok', 'Mensaje eliminado correctamente.', 3000);
                    ObtenerProductos();
                } else{
                    alerta.notif('fail', 'Ha ocurrido un error al eliminar el mensaje.', 3000);
                }
                break;
            default:
                break;
        }
    }
    

});