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



    document.getElementById("tblMensajes").addEventListener('click', function(e){
        if(e.target.cellIndex < 5){
            let mensajeId = parseInt(e.target.parentNode.cells[0].id);
            obtenerDetalleMensaje(mensajeId);
            actualizarEstadoMensaje(mensajeId);
        } else{
            console.warn('eliminar registro');
        }
        e.preventDefault();
    });


    document.getElementById("tabMensajes").addEventListener('click', function(e){
        ObtenerMensajes();
        e.preventDefault();
    });

    let variable = (e,i) =>{
        if(i === 1){
            return e.target.parentNode.cells[1].children[0].value === "1" ? true : false;
        } else{
            return e.target.parentNode.cells[i].innerText;
        }
    }

    function ObtenerMensajes() {
        let datos = {Action: "ObtenerMensajes"};
        call.post("../php/administrador.php", JSON.stringify(datos), handler, true);
    }

    let poblarTabla = (datos) =>{
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
                '<td style="text-align: center;"><button data-id=' + datos[i].MensajeId + '" class="btn btn-cancel btn-eliminar">&#10006;</button></td>'+
                '</tr>';
                let emptyRow = newTbody.insertRow(newTbody.rows.length);
                emptyRow.innerHTML = newRow; 
                if(datos[i].EstadoMensaje === "Nuevo"){
                    emptyRow.classList.add('tr-new')
                }
        };
        table.appendChild(newTbody);
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

    function handler(e){
        let msg = "";
        switch (e.callback) {
            case "ObtenerUsuario":
                if(!e.ok){
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            case "ObtenerMensajes":
                if(e.ok){
                    poblarTabla(e.data);
                }
                break;
            case "ObtenerDetalleMensaje":
                if(e.ok){
                    mostrarModal(e.data);
                }
                break;
            case "ActualizarEstadoMensaje":
                if(e.ok){
                    ObtenerMensajes();
                } else{
                    msg = 'Ha ocurrido un error al actualizar el estado del mensaje';
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            default:
                break;
        }
    }

    //obtenerUsuario();

});